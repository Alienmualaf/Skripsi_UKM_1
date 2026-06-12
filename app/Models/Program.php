<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = [
        'name',
        'division',
        'description',
        'start_date',
        'end_date',
        'target_date',
        'pic',
        'budget',
        'progress',
        'status',
        'activity_type', // Event, Competition, Performance
        'event_category', // Internal, External
        'venue',
        'show_on_landing',
    ];

    public function performance(): HasOne
    {
        return $this->hasOne(Performance::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(ProgramReport::class);
    }

    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function inventoryLoans(): HasMany
    {
        return $this->hasMany(InventoryLoan::class);
    }

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    public function isPerformance(): bool
    {
        return $this->activity_type === 'Performance';
    }

    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now()->toDateString());
    }
}
