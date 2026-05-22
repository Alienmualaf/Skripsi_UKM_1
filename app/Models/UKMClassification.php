<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UKMClassification extends Model
{
    use HasFactory;

    protected $table = 'ukm_classifications';

    protected $fillable = [
        'ukm_id',
        'name',
    ];

    public function ukm()
    {
        return $this->belongsTo(UKM::class, 'ukm_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'ukm_classification_id');
    }
}
