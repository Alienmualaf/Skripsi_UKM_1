<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'ukm_id',
        'created_by',
        'title',
        'type',
        'file_path',
    ];

    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
