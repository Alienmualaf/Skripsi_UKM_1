<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'name',
        'category',
        'condition',
        'quantity',
        'storage_location',
        'used_for',
        'program_id',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(InventoryLoan::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function getAvailableQtyAttribute(): int
    {
        return $this->quantity;
    }

    public function getTotalQtyAttribute(): int
    {
        $borrowed = $this->loans()->where('status', 'Dipinjam')->sum('quantity');
        return $this->quantity + $borrowed;
    }
}