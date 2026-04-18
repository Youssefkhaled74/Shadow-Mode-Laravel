<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class MetricSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'user_id',
        'confidence_score',
        'clarity_score',
        'pace_score',
        'overall_score',
        'filler_word_count',
        'missed_question_count',
        'meta',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'recorded_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
