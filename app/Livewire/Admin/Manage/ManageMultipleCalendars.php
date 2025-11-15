<?php

namespace App\Livewire\Admin\Manage;

use Livewire\Component;

use App\Models\Calendar;
use App\Models\RacingHorse;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class ManageMultipleCalendars extends Component
{
    public $dateAt;
    public $selectedCalendarIds = [];
    public $calendars;

    public $selectedCalendars = [];
    public $raceConfig = [];
    public $currentRaces = []; // Para llevar el control por cada jornada

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
        $this->selectedCalendarIds = [];
        $this->selectedCalendars = [];
        $this->currentRaces = [];
    }

    public function updatedSelectedCalendarIds($value)
    {
        $this->selectedCalendars = [];
        $this->currentRaces = [];
        $this->raceConfig = [];

        foreach ($this->selectedCalendarIds as $calendarId) {
            $calendar = Calendar::with(['track', 'racings.racing_horses', 'raceCalendars'])
                ->find($calendarId);
            
            if ($calendar) {
                $this->selectedCalendars[$calendarId] = $calendar;
                $this->currentRaces[$calendarId] = $calendar->raceCalendars->first()->race_current ?? 1;
                $this->initializeRaceConfigForCalendar($calendarId);
            }
        }
    }

    protected function initializeRaceConfigForCalendar($calendarId)
    {
        $calendar = $this->selectedCalendars[$calendarId];
        $this->raceConfig[$calendarId] = [];
        
        foreach ($calendar->racings->sortBy('race') as $racing) {
            $this->raceConfig[$calendarId][$racing->race] = [
                'horses' => $racing->total_horses,
                'retired_horses' => $racing->racing_horses
                    ->where('status', 'scratch')
                    ->pluck('nro')
                    ->toArray(),
                'status' => $racing->status
            ];
        }
    }

    public function toggleRetiredHorse($calendarId, $raceNumber, $horseNumber)
    {
        if (!isset($this->raceConfig[$calendarId][$raceNumber]['retired_horses'])) {
            $this->raceConfig[$calendarId][$raceNumber]['retired_horses'] = [];
        }

        $retiredHorses = &$this->raceConfig[$calendarId][$raceNumber]['retired_horses'];
        
        if (in_array($horseNumber, $retiredHorses)) {
            $retiredHorses = array_filter($retiredHorses, function($num) use ($horseNumber) {
                return $num != $horseNumber;
            });
        } else {
            $retiredHorses[] = $horseNumber;
            sort($retiredHorses);
        }

        $this->updateRacingHorses($calendarId, $raceNumber);
    }

    public function updateRacingHorses($calendarId, $raceNumber)
    {
        $calendar = $this->selectedCalendars[$calendarId] ?? null;
        if (!$calendar) return;

        $racing = $calendar->racings->where('race', $raceNumber)->first();
        if (!$racing) return;

        try {
            DB::transaction(function () use ($racing, $calendarId, $raceNumber) {
                RacingHorse::where('racing_id', $racing->id)->delete();
                
                $retiredHorses = $this->raceConfig[$calendarId][$raceNumber]['retired_horses'] ?? [];
                $totalHorses = $this->raceConfig[$calendarId][$raceNumber]['horses'];
                
                for ($horseNumber = 1; $horseNumber <= $totalHorses; $horseNumber++) {
                    $status = in_array($horseNumber, $retiredHorses) ? 'scratch' : 'run';
                    
                    RacingHorse::create([
                        'racing_id' => $racing->id,
                        'nro' => $horseNumber,
                        'status' => $status,
                    ]);
                }

                $activeHorses = $totalHorses - count($retiredHorses);
                $racing->update([
                    'active_horses' => max(1, $activeHorses),
                    'retired_horses' => count($retiredHorses),
                ]);
            });

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Caballos actualizados para ' . $calendar->track->name . ' - Carrera #' . $raceNumber
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ]);
        }
    }

    public function closeRace($calendarId, $raceNumber)
    {
        $calendar = $this->selectedCalendars[$calendarId] ?? null;
        if (!$calendar) return;

        $racing = $calendar->racings->where('race', $raceNumber)->first();
        if (!$racing) return;
        
        try {
            $racing->update(['status' => 'close']);
            $this->raceConfig[$calendarId][$raceNumber]['status'] = 'close';
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $calendar->track->name . ' - Carrera #' . $raceNumber . ' cerrada'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al cerrar carrera: ' . $e->getMessage()
            ]);
        }
    }

    public function openRace($calendarId, $raceNumber)
    {
        $calendar = $this->selectedCalendars[$calendarId] ?? null;
        if (!$calendar) return;

        $racing = $calendar->racings->where('race', $raceNumber)->first();
        if (!$racing) return;

        try {
            $racing->update(['status' => 'open']);
            $this->raceConfig[$calendarId][$raceNumber]['status'] = 'open';
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $calendar->track->name . ' - Carrera #' . $raceNumber . ' reabierta'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al reabrir carrera: ' . $e->getMessage()
            ]);
        }
    }

    public function nextRace($calendarId)
    {
        $calendar = $this->selectedCalendars[$calendarId] ?? null;
        if (!$calendar) return;

        try {
            $raceCalendar = $calendar->raceCalendars->first();
            $nextRace = $this->currentRaces[$calendarId] + 1;
            
            $nextRacing = $calendar->racings->where('race', $nextRace)->first();
            if (!$nextRacing) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => $calendar->track->name . ' - No existe la carrera #' . $nextRace
                ]);
                return;
            }

            $raceCalendar->update(['race_current' => $nextRace]);
            $this->currentRaces[$calendarId] = $nextRace;
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $calendar->track->name . ' - Avanzando a carrera #' . $nextRace
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al avanzar carrera: ' . $e->getMessage()
            ]);
        }
    }

    public function previousRace($calendarId)
    {
        $calendar = $this->selectedCalendars[$calendarId] ?? null;
        if (!$calendar || $this->currentRaces[$calendarId] <= 1) return;

        try {
            $raceCalendar = $calendar->raceCalendars->first();
            $previousRace = $this->currentRaces[$calendarId] - 1;
            
            $raceCalendar->update(['race_current' => $previousRace]);
            $this->currentRaces[$calendarId] = $previousRace;
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $calendar->track->name . ' - Retrocediendo a carrera #' . $previousRace
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al retroceder carrera: ' . $e->getMessage()
            ]);
        }
    }

    // Métodos para selección masiva
    public function selectAllCalendars()
    {
        $this->selectedCalendarIds = $this->calendars->pluck('id')->toArray();
        $this->updatedSelectedCalendarIds($this->selectedCalendarIds);
    }

    public function unselectAllCalendars()
    {
        $this->selectedCalendarIds = [];
        $this->selectedCalendars = [];
        $this->currentRaces = [];
        $this->raceConfig = [];
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.manage.manage-multiple-calendars');
    }
}
