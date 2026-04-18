<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ReportTimelineMoment extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_report_id',
        'training_session_id',
        'timestamp_seconds',
        'type',
        'title',
        'description',
        'impact_score',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(SessionReport::class, 'session_report_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }
}
