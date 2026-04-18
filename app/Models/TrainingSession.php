<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'room_code',
        'title',
        'scenario_type',
        'description',
        'host_id',
        'coach_id',
        'state',
        'scheduled_for',
        'started_at',
        'ended_at',
        'settings',
        'average_score',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'settings' => 'array',
            'average_score' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $session): void {
            $session->uuid ??= (string) Str::uuid();
            $session->room_code ??= strtoupper(Str::random(8));
        });
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(SessionParticipant::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(SessionEvent::class);
    }

    public function hints(): HasMany
    {
        return $this->hasMany(CoachingHint::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(MetricSnapshot::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(SessionInvite::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(SessionReport::class);
    }

    protected function isLive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->state === 'live',
        );
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
