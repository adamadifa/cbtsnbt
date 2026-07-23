<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['exam_attempt_id', 'user_id', 'action', 'details', 'ip_address'])]
class ActivityLog extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }
}
