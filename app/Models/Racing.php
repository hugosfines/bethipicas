<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Observers\RacingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([RacingObserver::class])]
class Racing extends Model
{
    protected $guarded = ['id'];

    /**
     * Cast para la fecha
     */
    protected $casts = [
        'start_time' => 'datetime',
    ];

    // relations
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function racing_horses()
    {
        return $this->hasMany(RacingHorse::class);
    }

    public function racing_bets()
    {
        return $this->hasMany(RacingBet::class);
    }

    public function bet()
    {
        return $this->hasOne(Bet::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
