<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    protected $fillable = [
        'exam_result_id',
        'question_id',
        'option_id',
        'option_ids',
        'matching_answers',
        'essay_answer',
        'is_correct',
        'points'
    ];

    protected $casts = [
        'option_ids' => 'array',
        'matching_answers' => 'array',
        'is_correct' => 'boolean',
    ];

    public function examResult(): BelongsTo
    {
        return $this->belongsTo(ExamResult::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
