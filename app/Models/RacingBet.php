<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacingBet extends Model
{
    protected $guarded = ['id'];

    // relations
    public function racing()
    {
        return $this->belongsTo(Racing::class);
    }

    public function bet_type()
    {
        return $this->belongsTo(BetType::class);
    }
}
