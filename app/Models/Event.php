<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'ukm_id',
        'created_by',
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'status',
        'validation_status',
        'validation_notes',
        'is_archived',
    ];

    /**
     * Casting
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // event milik 1 UKM
    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    // event dibuat oleh user (admin UKM)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with Participants (Memberships)
     */
    public function participants()
    {
        return $this->belongsToMany(Membership::class, 'event_participants', 'event_id', 'membership_id')->withTimestamps();
    }

    /**
     * Relationship with Materials
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Relationship with Announcements
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Relationship with Coaches
     */
    public function coaches()
    {
        return $this->belongsToMany(Coach::class, 'event_coach');
    }

    /**
     * Relationship with Activity Sessions (Pertemuan)
     */
    public function sessions()
    {
        return $this->hasMany(ActivitySession::class);
    }

    /**
     * Relationship with Coach Attendances
     */
    public function coachAttendances()
    {
        return $this->hasMany(CoachAttendance::class);
    }

    /**
     * Relationship with Member Attendances
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relationship with Finances
     */
    public function finances()
    {
        return $this->hasMany(Finance::class);
    }

    /**
     * Relationship with Inventories
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    // cek apakah event sudah selesai
    public function isFinished()
    {
        return $this->status === 'completed';
    }

    // cek apakah event sedang berlangsung
    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }

    // cek apakah event akan datang
    public function isUpcoming()
    {
        return $this->status === 'upcoming';
    }

    public function getStatusAttribute($value)
    {
        $now = now();
        $start = $this->start_date;
        $end = $this->end_date ?? $this->start_date->copy()->endOfDay();

        if ($now->gt($end)) {
            return 'completed';
        }
        if ($now->lt($start)) {
            return 'upcoming';
        }
        return 'ongoing';
    }

    public function getCalculatedStatusAttribute()
    {
        return $this->status;
    }
}