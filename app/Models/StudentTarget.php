<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'campus_prodi_id', 'order'])]
class StudentTarget extends Model
{
    use HasFactory;

    protected $table = 'student_targets';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campusProdi()
    {
        return $this->belongsTo(CampusProdi::class, 'campus_prodi_id');
    }
}
