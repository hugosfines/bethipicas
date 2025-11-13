<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if(! function_exists('MyRole')) {
    function MyRole() {
        //$roles = auth()->user()->getRoleNames();
        $userId = Auth::id();
        $user = User::find($userId);
        $roles = $user->getRoleNames();

        return count($roles) ? $roles[0] : null;
    }
}

if(! function_exists('MyAccount')) {
    function MyAccount() {
        $account = Auth::user()->headquarters->first();
        
        return (object) [
            'company_id' => $account->company_id ?? null,
            'company_name' => $account->company->name ?? null,
            'company_country' => $account->company->country ?? null,
            'company_city' => $account->company->city ?? null,
            'headquarter_id' => $account->id ?? null,
            'headquarter_name' => $account->name ?? null,
            'headquarter_phone' => $account->phone ?? null,
            'headquarter_address' => $account->address ?? null,
            'headquarter_email' => $account->email ?? null,
            'headquarter_is_active' => $account->is_active ?? false,
        ];
    }
}

if(! function_exists('FactorDivisor')) {
    function FactorDivisor($trackId) {
        $track = \App\Models\Track::find($trackId);

        return $track ? $track->division_factor : 2.00;
    }
}

if(! function_exists('diffMinutes')) {
    function diffMinutes($start_time, $viewInt=false) {
        $now = \Carbon\Carbon::now('America/Bogota'); // Cambia esto por tu zona horaria
        $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start_time, 'America/Bogota');
        //$startTime = Carbon::parse($this->racing->start_time)->setTimezone('America/Bogota');

        if ($viewInt) {
            // Redondear siempre hacia arriba (ceil) para contar minutos completos
            $secondsDifference = $now->diffInSeconds($startTime, false);
            $minutesDifference = ceil($secondsDifference / 60);

            // Solo mostrar tiempo faltante (positivo)
            return $minutesDifference > 0 ? (int)$minutesDifference : 0;
        }

        // Calculamos la diferencia en minutos (false = permite valores negativos)
        $minutesDifference = $now->diffInMinutes($startTime, false);

        // Solo devolvemos el valor si es positivo (tiempo faltante)
        return $minutesDifference > 0 ? $minutesDifference : 0;
    }
}

/**crear y convertir las jugadas */
if(! function_exists('createGPS')) {
    function createGPS($ticket, $race) {
        $apuestas = [];
        foreach($ticket->betLines as $index => $apuesta) {
            if (in_array($apuesta->bet_type_id, [1,2,3])) {
                for ($i = 1; $i < 6; $i++) {
                    if ($apuesta->{"step_$i"}) {
                        $apuestas[] = [
                            'apuesta' => $apuesta->betType->name,
                            'nro' => $apuesta->{"step_$i"},
                            'carr' => $race,
                            'combinaciones' => ($index + 1),
                        ];
                    }
                }
            }
        }
        return $apuestas;
    }
}

if(! function_exists('createETS')) {
    function createETS($ticket, $race) {
        foreach($ticket->betLines as $index => $apuesta) {
            if ($apuesta->betType->category->type_follow == 'current') {
                $jugadas = [];
                for ($i = 1; $i < 6; $i++) {
                    if ($apuesta->{"step_$i"}) {
                        array_push($jugadas, $apuesta->{"step_$i"});
                    }
                }
                $apuestas[] = [
                    'apuesta' => $apuesta->betType->name,
                    'nro' => implode(', ', $jugadas),
                    'carr' => $race,
                    'combinaciones' => ($index + 1),
                ];
            }
        }
        return $apuestas;
    }
}

if(! function_exists('createETSStep')) {
    function createETSStep($ticket, $race) {
        $codesNumber = [
            "1"=>'er', 
            "2"=>'do', 
            "3"=>'er', 
            "4"=>'to'
        ];
        $plays = [];
        $apuestas = [];
        $step = 1;
        foreach($ticket->betLines as $index => $apuesta) {
            if ($apuesta->betType->category->type_follow == 'current') {
                for ($i = 1; $i < 6; $i++) {
                    if ($apuesta->{"step_$i"}) {
                        $plays[$i][$apuesta->{"step_$i"}] = $i;
                    }
                }
            }
        }
        
        if(!empty($plays)) {
            foreach ($plays as $keyStep => $jugadas) {
                $carrPicks = [];
                foreach ($jugadas as $keyNro => $jugada) {
                    $carrPicks[$keyStep][] = $keyNro;
                    $flattened = array_merge(...$carrPicks);
                    sort($flattened);
                    $stringJugadas = implode(', ', $flattened);
                }
                $apuestas[] = [
                    'apuesta' => $apuesta->betType->name,
                    'nro' => $stringJugadas,
                    'carr' => $step.$codesNumber[$step],
                    'combinaciones' => $step,
                ];
                $step++;
            }
        }

        return $apuestas;
    }
}

if(! function_exists('createPICK')) {
    function createPICK($ticket, $race) {
        $picks = [];
        $apuestas = [];
        $newCarr = $race;
        $combinations = 0;
        foreach($ticket->betLines as $index => $apuesta) {
            if ($apuesta->betType->category->type_follow == 'next') {
                for ($i = 1; $i < 6; $i++) {
                    if ($apuesta->{"step_$i"}) {
                        $picks[$i][$apuesta->{"step_$i"}] = $i;
                    }
                }
                $combinations++;
            }
        }
        
        if(!empty($picks)) {
            foreach ($picks as $keyStep => $jugadas) {
                $carrPicks = [];
                foreach ($jugadas as $keyNro => $jugada) {
                    $carrPicks[$keyStep][] = $keyNro;
                    $flattened = array_merge(...$carrPicks);
                    $stringJugadas = implode(', ', $flattened);
                }
                $apuestas[] = [
                    'apuesta' => $apuesta->betType->name,
                    'nro' => $stringJugadas,
                    'carr' => $newCarr,
                    'combinaciones' => $combinations,
                ];
                $newCarr++;
            }
        }

        return $apuestas;
    }
}

/**./crear y convertir las jugadas */