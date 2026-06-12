<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomSongTarget extends Model
{
    protected $fillable = [
        'classroom_id',
        'song_title',
        'composer',
        'voice_part',
        'status',
        'notes',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
