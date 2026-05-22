<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'session_id',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
