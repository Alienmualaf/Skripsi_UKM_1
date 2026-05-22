<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySession extends Model
{
    use HasFactory;

    protected $table = 'activity_sessions';

    protected $fillable = [
        'event_id',
        'title',
        'date',
        'description',
        'is_open',
    ];

    protected $casts = [
        'date' => 'date',
        'is_open' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    public function coachAttendances()
    {
        return $this->hasMany(CoachAttendance::class, 'session_id');
    }
}
