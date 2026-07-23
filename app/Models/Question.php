<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'subject_id',
        'type',
        'content',
        'explanation',
        'audio_file',
        'image',
        'difficulty',
        'points',
        'negative_points',
        'passage_group_id',
        'metadata',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'metadata' => 'json',
        'is_active' => 'boolean',
        'points' => 'decimal:2',
        'negative_points' => 'decimal:2',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    public function subtests(): BelongsToMany
    {
        return $this->belongsToMany(ExamSubtest::class, 'exam_subtest_questions')
                    ->withPivot('order')
                    ->withTimestamps();
    }

    public function passageGroup(): BelongsTo
    {
        return $this->belongsTo(PassageGroup::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
