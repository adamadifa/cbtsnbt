<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExamSubtest extends Model
{
    protected $fillable = [
        'exam_package_id',
        'subject_id',
        'title',
        'duration_minutes',
        'total_questions',
        'order',
        'instructions'
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(ExamPackage::class, 'exam_package_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_subtest_questions')
                    ->withPivot('order')
                    ->withTimestamps();
    }
}
