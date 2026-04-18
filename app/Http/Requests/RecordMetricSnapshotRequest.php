<?php

namespace App\Http\Requests;

use App\Models\TrainingSession;
use Illuminate\Foundation\Http\FormRequest;

class RecordMetricSnapshotRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var TrainingSession|null $session */
        $session = $this->route('trainingSession');

        return $this->user() !== null
            && $session !== null
            && $this->user()->can('view', $session);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'confidence_score' => ['required', 'integer', 'between:0,100'],
            'clarity_score' => ['required', 'integer', 'between:0,100'],
            'pace_score' => ['required', 'integer', 'between:0,100'],
            'overall_score' => ['required', 'integer', 'between:0,100'],
            'filler_word_count' => ['required', 'integer', 'between:0,999'],
            'missed_question_count' => ['required', 'integer', 'between:0,99'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
