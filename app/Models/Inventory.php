<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'ukm_id',
        'created_by',
        'name',
        'quantity',
        'condition',
        'location',
        'description',
        'event_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // inventory milik 1 UKM
    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    // siapa yang input (admin UKM)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    // cek kondisi barang
    public function isGood()
    {
        return $this->condition === 'good';
    }

    public function isDamaged()
    {
        return $this->condition === 'damaged';
    }

    public function isLost()
    {
        return $this->condition === 'lost';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeGood($query)
    {
        return $query->where('condition', 'good');
    }

    public function scopeDamaged($query)
    {
        return $query->where('condition', 'damaged');
    }

    public function scopeLost($query)
    {
        return $query->where('condition', 'lost');
    }
}