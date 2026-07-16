<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('exclude_non_anggota', function ($builder) {
            $builder->where(function ($query) {
                $query->whereHas('user.role', function ($q) {
                    $q->whereNotIn('name', ['administrator', 'admin_ukm', 'pengurus']);
                })->orWhereDoesntHave('user');
            });
        });
    }

    protected $fillable = [
        'user_id',
        'npm',
        'name',
        'gender',
        'faculty',
        'major',
        'class_year',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'email',
        'photo',
        'choir_experience',
        'status',
        'voice_classification_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voiceClassification(): BelongsTo
    {
        return $this->belongsTo(VoiceClassification::class);
    }

    public function jobs()
    {
        return Performance::whereHas('classroom.members', function ($query) {
                $query->where('members.id', $this->id);
            });
    }

    public function getJobsAttribute()
    {
        return $this->jobs()->get();
    }

    public function loans(): HasMany
    {
        return $this->hasMany(InventoryLoan::class);
    }

    public function attendanceDetails(): HasMany
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_members')
            ->withPivot('role');
    }

    public function getNimAttribute()
    {
        return $this->attributes['npm'] ?? null;
    }

    public function setNimAttribute($value)
    {
        $this->attributes['npm'] = $value;
    }
}
