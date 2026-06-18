<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'registration_id',
        'recipient_email',
        'recipient_name',
        'subject',
        'content',
        'status',
        'error_message',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
