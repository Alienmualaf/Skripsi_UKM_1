<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ukm_id',
        'event_id',
        'created_by',
        'title',
        'content',
    ];

    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
