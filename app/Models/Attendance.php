<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    protected $fillable = [
        'title',
        'type',
        'date',
        'classroom_id',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
