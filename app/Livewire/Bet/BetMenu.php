<?php

namespace App\Livewire\Bet;

use Livewire\Component;

use App\Models\Bet;
use App\Models\BetLine;
use App\Models\BetType;
use App\Models\Calendar;
use App\Models\RaceCalendar;
use App\Services\ConstService;
use App\Services\BetTypeService;
use App\Services\CombinationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class BetMenu extends Component
{
    public $loading = false;
    public $loadingTwo = false;

    public $codeNumbers = [];
    public $colorNumbers = [];
    public $defaultPrices = [500, 1000, 2000, 5000, 10000, 20000, 50000, 100000, 200000, 300000, 400000, 500000];

    public $dateAt;
    public $calendars = [];
    public $calendarId = '';
    public $calendarData;
    public $racing = null;

    public $raceCurrent = 1;

    public $betTypes = [];
    public $betTypeId = 1;
    public $betTypeData = null;

    public $montoSelect = '';
    public $monto = '';

    public $corredores;

    public $jugadas = [];

    public function mount()
    {
        $service = new ConstService();
        $this->codeNumbers = $service->codesNumber;
        $this->colorNumbers = $service->colorsNumber;

        $this->dateAt = now()->format('Y-m-d');

        $this->loadData();
        // $account = MyAccount();
    }

    public function updatedDateAt()
    {
        $this->reset(['calendarData', 'calendarId', 'betTypeId', 'corredores', 'calendars', 'betTypes']);

        $this->loadData();
    }

    public function updatedCalendarId($value)
    {
        $this->calendarData = Calendar::with('track')
            ->where('is_active', true)
            ->find($value);

        $raceCalendar = $this->calendarData ? RaceCalendar::where('calendar_id', $this->calendarData->id)->latest()->first() : null;
        
        $this->betTypeId = 1;
        $this->raceCurrent = $raceCalendar->race_current ?? 1;
        $this->corredores = [];
        
        $this->addCorredores();
    }

    public function updatedRaceCurrent()
    {
        $this->betTypeId = 1;
        $this->corredores = [];
    }

    public function updatedBetTypeId($value)
    {
        $this->corredores = [];
        $this->addCorredores();
    }

    public function updatedMontoSelect($value)
    {
        $this->monto = number_format($value, 0, '.', ',');
    }

    public function updatedMonto($value)
    {
        $this->montoSelect = str_replace(',', '', $value);
    }

    public function loadData()
    {
        $this->calendars = Calendar::orderBy('track_id', 'asc')
            ->with('track')
            ->where('date_at', $this->dateAt)
            ->where('is_active', true)
            ->get();

        $this->calendarId = isset($this->calendars[0]->id) ? $this->calendars[0]->id : '';
        
        $this->updatedCalendarId($this->calendarId);
    }

    public function addCorredores()
    {
        $service = new BetTypeService;

        $this->betTypeData = BetType::with('category')->find($this->betTypeId);

        $this->racing = \App\Models\Racing::where('calendar_id', $this->calendarId)
            ->where('race', $this->raceCurrent)
            ->first(); // where('status', 'open')

        $this->betTypes = isset($this->racing->racing_bets) ? $this->racing->racing_bets
            ->sortBy('bet_type_id')
            ->values() : [];

        $this->corredores = $service->addCorredores($this->calendarId, $this->raceCurrent, $this->betTypeId);

        $this->reset(['jugadas']);

        $this->dispatch('clearBets');
        
        $this->loadingTwo = false;
    }

    public function createPlayings($betTypeId, $nro, $race, $step)
    {
        // Inicializar el array si no existe
        if (!isset($this->jugadas[$race][$betTypeId][$step])) {
            $this->jugadas[$race][$betTypeId][$step] = [];
        }
        
        // Verificar si el número ya está en el paso actual ($step)
        if (in_array($nro, $this->jugadas[$race][$betTypeId][$step])) {
            // Eliminar el número del paso actual
            $this->jugadas[$race][$betTypeId][$step] = array_diff($this->jugadas[$race][$betTypeId][$step], [$nro]);
        } else {
            // Añadir el número al paso actual
            $this->jugadas[$race][$betTypeId][$step][] = $nro;
            sort($this->jugadas[$race][$betTypeId][$step], SORT_NUMERIC);
        }

        // Eliminar el paso si está vacío (opcional)
        if (empty($this->jugadas[$race][$betTypeId][$step])) {
            unset($this->jugadas[$race][$betTypeId][$step]);
        }

        if (empty($this->jugadas[$race][$betTypeId])) {
            unset($this->jugadas[$race][$betTypeId]);
        }

        if (empty($this->jugadas[$race])) {
            unset($this->jugadas[$race]);
        }

        $this->dispatch('desmarcar-select-all', $nro);
    }

    public function selectAllExotics($nro, $isChecked)
    {
        $betTypeId = $this->betTypeData->id;
        $steps = $this->betTypeData->follow;
        $race = $this->raceCurrent;
        
        for ($step=1; $step<=$steps ; $step++) { 
            if (!isset($this->jugadas[$race][$betTypeId][$step])) {
                $this->jugadas[$race][$betTypeId][$step] = [];
            }

            // Verificar si el número ya está en el paso actual ($step)
            if (in_array($nro, $this->jugadas[$race][$betTypeId][$step])) {
                // Eliminar el número del paso actual
                if (!$isChecked) {
                    $this->jugadas[$race][$betTypeId][$step] = array_diff($this->jugadas[$race][$betTypeId][$step], [$nro]);
                }
            } else {
                // Añadir el número al paso actual
                $this->jugadas[$race][$betTypeId][$step][] = $nro;
                sort($this->jugadas[$race][$betTypeId][$step], SORT_NUMERIC);
            }

            // Eliminar el paso si está vacío (opcional)
            if (empty($this->jugadas[$race][$betTypeId][$step])) {
                unset($this->jugadas[$race][$betTypeId][$step]);
            }

            if (empty($this->jugadas[$race][$betTypeId])) {
                unset($this->jugadas[$race][$betTypeId]);
            }

            if (empty($this->jugadas[$race])) {
                $this->jugadas = [];
            }

            if ($isChecked) {
                $this->dispatch('marcar-numeros', [
                    'nro' => $nro,
                    'step' => $step
                ]);
            } else {
                $this->dispatch('desmarcar-numeros', [
                    'nro' => $nro,
                    'step' => $step
                ]);
            }
        }
        $this->loadingTwo = false;
    }

    #[Computed()]
    public function getTimeToStart()
    {
        if (empty($this->racing->start_time)) {
            return null;
        }

        return diffMinutes($this->racing->start_time, true);
    }

    #[Computed()]
    public function totalPaymentBet()
    {
        if (COUNT($this->jugadas) > 0) {
            $betType = $this->betTypeData;
            $combinations = 0;

            if ($betType->category->type_follow == 'current') {
                foreach ($this->jugadas as $keyRace => $carreras) { // $this->jugadas[$race][$betTypeId][$step]
                    foreach ($carreras as $keyBetType => $apuestas) {
                        ksort($apuestas);

                        if (in_array($betType->id, [1,2,3])) {
                            foreach ($apuestas as $keyStep => $pasos) {
                                ksort($pasos);
                                foreach ($pasos as $key => $nro) {
                                    $combinations++;
                                }
                            }
                        } else {
                            $combinationService = new CombinationService($this->jugadas);
                            
                            switch ($betType->category->type_follow) {
                                case 'current':
                                    // exoticas
                                    $uniqueCombinations = $combinationService->getStepCombinationsAuto($keyRace, $keyBetType);

                                    foreach ($uniqueCombinations as $key => $plays) {
                                        $playings = explode(',', $plays);

                                        if (COUNT($playings) == $betType->follow) {
                                            $combinations++;
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
            } else {
                // picks
                $combinationService = new CombinationService($this->jugadas);
                $threeRaces = $combinationService->withThreeRacesExample($this->raceCurrent, $betType->id);

                foreach ($threeRaces as $key => $plays) {
                    $playings = explode(',', $plays);

                    if (COUNT($playings) == $betType->follow) {
                        $combinations++;
                    }
                }
            }

            $monto = $this->monto ? str_replace([',', ' '], '', $this->monto) : 0;
            return ($combinations * $monto);
        }
    }

    public function saveAndPrint()
    {
        try {
            $this->validate([
                'monto' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $numeric = str_replace([',', ' '], '', $value);
                        if (!is_numeric($numeric) || $numeric <= 0) {
                            $fail('El monto debe ser un número válido mayor que cero.');
                        }
                    },
                ],
            ]);
            
            ksort($this->jugadas);
            //$betSt = '';

            if (empty($this->totalPaymentBet())) {
                throw new \Exception("Apuesta no válida", 1);
            }

            DB::beginTransaction();

            $betType = \App\Models\BetType::with('category')->find($this->betTypeId);

            $bet = Bet::create([
                'racing_id' => $this->racing->id,
                'date_at' => date('Y-m-d', strtotime($this->racing->start_time)),
                'user_id' => Auth::id(),
                'amount' => str_replace([',', ' '], '', $this->monto),
            ]);
            
            if ($betType->category->type_follow == 'current') {
                foreach ($this->jugadas as $keyRace => $carreras) { // $this->jugadas[$race][$betTypeId][$step]
                    foreach ($carreras as $keyBetType => $apuestas) {
                        ksort($apuestas);

                        if (in_array($betType->id, [1,2,3])) {
                            foreach ($apuestas as $keyStep => $pasos) {
                                ksort($pasos);
                                foreach ($pasos as $key => $nro) {
                                    BetLine::create([
                                        'calendar_id' => $this->calendarData->id,
                                        'bet_id' => $bet->id,
                                        'bet_type_id' => $keyStep,
                                        'race' => $this->raceCurrent,
                                        'amount' => str_replace([',', ' '], '', $this->monto),
                                        "step_{$keyStep}" => $nro,
                                    ]);
                                    //$betSt .=  $keyStep . $nro . PHP_EOL;
                                    //$betSt .=  'Carr: '.$keyRace.'> Apuesta: '.$apuesta->name.'> Nro:'. $nro . PHP_EOL;
                                }
                            }
                        } else {
                            $combinationService = new CombinationService($this->jugadas);
                            
                            switch ($betType->category->type_follow) {
                                case 'current':
                                    // exoticas
                                    $uniqueCombinations = $combinationService->getStepCombinationsAuto($keyRace, $keyBetType);
                                    $listPlaying = [];
                                    // $uniqueCombinations = $combinationService->getAllUniqueCombinations($keyRace, $keyBetType, $apuesta->follow);
                                    // $allCombinations = $combinationService->getAllCombinations($keyRace, $keyBetType, $this->raceCurrent, $betType->follow);
                                    foreach ($uniqueCombinations as $key => $plays) {
                                        $playings = explode(',', $plays);
                                        
                                        $listPlaying = [
                                            'calendar_id' => $this->calendarData->id,
                                            'bet_id' => $bet->id,
                                            'bet_type_id' => $betType->id,
                                            'race' => $this->raceCurrent,
                                            'amount' => str_replace([',', ' '], '', $this->monto),
                                            'step_1' => $playings[0],
                                            'step_2' => $playings[1] ?? null,
                                            'step_3' => $playings[2] ?? null,
                                            'step_4' => $playings[3] ?? null,
                                            'step_5' => $playings[4] ?? null,
                                            'step_6' => $playings[6] ?? null,
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ];
                                        BetLine::insert($listPlaying);
                                    }
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }
                        }
                    }
                }
            } else {
                // picks
                $combinationService = new CombinationService($this->jugadas);
                $result = $combinationService->getRaceCombinations($betType->id);
                $threeRaces = $combinationService->withThreeRacesExample($this->raceCurrent, $betType->id);
                
                $listPlaying = [];
                $raceInit = $this->raceCurrent;
                $raceEnd = ($this->raceCurrent + $betType->follow) - 1;

                foreach ($threeRaces as $key => $plays) {
                    $playings = explode(',', $plays);
                    
                    $listPlaying = [
                        'calendar_id' => $this->calendarData->id,
                        'bet_id' => $bet->id,
                        'bet_type_id' => $betType->id,
                        'race' => $this->raceCurrent,
                        'amount' => str_replace([',', ' '], '', $this->monto),
                        'step_1' => $playings[0],
                        'step_2' => $playings[1] ?? null,
                        'step_3' => $playings[2] ?? null,
                        'step_4' => $playings[3] ?? null,
                        'step_5' => $playings[4] ?? null,
                        'step_6' => $playings[6] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    BetLine::insert($listPlaying);
                }
            }
            
            DB::commit();
            $this->reset(['jugadas', 'betTypeId', 'monto', 'montoSelect']);

            $this->dispatch('clearBets');
            $this->dispatch('clearAmounts');
            $this->dispatch('clear-selectAll');

            //return redirect()->route('tickets.imprimir', $bet->id);
            $this->dispatch('abrir-impresion', ticketId: $bet->id);

            flash()->success('Jugada registrada con exito...');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura los errores de validación
            //$errores = $e->validator->errors()->all();
            $errores = [];

            $this->clearValidation();
            
            foreach ($e->validator->errors()->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message); // Para que funcione @error en la vista
                    $errores[$message] = $message;
                }
            }
            // Envía los mensajes de error mediante flash
            flash()->warning(implode('<br>', $errores));
        } catch (\Exception $e) {
            $codigoError = $e->getCode();
            $prefError = $codigoError ? 'Ha ocurrido un error inesperado. ' : '';
            flash()->warning($prefError . $e->getMessage());
        }
    }

    public function render()
    {
        $this->loading = false;

        return view('livewire.bet.bet-menu');
    }
}
