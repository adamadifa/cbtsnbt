<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExamSession extends Model
{
    protected $fillable = [
        'exam_package_id',
        'title',
        'token',
        'start_time',
        'end_time',
        'is_active',
        'max_participants',
        'status',
        'created_by'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($session) {
            if (empty($session->token)) {
                $session->token = strtoupper(Str::random(6));
            }
            if (empty($session->created_by)) {
                $session->created_by = auth()->id();
            }
        });
    }

    public function examPackage(): BelongsTo
    {
        return $this->belongsTo(ExamPackage::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    /**
     * Get computed status based on current time
     */
    public function getComputedStatusAttribute()
    {
        $now = now();

        if ($this->status === 'cancelled') return 'cancelled';
        if ($now->lt($this->start_time)) return 'scheduled';
        if ($now->gt($this->end_time)) return 'completed';
        
        return 'active';
    }
}
