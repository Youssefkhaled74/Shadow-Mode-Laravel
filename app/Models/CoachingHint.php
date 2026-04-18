<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CoachingHint extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'author_id',
        'target_user_id',
        'category',
        'severity',
        'is_system',
        'content',
        'meta',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'meta' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
