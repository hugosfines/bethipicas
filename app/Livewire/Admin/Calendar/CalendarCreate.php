<?php

namespace App\Livewire\Admin\Calendar;

use Livewire\Component;

use App\Models\Calendar;
use App\Models\Track;
use App\Models\RaceCalendar;
use App\Models\Racing;
use App\Models\RacingBet;
use App\Models\RacingHorse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class CalendarCreate extends Component
{
    use WithPagination;

    public $dateAt;
    public $showModal = false;
    public $showConfigModal = false;
    public $modalTitle = '';
    public $editingId = null;
    
    // Configuración de jornadas
    public $selectedTracks = [];
    public $trackConfig = [];
    public $raceConfig = []; // Nueva configuración por carrera
    public $defaultRaces = 10;
    public $defaultHorses = 10;

    public $form = [
        'track_id' => '',
        'date_at' => '',
        'total_races' => 13,
        'is_active' => true
    ];

    // edit calendar modal
    public $showEditModal = false;
    public $editingCalendar = null;
    public $editTrackConfig = [];
    public $editRaceConfig = [];
    public $editDefaultRaces = 13;
    public $editDefaultHorses = 16;

    protected $rules = [
        'form.track_id' => 'required|exists:tracks,id',
        'form.date_at' => 'required|date',
        'form.total_races' => 'required|integer|min:1|max:20',
        'form.is_active' => 'boolean'
    ];

    public function mount()
    {
        $this->dateAt = now()->format('Y-m-d');
        $this->initializeTrackConfig();
        $this->raceConfig = []; // Inicializar como array vacío
    }

    protected function initializeTrackConfig()
    {
        $trackIds = [52,4,9,18,22,25,119,26,27,30,32,38,55,59,60,61,68,76,82,83,89,90,91,103,105,116,23,8];
        
        //$tracks = Track::whereIn('id', $trackIds)
            //->orderByRaw('FIELD(id, ' . implode(',', $trackIds) . ')')
            //->get();
        $tracks = Track::whereIn('id', $trackIds)
            ->get();

        foreach ($tracks as $track) {
            $this->trackConfig[$track->id] = [
                'races' => $this->defaultRaces,
                'selected' => false
            ];
        }
    }

    // Cuando cambia el número de carreras de un hipódromo
    public function updatedTrackConfig($value, $key)
    {
        // key viene como "1.races" donde 1 es el track_id
        $parts = explode('.', $key);
        if (count($parts) === 2 && $parts[1] === 'races') {
            $trackId = $parts[0];
            $numRaces = $value;
            
            // Inicializar configuración de carreras para este hipódromo
            $this->raceConfig[$trackId] = [];
            
            for ($raceNumber = 1; $raceNumber <= $numRaces; $raceNumber++) {
                $this->raceConfig[$trackId][$raceNumber] = [
                    'horses' => $this->defaultHorses,
                    'retired_horses' => [] // Array de números de caballos retirados
                ];
            }
        }
    }

    public function updateRaceConfig($trackId, $numRaces)
    {
        $this->raceConfig[$trackId] = [];
        
        for ($raceNumber = 1; $raceNumber <= $numRaces; $raceNumber++) {
            $this->raceConfig[$trackId][$raceNumber] = [
                'horses' => $this->defaultHorses,
                'retired_horses' => []
            ];
        }
    }

    // Alternar estado de caballo retirado
    public function toggleRetiredHorse($trackId, $raceNumber, $horseNumber)
    {
        if (!isset($this->raceConfig[$trackId][$raceNumber]['retired_horses'])) {
            $this->raceConfig[$trackId][$raceNumber]['retired_horses'] = [];
        }

        $retiredHorses = &$this->raceConfig[$trackId][$raceNumber]['retired_horses'];
        
        if (in_array($horseNumber, $retiredHorses)) {
            // Quitar de retirados
            $retiredHorses = array_filter($retiredHorses, function($num) use ($horseNumber) {
                return $num != $horseNumber;
            });
        } else {
            // Agregar a retirados
            $retiredHorses[] = $horseNumber;
            sort($retiredHorses); // Mantener ordenado
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $calendars = Calendar::with(['track', 'racings'])
            ->orderBy('date_at', 'desc')
            ->paginate(10);

        $trackIds = [52,4,22,25,61,116,119,26,27,30,32,38,55,9,59,60,68,18,76,82,83,89,90,91,103,105,23,8];
        //$tracks = Track::whereIn('id', $trackIds)
            //->orderByRaw('FIELD(id, ' . implode(',', $trackIds) . ')')
            //->get();
        $tracks = Track::whereIn('id', $trackIds)
            ->get();

        return view('livewire.admin.calendar.calendar-create', compact('calendars', 'tracks'));
    }

    public function openConfigModal()
    {
        $this->showConfigModal = true;
    }

    public function selectAllTracks()
    {
        foreach ($this->trackConfig as $trackId => &$config) {
            $config['selected'] = true;
        }
    }

    public function unselectAllTracks()
    {
        foreach ($this->trackConfig as $trackId => &$config) {
            $config['selected'] = false;
        }
    }

    public function setDefaultsForAll()
    {
        foreach ($this->trackConfig as $trackId => &$config) {
            if ($config['selected']) {
                $config['races'] = $this->defaultRaces;
                // Inicializar raceConfig para este track
                $this->raceConfig[$trackId] = [];
                for ($raceNumber = 1; $raceNumber <= $this->defaultRaces; $raceNumber++) {
                    $this->raceConfig[$trackId][$raceNumber] = [
                        'horses' => $this->defaultHorses,
                        'retired_horses' => []
                    ];
                }
            }
        }
    }

    public function createJornadas()
    {
        $this->validate([
            'dateAt' => 'required|date'
        ]);

        $selectedTracks = collect($this->trackConfig)->filter(function ($config) {
            return $config['selected'];
        });

        if ($selectedTracks->isEmpty()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Selecciona al menos un hipódromo'
            ]);
            return;
        }

        try {
            DB::transaction(function () use ($selectedTracks) {
                // Solo obtener los tracks que están seleccionados
                $selectedTrackIds = $selectedTracks->keys()->map(function($key) {
                    return (int)$key;
                })->toArray();
                
                //$tracks = Track::whereIn('id', $selectedTrackIds)
                    //->orderByRaw('FIELD(id, ' . implode(',', $selectedTrackIds) . ')')
                    //->get();
                $tracks = Track::whereIn('id', $selectedTrackIds)
                    ->get();

                foreach($tracks as $track) {
                    // Verificación redundante por seguridad
                    if (!isset($this->trackConfig[$track->id]) || !$this->trackConfig[$track->id]['selected']) {
                        continue;
                    }

                    $numRaces = $this->trackConfig[$track->id]['races'];

                    // Crear SOLO UNA jornada para este track (no 6 como antes)
                    $calendar = Calendar::create([
                        'track_id' => $track->id,
                        'date_at' => $this->dateAt,
                        'total_races' => $numRaces,
                        'is_active' => true,
                    ]);

                    RaceCalendar::create([
                        'calendar_id' => $calendar->id,
                        'race_current' => 1,
                        'user_id' => Auth::id() ?? 1
                    ]);

                    // Crear cada carrera según la configuración
                    for ($raceNumber=1; $raceNumber <= $numRaces ; $raceNumber++) { 
                        // Obtener configuración de la carrera específica
                        $raceConfig = $this->raceConfig[$track->id][$raceNumber] ?? [
                            'horses' => $this->defaultHorses,
                            'retired_horses' => []
                        ];

                        $totalHorses = $raceConfig['horses'];
                        $retiredHorses = $raceConfig['retired_horses'] ?? [];
                        $activeHorses = $totalHorses - count($retiredHorses);

                        $race = Racing::create([
                            'calendar_id' => $calendar->id,
                            'race' => $raceNumber,
                            'total_horses' => $totalHorses,
                            'active_horses' => max(1, $activeHorses),
                            'retired_horses' => count($retiredHorses),
                            'start_time' => $calendar->date_at->format('Y-m-d') . ' ' . now()->addMinutes($raceNumber * 10)->format('H:i'),
                            'distance' => rand(1000, 2000),
                            'status' => 'open',
                        ]);

                        // Crear los caballos para esta carrera
                        for ($horseNumber=1; $horseNumber <= $totalHorses ; $horseNumber++) { 
                            $status = in_array($horseNumber, $retiredHorses) ? 'scratch' : 'run';
                            
                            RacingHorse::create([
                                'racing_id' => $race->id,
                                'nro' => $horseNumber,
                                'status' => $status,
                            ]);
                        }

                        // Crear los tipos de apuesta para esta carrera
                        for ($j=1; $j <= 3 ; $j++) { 
                            RacingBet::create([
                                'racing_id' => $race->id,
                                'bet_type_id' => $j,
                            ]);
                        }
                    }
                }
            });

            $this->showModal = false;
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Jornadas creadas exitosamente!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al crear las jornadas: ' . $e->getMessage()
            ]);
        }
    }

    // Método para editar
    public function edit($id)
    {
        $this->editingCalendar = Calendar::with(['racings.racing_horses'])->findOrFail($id);
        
        // Inicializar configuración para este track específico
        $this->initializeEditConfig($this->editingCalendar);
        
        $this->showEditModal = true;
    }

    protected function initializeEditConfig($calendar)
    {
        $trackId = $calendar->track_id;
        
        // Configuración del track
        $this->editTrackConfig[$trackId] = [
            'races' => $calendar->total_races,
            'selected' => true // Siempre seleccionado porque es el que estamos editando
        ];
        
        // Configuración de las carreras existentes
        $this->editRaceConfig[$trackId] = [];
        
        foreach ($calendar->racings as $racing) {
            $this->editRaceConfig[$trackId][$racing->race] = [
                'horses' => $racing->total_horses,
                'retired_horses' => $racing->racing_horses
                    ->where('status', 'scratch')
                    ->pluck('nro')
                    ->toArray()
            ];
        }
    }

    // Método para actualizar configuración de carreras en edición
    public function updateEditRaceConfig($trackId, $numRaces)
    {
        $this->editRaceConfig[$trackId] = [];
        
        for ($raceNumber = 1; $raceNumber <= $numRaces; $raceNumber++) {
            // Mantener configuración existente o usar defaults
            $existingConfig = $this->editRaceConfig[$trackId][$raceNumber] ?? [
                'horses' => $this->editDefaultHorses,
                'retired_horses' => []
            ];
            
            $this->editRaceConfig[$trackId][$raceNumber] = $existingConfig;
        }
    }

    // Método para alternar caballo retirado en edición
    public function toggleEditRetiredHorse($trackId, $raceNumber, $horseNumber)
    {
        if (!isset($this->editRaceConfig[$trackId][$raceNumber]['retired_horses'])) {
            $this->editRaceConfig[$trackId][$raceNumber]['retired_horses'] = [];
        }

        $retiredHorses = &$this->editRaceConfig[$trackId][$raceNumber]['retired_horses'];
        
        if (in_array($horseNumber, $retiredHorses)) {
            $retiredHorses = array_filter($retiredHorses, function($num) use ($horseNumber) {
                return $num != $horseNumber;
            });
        } else {
            $retiredHorses[] = $horseNumber;
            sort($retiredHorses);
        }
    }

    // Método principal para actualizar la jornada
    public function updateCalendar()
    {
        try {
            DB::transaction(function () {
                $calendar = $this->editingCalendar;
                $trackId = $calendar->track_id;
                
                // Actualizar el calendario principal
                $calendar->update([
                    'total_races' => $this->editTrackConfig[$trackId]['races'],
                    'is_active' => true,
                ]);
                
                $numRaces = $this->editTrackConfig[$trackId]['races'];
                $existingRacings = $calendar->racings->keyBy('race');
                
                for ($raceNumber = 1; $raceNumber <= $numRaces; $raceNumber++) {
                    $raceConfig = $this->editRaceConfig[$trackId][$raceNumber] ?? [
                        'horses' => $this->editDefaultHorses,
                        'retired_horses' => []
                    ];

                    $totalHorses = $raceConfig['horses'];
                    $retiredHorses = $raceConfig['retired_horses'] ?? [];
                    $activeHorses = $totalHorses - count($retiredHorses);

                    // Verificar si ya existe una carrera para este número
                    if (isset($existingRacings[$raceNumber])) {
                        // Actualizar carrera existente
                        $race = $existingRacings[$raceNumber];
                        $race->update([
                            'total_horses' => $totalHorses,
                            'active_horses' => max(1, $activeHorses),
                            'retired_horses' => count($retiredHorses),
                            'start_time' => $calendar->date_at->format('Y-m-d') . ' ' . now()->addMinutes($raceNumber * 10)->format('H:i'),
                            'distance' => rand(1000, 2000),
                            'status' => 'open',
                        ]);
                    } else {
                        // Crear nueva carrera si no existe
                        $race = Racing::create([
                            'calendar_id' => $calendar->id,
                            'race' => $raceNumber,
                            'total_horses' => $totalHorses,
                            'active_horses' => max(1, $activeHorses),
                            'retired_horses' => count($retiredHorses),
                            'start_time' => $calendar->date_at->format('Y-m-d') . ' ' . now()->addMinutes($raceNumber * 10)->format('H:i'),
                            'distance' => rand(1000, 2000),
                            'status' => 'open',
                        ]);
                    }

                    // Eliminar caballos existentes y crear nuevos
                    RacingHorse::where('racing_id', $race->id)->delete();
                    for ($horseNumber = 1; $horseNumber <= $totalHorses; $horseNumber++) {
                        $status = in_array($horseNumber, $retiredHorses) ? 'scratch' : 'run';
                        
                        RacingHorse::create([
                            'racing_id' => $race->id,
                            'nro' => $horseNumber,
                            'status' => $status,
                        ]);
                    }

                    // Eliminar apuestas existentes y crear nuevas
                    RacingBet::where('racing_id', $race->id)->delete();
                    for ($j = 1; $j <= 3; $j++) {
                        RacingBet::create([
                            'racing_id' => $race->id,
                            'bet_type_id' => $j,
                        ]);
                    }
                }
                
                // Eliminar carreras sobrantes si se redujo el número de carreras
                $racesToDelete = $existingRacings->keys()->filter(function($existingRaceNumber) use ($numRaces) {
                    return $existingRaceNumber > $numRaces;
                });
                
                foreach ($racesToDelete as $raceNumberToDelete) {
                    $racingToDelete = $existingRacings[$raceNumberToDelete];
                    RacingHorse::where('racing_id', $racingToDelete->id)->delete();
                    RacingBet::where('racing_id', $racingToDelete->id)->delete();
                    $racingToDelete->delete();
                }
            });

            $this->showEditModal = false;
            $this->reset('editTrackConfig', 'editRaceConfig', 'editingCalendar');
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Jornada actualizada exitosamente!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        $this->reset('form', 'editingId');
        $this->form['date_at'] = $this->dateAt;
        $this->modalTitle = 'Crear Jornada Individual';
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editingId) {
            $this->updateCalendar();
        } else {
            $this->createCalendar();
        }
    }

    public function createCalendar()
    {
        $this->validate([
            'form.track_id' => 'required|exists:tracks,id',
            'form.date_at' => 'required|date',
            'form.total_races' => 'required|integer|min:1|max:20',
            'form.is_active' => 'boolean'
        ]);

        try {
            Calendar::create($this->form);

            $this->showModal = false;
            $this->reset('form');
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Jornada individual creada exitosamente!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al crear la jornada: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $calendar = Calendar::findOrFail($id);
            $calendar->update(['is_active' => !$calendar->is_active]);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Estado actualizado exitosamente!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ]);
        }
    }

}
