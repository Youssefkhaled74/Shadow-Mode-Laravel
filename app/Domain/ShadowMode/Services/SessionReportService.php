<?php

namespace App\Domain\ShadowMode\Services;

use App\Models\ReportTimelineMoment;
use App\Models\SessionReport;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SessionReportService
{
    public function generate(TrainingSession $session, ?User $actor = null): SessionReport
    {
        $metrics = $session->metrics()->orderBy('recorded_at')->get();
        $events = $session->events()->latest('occurred_at')->limit(10)->get();

        $overall = (int) round($metrics->avg('overall_score') ?? 0);
        $confidence = (int) round($metrics->avg('confidence_score') ?? 0);
        $clarity = (int) round($metrics->avg('clarity_score') ?? 0);
        $pace = (int) round($metrics->avg('pace_score') ?? 0);
        $filler = (int) round($metrics->avg('filler_word_count') ?? 0);
        $missed = (int) round($metrics->avg('missed_question_count') ?? 0);

        $bestMoment = $metrics->sortByDesc('overall_score')->first();
        $worstMoment = $metrics->sortBy('overall_score')->first();

        return DB::transaction(function () use (
            $session,
            $actor,
            $overall,
            $confidence,
            $clarity,
            $pace,
            $filler,
            $missed,
            $bestMoment,
            $worstMoment,
            $events
        ): SessionReport {
            $report = SessionReport::query()->updateOrCreate(
                ['training_session_id' => $session->id],
                [
                    'generated_by' => $actor?->id,
                    'overall_score' => $overall,
                    'summary' => $this->buildNarrativeSummary($overall, $confidence, $clarity, $pace, $filler, $missed),
                    'best_response' => $bestMoment
                        ? "Highest impact segment at {$bestMoment->recorded_at?->format('H:i:s')} with score {$bestMoment->overall_score}."
                        : 'No measurable best response captured.',
                    'weakest_response' => $worstMoment
                        ? "Lowest point at {$worstMoment->recorded_at?->format('H:i:s')} with score {$worstMoment->overall_score}."
                        : 'No measurable weak response captured.',
                    'key_mistakes' => $this->buildKeyMistakes($filler, $missed, $pace),
                    'improvement_suggestions' => $this->buildSuggestions($confidence, $clarity, $pace, $filler, $missed),
                    'score_breakdown' => [
                        'confidence' => $confidence,
                        'clarity' => $clarity,
                        'pace' => $pace,
                        'overall' => $overall,
                    ],
                    'generated_at' => now(),
                ],
            );

            ReportTimelineMoment::query()->where('session_report_id', $report->id)->delete();

            $this->buildTimelineMoments($events)->each(function (array $moment) use ($report, $session): void {
                ReportTimelineMoment::query()->create([
                    ...$moment,
                    'session_report_id' => $report->id,
                    'training_session_id' => $session->id,
                ]);
            });

            return $report->fresh('timelineMoments');
        });
    }

    private function buildNarrativeSummary(
        int $overall,
        int $confidence,
        int $clarity,
        int $pace,
        int $filler,
        int $missed
    ): string {
        return "Overall performance landed at {$overall}/100. Confidence {$confidence}, clarity {$clarity}, and pace {$pace}. ".
            "Detected {$filler} filler words on average and {$missed} missed-question moments.";
    }

    private function buildKeyMistakes(int $filler, int $missed, int $pace): array
    {
        $mistakes = [];

        if ($filler > 4) {
            $mistakes[] = 'Filler words interrupted flow in critical answers.';
        }

        if ($missed > 1) {
            $mistakes[] = 'Some interviewer prompts were partially or fully missed.';
        }

        if ($pace < 55) {
            $mistakes[] = 'Speaking pace was too inconsistent, causing lower comprehension.';
        }

        return $mistakes ?: ['No critical mistakes were detected in this session.'];
    }

    private function buildSuggestions(int $confidence, int $clarity, int $pace, int $filler, int $missed): array
    {
        return [
            $confidence < 70 ? 'Lead responses with stronger intent statements.' : 'Keep your confident opening cadence.',
            $clarity < 70 ? 'Use a tighter STAR format for complex questions.' : 'Maintain clear structure with short transitions.',
            $pace < 60 ? 'Slow down during key points and pause before conclusions.' : 'Pace was healthy; preserve the rhythm.',
            $filler > 4 ? 'Replace filler words with intentional pauses.' : 'Filler control looked solid.',
            $missed > 0 ? 'Echo the question back before answering to avoid misses.' : 'Question coverage was comprehensive.',
        ];
    }

    private function buildTimelineMoments(Collection $events): Collection
    {
        return $events->take(6)->values()->map(function ($event, int $index): array {
            return [
                'timestamp_seconds' => max(30, ($index + 1) * 90),
                'type' => in_array($event->type, ['warning', 'missed_question'], true) ? 'warning' : 'highlight',
                'title' => $event->title,
                'description' => $event->message,
                'impact_score' => in_array($event->type, ['warning', 'missed_question'], true) ? -1 : 2,
                'meta' => $event->payload,
            ];
        });
    }
}
