<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'role_id',
        'status',
        'division', // Divisi pengurus: Sekretaris, Bendahara, Divisi Latihan, Divisi Humas, Divisi Perlengkapan
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

    /**
     * Relation to Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation to Member.
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    // Role Helper Methods
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->name === 'administrator';
    }

    public function isAdminUkm(): bool
    {
        return $this->role && $this->role->name === 'admin_ukm';
    }

    public function isPengurus(): bool
    {
        return $this->role && $this->role->name === 'pengurus';
    }

    public function isAnggota(): bool
    {
        return $this->role && $this->role->name === 'anggota';
    }

    public function roleInUKM($ukmId = null)
    {
        if ($this->isSuperAdmin() || $this->isAdminUkm() || $this->isPengurus()) {
            return 'admin';
        }
        return 'member';
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }
}