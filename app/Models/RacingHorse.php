<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacingHorse extends Model
{
    protected $guarded = ['id'];

    // relations
    public function racing()
    {
        return $this->belongsTo(Racing::class);
    }
}
