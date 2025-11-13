<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AditionalPayment extends Model
{
    protected $guarded = ['id'];

    // relations
    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }
}
