<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamViolation extends Model
{
    protected $fillable = [
        'exam_result_id',
        'type',
        'details'
    ];

    public function examResult(): BelongsTo
    {
        return $this->belongsTo(ExamResult::class);
    }
}
