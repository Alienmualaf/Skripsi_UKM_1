<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'name',
        'npm',
        'gender',
        'faculty',
        'major',
        'class_year',
        'phone',
        'email',
        'choir_experience',
        'photo',
        'status',
        'password',
    ];
}
