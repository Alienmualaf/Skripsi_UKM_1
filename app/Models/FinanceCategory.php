<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceCategory extends Model
{
    protected $fillable = ['name', 'type'];

    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }
}
