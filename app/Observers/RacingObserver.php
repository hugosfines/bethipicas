<?php

namespace App\Observers;

use App\Models\Racing;
use App\Models\RacingHorse;

class RacingObserver
{
    /**
     * Handle the Negotiation "created" event.
     */
    public function created(Racing $racing): void
    {
        for ($i=1; $i <= $racing->total_horses ; $i++) { 
            RacingHorse::create([
                'racing_id' => $racing->id,
                'nro' => $i,
                'status' => 'run',
            ]);
        }
    }
}
