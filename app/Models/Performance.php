<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Performance extends Model
{
    protected $fillable = [
        'program_id',
        'title',
        'venue',
        'performance_date',
        'performance_time',
        'description',
        'dress_code',
        'status',
        'institution',
        'fee',
        'pic',
        'rundown',
        'show_on_landing',
    ];

    protected $casts = [
        'performance_date' => 'date',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function classroom(): HasOne
    {
        return $this->hasOne(Classroom::class);
    }

    public function getStartDateAttribute()
    {
        return $this->performance_date;
    }

    public function getCalculatedStatusAttribute()
    {
        return $this->status;
    }

    public function getDateAttribute()
    {
        return $this->performance_date ? ($this->performance_date instanceof \Carbon\Carbon ? $this->performance_date->format('Y-m-d') : $this->performance_date) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['performance_date'] = $value;
    }

    public function getLocationAttribute()
    {
        return $this->venue;
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['venue'] = $value;
    }

    public function getMembersAttribute()
    {
        return $this->classroom ? $this->classroom->members : collect();
    }
}
