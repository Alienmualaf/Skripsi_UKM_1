<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'role',
        'activity',
        'module',
        'ip_address',
        'browser',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
