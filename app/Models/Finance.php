<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'ukm_id',
        'created_by',
        'title',
        'type',
        'amount',
        'description',
        'transaction_date',
        'event_id',
        'validation_status',
        'validation_notes',
    ];

    /**
     * Casting
     */
    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // transaksi milik 1 UKM
    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

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

    // cek pemasukan
    public function isIncome()
    {
        return $this->type === 'income';
    }

    // cek pengeluaran
    public function isExpense()
    {
        return $this->type === 'expense';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (BIAR QUERY MUDAH)
    |--------------------------------------------------------------------------
    */

    // hanya pemasukan
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    // hanya pengeluaran
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}