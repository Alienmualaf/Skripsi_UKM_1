<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // super_admin, ukm_admin, member
        'npm',
        'faculty',
        'major',
        'phone',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isSuperAdmin() { return $this->role === 'super_admin'; }
    public function isUKMAdmin() { return $this->role === 'ukm_admin'; }
    public function isMember() { return $this->role === 'user' || $this->role === 'member'; }

    // Relationship: 1 User bisa jadi member di banyak UKM
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    // Ambil UKM yang dikelola (untuk role ukm_admin)
    public function managedUKM()
    {
        $membership = $this->memberships()
            ->where('role_in_ukm', 'admin')
            ->first();

        return $membership ? $membership->ukm : null;
    }

    // Dapatkan peran user dalam UKM tertentu
    public function roleInUKM($ukmId)
    {
        $membership = $this->memberships()
            ->where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->first();
        return $membership ? $membership->role_in_ukm : null;
    }

    // Ambil semua UKM tempat dia terdaftar sebagai member biasa
    public function joinedUKMs()
    {
        return UKM::whereIn('id', $this->memberships()
            ->where('status', 'approved')
            ->pluck('ukm_id')
        )->get();
    }

    // cek apakah user terdaftar di UKM tertentu
    public function isMemberOf($ukmId)
    {
        return $this->memberships()
            ->where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->exists();
    }

    // ambil membership aktif (kalau cuma 1 UKM)
    public function activeMembership()
    {
        return $this->memberships()
            ->where('status', 'approved')
            ->first();
    }

    // cek boleh masuk room
    public function canAccessUKM($ukmId)
    {
        return $this->memberships()
            ->where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->exists();
    }

    public function isParticipant($eventId)
    {
        $membershipIds = $this->memberships()
            ->where('status', 'approved')
            ->pluck('id');

        if ($membershipIds->isEmpty()) return false;

        return \DB::table('event_participants')
            ->where('event_id', $eventId)
            ->whereIn('membership_id', $membershipIds)
            ->exists();
    }
}