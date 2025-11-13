<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetType extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'position_winner',
        'follow',
        'positions',
        'box',
        'is_active',
    ];

    protected $casts = [
        'position_winner' => 'integer',
        'follow' => 'integer',
        'positions' => 'array',
        'is_active' => 'boolean',
    ];

    // relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function racing_bets()
    {
        return $this->hasMany(RacingBet::class);
    }
}
