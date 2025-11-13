<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = ['id'];

    // relations
    public function headquarters()
    {
        return $this->hasMany(Headquarter::class);
    }
}
