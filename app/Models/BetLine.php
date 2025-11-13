<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetLine extends Model
{
    protected $guarded = ['id'];

    // relations
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function bet()
    {
        return $this->belongsTo(Bet::class);
    }

    public function betType()
    {
        return $this->belongsTo(BetType::class);
    }
}
