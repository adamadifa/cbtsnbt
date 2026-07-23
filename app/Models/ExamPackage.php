<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExamPackage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'banner_image',
        'type',
        'price',
        'passing_score',
        'max_attempts',
        'shuffle_questions',
        'shuffle_options',
        'show_result',
        'show_explanation',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'passing_score' => 'decimal:2',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'show_result' => 'boolean',
        'show_explanation' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->title) . '-' . Str::random(5);
            }
        });
    }

    public function subtests(): HasMany
    {
        return $this->hasMany(ExamSubtest::class)->orderBy('order');
    }

    public function getTotalDurationAttribute(): int
    {
        return $this->subtests()->sum('duration_minutes');
    }

    public function getTotalQuestionsAttribute(): int
    {
        return $this->subtests()->sum('total_questions');
    }
}
