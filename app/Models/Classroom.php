<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'performance_id',
        'trainer_id',
        'name',
        'description',
        'status',
    ];

    public function performance(): BelongsTo
    {
        return $this->belongsTo(Performance::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'classroom_members')
            ->withPivot('role');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ClassroomSchedule::class);
    }

    public function songTargets(): HasMany
    {
        return $this->hasMany(ClassroomSongTarget::class);
    }

    public static function autoArchivePastClassrooms(): void
    {
        $pastClassrooms = self::where('status', 'Aktif')
            ->whereHas('performance', function ($query) {
                $query->whereNotNull('performance_date')
                      ->where('performance_date', '<', date('Y-m-d'));
            })
            ->get();

        foreach ($pastClassrooms as $classroom) {
            $classroom->update(['status' => 'Terarsip']);
        }
    }
}
