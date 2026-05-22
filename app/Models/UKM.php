<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UKM extends Model
{
    use HasFactory;

    /**
     * 🔥 FIX PENTING (BIAR GA JADI u_k_m_s)
     */
    protected $table = 'ukms';

    /**
     * Mass assignable
     */
    protected $fillable = [
        'name',
        'description',
        'logo',
        'phone',
        'vision',
        'mission',
        'structure_image',
        'is_recruitment_open',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // 1 UKM punya banyak membership
    public function coaches()
    {
        return $this->hasMany(Coach::class, 'ukm_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'ukm_id');
    }

    // UKM punya banyak user (anggota & admin)
    public function users()
    {
        return $this->belongsToMany(User::class, 'memberships', 'ukm_id', 'user_id')
            ->withPivot('role_in_ukm', 'status')
            ->withTimestamps();
    }

    // ambil admin UKM (1 UKM = 1 admin)
    public function admin()
    {
        return $this->users()
            ->wherePivot('role_in_ukm', 'admin')
            ->wherePivot('status', 'approved')
            ->first();
    }

    // ambil semua anggota
    public function members()
    {
        return $this->users()
            ->wherePivot('role_in_ukm', 'member')
            ->wherePivot('status', 'approved');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP FITUR
    |--------------------------------------------------------------------------
    */

    public function events()
    {
        return $this->hasMany(Event::class, 'ukm_id');
    }

    public function finances()
    {
        return $this->hasMany(Finance::class, 'ukm_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'ukm_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'ukm_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'ukm_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'ukm_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'ukm_id');
    }

    public function classifications()
    {
        return $this->hasMany(UKMClassification::class, 'ukm_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    // cek apakah user admin di UKM ini
    public function isAdmin($userId)
    {
        return $this->memberships()
            ->where('user_id', $userId)
            ->where('role_in_ukm', 'admin')
            ->where('status', 'approved')
            ->exists();
    }

    // cek apakah user anggota UKM ini
    public function isMember($userId)
    {
        return $this->memberships()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->exists();
    }
}