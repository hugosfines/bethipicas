<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'racing_id',
        'bet_type_id',
        'number',
        'dividendo',
        'user_id',
        'order'
    ];

    protected $casts = [
        'dividendo' => 'decimal:2',
    ];

    public function racing()
    {
        return $this->belongsTo(Racing::class);
    }

    public function betType()
    {
        return $this->belongsTo(RacingBet::class, 'bet_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
