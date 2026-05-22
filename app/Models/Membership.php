<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'user_id',
        'ukm_id',
        'ukm_classification_id',
        'role_in_ukm',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // membership milik 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    public function classification()
    {
        return $this->belongsTo(UKMClassification::class, 'ukm_classification_id');
    }

    public function participatingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_participants', 'membership_id', 'event_id')->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    // cek apakah sudah di-approve
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // cek masih pending
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // cek role admin UKM
    public function isAdmin()
    {
        return $this->role_in_ukm === 'admin' && $this->isApproved();
    }

    // cek role anggota biasa
    public function isMember()
    {
        return $this->role_in_ukm === 'member' && $this->isApproved();
    }
}