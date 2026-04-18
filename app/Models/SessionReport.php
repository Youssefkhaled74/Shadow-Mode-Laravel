<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class SessionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'generated_by',
        'overall_score',
        'summary',
        'best_response',
        'weakest_response',
        'key_mistakes',
        'improvement_suggestions',
        'score_breakdown',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'key_mistakes' => 'array',
            'improvement_suggestions' => 'array',
            'score_breakdown' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function timelineMoments(): HasMany
    {
        return $this->hasMany(ReportTimelineMoment::class);
    }
}
