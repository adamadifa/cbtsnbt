<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['campus_name', 'prodi_name', 'jenjang'])]
class CampusProdi extends Model
{
    use HasFactory;

    protected $table = 'campus_prodis';
}
