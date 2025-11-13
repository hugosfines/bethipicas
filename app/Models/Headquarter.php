<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Headquarter extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // relations
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function aditional_payment()
    {
        return $this->hasOne(AditionalPayment::class);
    }

    // Relacion muchos a muchos
    public function users(){
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }
}
