<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $guarded = ['id'];

    /**
     * Cast para la fecha
     */
    protected $casts = [
        'date_at' => 'date',
    ];

    // relations
    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function raceCalendar()
    {
        return $this->hasOne(RaceCalendar::class);
    }

    public function racings()
    {
        return $this->hasMany(Racing::class);
    }
}
