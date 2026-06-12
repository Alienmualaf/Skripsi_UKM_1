<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLoan extends Model
{
    protected $fillable = [
        'inventory_id',
        'member_id',
        'borrower_name',
        'loan_letter',
        'loan_date',
        'return_date',
        'actual_return_date',
        'quantity',
        'condition_on_loan',
        'condition_on_return',
        'status',
        'used_for',
        'program_id',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
