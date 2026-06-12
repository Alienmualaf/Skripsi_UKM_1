<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['ukm_id', 'created_by', 'title', 'file_path', 'description', 'type', 'show_on_landing'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
