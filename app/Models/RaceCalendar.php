<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaceCalendar extends Model
{
    protected $fillable = [
        'calendar_id', 
        'race_current',
        'user_id'
    ];

    /**
     * Cast para la fecha
     */
    protected $casts = [
        'race_current' => 'integer',
    ];

    // relations
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
