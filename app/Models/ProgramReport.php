<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramReport extends Model
{
    protected $fillable = [
        'program_id',
        'created_by',
        'title',
        'executive_summary',
        'activities_description',
        'budget_realization',
        'obstacles',
        'recommendations',
        'realized_budget',
        'status',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
