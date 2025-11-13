<?php

namespace App\Livewire\Admin\Result;

use Livewire\Component;

use App\Models\BetType;
use App\Models\Result;
use App\Models\Calendar;
use App\Models\Racing;
use App\Models\RaceCalendar;
use App\Models\RacingBet;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class ResultCreate extends Component
{
    public $dateAt;
    public $calendars = [];
    public $calendarId;
    public $calendarData;
    public $raceCurrent = 1;
    public $racing;
    public $betTypes = [];
    
    // Resultados simples: [bet_type_id => [numero, dividendo]]
    public $results = [];
    public $ties = []; // Para manejar empates

    protected $rules = [
        'results.*.number' => 'required|integer|min:1',
        'results.*.dividendo' => 'required|numeric|min:0',
        'ties.*.number' => 'required|integer|min:1',
        'ties.*.dividendo' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->dateAt = now()->format('Y-m-d');
        $this->loadCalendars();
    }

    public function loadCalendars()
    {
        $calendars = Calendar::orderBy('track_id', 'asc')
            ->with('track')
            ->where('date_at', $this->dateAt)
            ->where('is_active', true)
            ->get();

        $this->calendars = $calendars;
        $this->calendarId = $calendars->first()->id ?? '';
        $this->loadCalendarData();
    }

    public function loadCalendarData($changeRaceCurrent = false)
    {
        $this->calendarData = $this->calendarId ? Calendar::with('track')
            ->where('is_active', true)
            ->find($this->calendarId) : null;

        $raceCalendar = $this->calendarData ? 
            RaceCalendar::where('calendar_id', $this->calendarData->id)->latest()->first() : null;
        
        if (!$changeRaceCurrent) {
            $this->raceCurrent = $raceCalendar->race_current ?? 1;
        }

        $this->racing = $this->calendarId ? Racing::where('calendar_id', $this->calendarId)
            ->where('race', $this->raceCurrent)
            ->first() : null;

        /* $betTypes = isset($this->racing->racing_bets) ? 
            $this->racing->racing_bets->sortBy('bet_type_id')->values() : []; */
        $racingBets = isset($this->racing->racing_bets) ? RacingBet::with('bet_type.category')
            ->where('racing_id', $this->racing->id)
            ->orderBy('bet_type_id', 'asc')
            ->get() : collect();

        $this->betTypes = $racingBets->count() ? BetType::whereIn('id', $racingBets->pluck('bet_type_id'))
            ->whereIn('category_id', [1])
            ->orderBy('category_id')
            ->orderBy('id')
            ->get() : collect();
        
        $this->loadExistingResults();
    }

    public function loadExistingResults()
    {
        $this->results = [];
        $this->ties = [];

        if ($this->racing) {
            $existingResults = Result::where('racing_id', $this->racing->id)
                ->orderBy('bet_type_id')
                ->orderBy('order')
                ->get();

            foreach ($existingResults as $key => $result) {
                if ($result->order == 1) {
                //if ($key == 0) {
                    // Resultado principal
                    /* $this->results[$result->bet_type_id] = [
                        'number' => $result->number,
                        'dividendo' => (float) $result->dividendo,
                    ]; */
                    $this->results[$result->bet_type_id] = [
                        'id' => $result->id,
                        'number' => $result->number,
                        'dividendo' => (float) $result->dividendo,
                        'order' => $result->order,
                    ];
                } else {
                    // Empates (order > 1)
                    $this->ties[$result->bet_type_id][] = [
                        'id' => $result->id,
                        'number' => $result->number,
                        'dividendo' => (float) $result->dividendo,
                        'order' => $result->order,
                    ];
                }
            }

            // Inicializar resultados vacíos para cada tipo de apuesta
            foreach ($this->betTypes as $betType) {
                if (!isset($this->results[$betType->id])) {
                    $this->results[$betType->id] = ['number' => '', 'dividendo' => ''];
                }
                if (!isset($this->ties[$betType->id])) {
                    $this->ties[$betType->id] = [];
                }
            }

            ksort($this->results);
        }
    }

    public function addTie($betTypeId)
    {
        $this->ties[$betTypeId][] = [
            'number' => '',
            'dividendo' => '',
            'order' => count($this->ties[$betTypeId]) + 2 // Empieza en posición 2
        ];
    }

    public function removeTie($betTypeId, $index)
    {
        if (isset($this->ties[$betTypeId][$index]['id'])) {
            Result::find($this->ties[$betTypeId][$index]['id'])->delete();
        }
        unset($this->ties[$betTypeId][$index]);
        $this->ties[$betTypeId] = array_values($this->ties[$betTypeId]);
    }

    public function saveResults()
    {
        //$this->validate();

        if (!$this->racing) {
            session()->flash('error', 'No se encontró la carrera seleccionada.');
            return;
        }

        try {
            // Eliminar resultados existentes para esta carrera
            //dd($this->results);
            Result::where('racing_id', $this->racing->id)->delete();
            
            // Guardar nuevos resultados
            foreach ($this->results as $betTypeId => $result) {
                if (!empty($result['number']) && !empty($result['dividendo'])) {
                    // Guardar resultado principal (posición 1)
                    Result::create([
                        'racing_id' => $this->racing->id,
                        'bet_type_id' => $betTypeId,
                        'order' => 1,
                        'number' => $result['number'],
                        'dividendo' => (float) $result['dividendo'],
                        'user_id' => Auth::id(),
                    ]);

                    // Guardar empates si existen
                    if (isset($this->ties[$betTypeId])) {
                        foreach ($this->ties[$betTypeId] as $index => $tie) {
                            if (!empty($tie['number']) && !empty($tie['dividendo'])) {
                                Result::create([
                                    'racing_id' => $this->racing->id,
                                    'bet_type_id' => $betTypeId,
                                    'order' => $index + 2, // Posición 2, 3, etc.
                                    'number' => $tie['number'],
                                    'dividendo' => (float) $tie['dividendo'],
                                    'user_id' => Auth::id(),
                                ]);
                            }
                        }
                    }
                }
            }

            // EJECUTAR EL COMMAND DESPUÉS DE GUARDAR
            $this->processWinners();

            session()->flash('success', 'Resultados guardados exitosamente.');
            $this->loadExistingResults();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar los resultados: ' . $e->getMessage());
        }
    }

    public function updatedDateAt()
    {
        $this->loadCalendars();
    }

    public function updatedCalendarId()
    {
        $this->loadCalendarData();
    }

    public function updatedRaceCurrent($value)
    {
        $this->loadCalendarData($value);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.result.result-create');
    }

    protected function processWinners()
    {
        try {
            // Ejecutar el command
            \Illuminate\Support\Facades\Artisan::call('winners:process', [
                'racing_id' => $this->racing->id
            ]);

            // Opcional: obtener output del command
            $output = \Illuminate\Support\Facades\Artisan::output();
            \Illuminate\Support\Facades\Log::info("Winners processing output: " . $output);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error executing winners command: " . $e->getMessage());
            // No mostrar error al usuario para no interrumpir el flujo
        }
    }
}
