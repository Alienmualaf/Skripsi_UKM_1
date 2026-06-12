<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomSchedule extends Model
{
    protected $fillable = [
        'classroom_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'location',
        'notes',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
