<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'component',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
