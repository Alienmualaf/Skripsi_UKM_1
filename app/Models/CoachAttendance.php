<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'session_id',
        'coach_id',
        'category',
        'name',
        'notes',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function session()
    {
        return $this->belongsTo(ActivitySession::class, 'session_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
