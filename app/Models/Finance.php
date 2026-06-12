<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Finance extends Model
{
    protected $fillable = [
        'finance_category_id',
        'type',
        'amount',
        'title',
        'description',
        'transaction_date',
        'receipt_file',
        'used_for',
        'program_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}