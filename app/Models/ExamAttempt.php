<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'exam_session_id', 'started_at', 'finished_at', 'total_score'])]
class ExamAttempt extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examPackage()
    {
        return $this->hasOneThrough(ExamPackage::class, ExamSession::class, 'id', 'id', 'exam_session_id', 'exam_package_id');
    }
}
