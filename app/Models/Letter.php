<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Letter extends Model
{
    protected $fillable = [
        'letter_number',
        'date',
        'subject',
        'destination',
        'type',
        'file_path',
        'related_to',
        'program_id',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
