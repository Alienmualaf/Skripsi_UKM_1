<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'specialty',
        'phone',
        'email',
        'photo',
        'salary',
        'status',
    ];
}
