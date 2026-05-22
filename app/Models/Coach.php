<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'ukm_id',
        'name',
        'category',
        'photo',
        'description',
        'skills',
    ];

    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_coach');
    }

    public function attendances()
    {
        return $this->hasMany(CoachAttendance::class);
    }
}
