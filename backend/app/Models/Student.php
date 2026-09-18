<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'name',
    ];

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }
}
