<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class LogoutOtherSessions
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user; // Usuario autenticado
        $currentSessionId = session()->getId();

        // Asociar el user_id con la sesión actual
        DB::table('sessions')
            ->where('id', $currentSessionId)
            ->update(['user_id' => $user->id]);

        // Cerrar otras sesiones activas del usuario
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();
    }
}
