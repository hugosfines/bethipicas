<?php

namespace App\Livewire\Admin\Manage;

use Livewire\Component;

use App\Models\Calendar;
use App\Models\RacingHorse;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class ManageCalendars extends Component
{
    public $dateAt;
    public $calendarId = '';
    public $calendars = [];

    public $selectedCalendar = null;
    public $racings;
    public $raceConfig = [];
    public $currentRace = 1;

    public function mount()
    {
        $this->dateAt = now()->format('Y-m-d');

        $this->loadCalendars();
    }

    public function loadCalendars()
    {
        $this->calendars = Calendar::orderBy('id', 'asc')
            ->with('track')
            ->where('date_at', $this->dateAt)
            ->where('is_active', true)
            ->get();
    }

    public function updatedDateAt()
    {
        $this->loadCalendars();
        $this->calendarId = '';
        $this->selectedCalendar = null;
        $this->racings = [];
    }

    public function updatedCalendarId($value)
    {
        if ($value) {
            $this->selectedCalendar = Calendar::with(['track', 'racings.racing_horses', 'raceCalendars'])
                ->find($value);
            
            $this->racings = $this->selectedCalendar->racings->sortBy('race');
            $this->currentRace = $this->selectedCalendar->raceCalendars->first()->race_current ?? 1;
            $this->initializeRaceConfig();
        } else {
            $this->selectedCalendar = null;
            $this->racings = [];
            $this->raceConfig = [];
        }
    }

    protected function initializeRaceConfig()
    {
        $this->raceConfig = [];
        
        foreach ($this->racings as $racing) {
            $this->raceConfig[$racing->race] = [
                'horses' => $racing->total_horses,
                'retired_horses' => $racing->racing_horses
                    ->where('status', 'scratch')
                    ->pluck('nro')
                    ->toArray(),
                'status' => $racing->status
            ];
        }
    }

    public function toggleRetiredHorse($raceNumber, $horseNumber)
    {
        if (!isset($this->raceConfig[$raceNumber]['retired_horses'])) {
            $this->raceConfig[$raceNumber]['retired_horses'] = [];
        }

        $retiredHorses = &$this->raceConfig[$raceNumber]['retired_horses'];
        
        if (in_array($horseNumber, $retiredHorses)) {
            $retiredHorses = array_filter($retiredHorses, function($num) use ($horseNumber) {
                return $num != $horseNumber;
            });
        } else {
            $retiredHorses[] = $horseNumber;
            sort($retiredHorses);
        }

        $this->updateRacingHorses($raceNumber);
    }

    public function updateRacingHorses($raceNumber)
    {
        $racing = $this->racings->where('race', $raceNumber)->first();
        if (!$racing) return;

        try {
            DB::transaction(function () use ($racing, $raceNumber) {
                // Actualizar estado de los caballos
                RacingHorse::where('racing_id', $racing->id)->delete();
                
                $retiredHorses = $this->raceConfig[$raceNumber]['retired_horses'] ?? [];
                $totalHorses = $this->raceConfig[$raceNumber]['horses'];
                
                for ($horseNumber = 1; $horseNumber <= $totalHorses; $horseNumber++) {
                    $status = in_array($horseNumber, $retiredHorses) ? 'scratch' : 'run';
                    
                    RacingHorse::create([
                        'racing_id' => $racing->id,
                        'nro' => $horseNumber,
                        'status' => $status,
                    ]);
                }

                // Actualizar estadísticas en la carrera
                $activeHorses = $totalHorses - count($retiredHorses);
                $racing->update([
                    'active_horses' => max(1, $activeHorses),
                    'retired_horses' => count($retiredHorses),
                ]);
            });

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Caballos actualizados para la carrera #' . $raceNumber
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ]);
        }
    }

    public function closeRace($raceNumber)
    {
        $racing = $this->racings->where('race', $raceNumber)->first();
        
        if (!$racing) return;
        
        try {
            $racing->update(['status' => 'close']);
            
            $this->raceConfig[$raceNumber]['status'] = 'close';
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Carrera #' . $raceNumber . ' cerrada'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al cerrar carrera: ' . $e->getMessage()
            ]);
        }
    }

    public function openRace($raceNumber)
    {
        $racing = $this->racings->where('race', $raceNumber)->first();
        if (!$racing) return;

        try {
            $racing->update(['status' => 'open']);
            
            $this->raceConfig[$raceNumber]['status'] = 'open';
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Carrera #' . $raceNumber . ' reabierta'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al reabrir carrera: ' . $e->getMessage()
            ]);
        }
    }

    public function nextRace()
    {
        if (!$this->selectedCalendar) return;

        try {
            $raceCalendar = $this->selectedCalendar->raceCalendars->first();
            $nextRace = $this->currentRace + 1;
            
            // Verificar que existe la siguiente carrera
            $nextRacing = $this->racings->where('race', $nextRace)->first();
            if (!$nextRacing) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No existe la carrera #' . $nextRace
                ]);
                return;
            }

            $raceCalendar->update(['race_current' => $nextRace]);
            $this->currentRace = $nextRace;
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Avanzando a carrera #' . $nextRace
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al avanzar carrera: ' . $e->getMessage()
            ]);
        }
    }

    public function previousRace()
    {
        if (!$this->selectedCalendar || $this->currentRace <= 1) return;

        try {
            $raceCalendar = $this->selectedCalendar->raceCalendars->first();
            $previousRace = $this->currentRace - 1;
            
            $raceCalendar->update(['race_current' => $previousRace]);
            $this->currentRace = $previousRace;
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Retrocediendo a carrera #' . $previousRace
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al retroceder carrera: ' . $e->getMessage()
            ]);
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.manage.manage-calendars');
    }
}
