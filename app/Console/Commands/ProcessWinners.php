<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Result;
use App\Models\Racing;
use App\Models\Bet; 
use App\Models\BetLine;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessWinners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winners:process {racing_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa los ganadores y calcula los pagos de las apuestas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $racingId = $this->argument('racing_id');
        
        if ($racingId) {
            // Procesar una carrera específica
            $this->processRacing($racingId);
        } else {
            // Procesar todas las carreras con resultados del día
            $this->processAllRacings();
        }

        $this->info('Procesamiento de ganadores completado.');
    }

    private function processAllRacings()
    {
        $today = now()->format('Y-m-d');
        
        $racings = Racing::whereHas('results')
            ->whereHas('calendar', function($query) use ($today) {
                $query->where('date_at', $today);
            })
            ->with(['results', 'bets'])
            ->get();

        foreach ($racings as $racing) {
            $this->processRacing($racing->id);
        }
    }

    private function processRacing($racingId)
    {
        try {
            DB::beginTransaction();
            
            /* $racing = Racing::with(['results' => function($query) {
                $query->orderBy('bet_type_id')->orderBy('order');
            }, 'bets'])->find($racingId); */
            $racing = Racing::with(['results' => function($query) {
                $query->orderBy('bet_type_id')->orderBy('order');
            }, 'bet'])->find($racingId);
            
            if (!$racing) {
                $this->error("Carrera con ID {$racingId} no encontrada.");
                return;
            }

            if ($racing->results->isEmpty()) {
                $this->warn("Carrera {$racingId} no tiene resultados.");
                return;
            }

            $this->info("Procesando ganadores para carrera: {$racing->id}");
            
            // Obtener todas las apuestas de esta carrera
            $bets = Bet::where('racing_id', $racingId)->get();

            $winnersCount = 0;
            $totalPayout = 0;

            BetLine::orderBy('bet_type_id')->orderBy('id')
                //select('id', 'bet_id', 'step_1 as nro')
                //->where('bet_type_id', 1)
                //->where('step_1', $bet->step_1)
                ->whereHas('bet', function($query) use ($racing) {
                    $query->where('racing_id', $racing->id);
                })
                ->update([
                    'type' => 'playing', // playing - expecting - win - return - lost
                    'status' => 'pending',  // playing - pending - processed - paid - canceled
                    'amount_pay' => 0.00,
                    'amount_paid' => 0.00
                ]);

            $racingResults = $racing->results;

            foreach ($bets as $betm) {
                foreach ($betm->betLines as $key => $bet) {
                    $isWinner = false;

                    if (in_array($bet->bet_type_id, [1, 2, 3])) {
                        if ($bet->status != 'paid') {
                            $isWinner = $this->checkIfBetWins($bet, $racingResults);
                        }
                    }
                    
                    if ($isWinner) {
                        //dd('isWinner', $isWinner);
                        /* $payout = $this->calculatePayout($bet, $racing->results);
                        
                        // Marcar apuesta como ganadora y guardar pago
                        $bet->update([
                            'is_winner' => true,
                            'payout_amount' => $payout,
                            'processed_at' => now(),
                        ]);

                        // Aquí puedes agregar lógica para pagar al usuario
                        $this->payUser($bet->user_id, $payout);

                        $winnersCount++;
                        $totalPayout += $payout; */

                        //$this->info("Apuesta {$bet->id} GANADORA - Pago: {$payout}");
                    } else {
                        /* $bet->update([
                            'is_winner' => false,
                            'processed_at' => now(),
                        ]); */
                    }
                }
            }

            // Marcar la carrera como procesada
            $racing->update(['winners_processed_at' => now()]);

            DB::commit();

            $this->info("Procesados {$winnersCount} ganadores de " . $bets->count() . " apuestas.");
            $this->info("Pago total: {$totalPayout}");

            // Log del procesamiento
            Log::info("Winners processed for racing {$racingId}", [
                'winners_count' => $winnersCount,
                'total_bets' => $bets->count(),
                'total_payout' => $totalPayout,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error procesando carrera {$racingId}: " . $e->getMessage());
            Log::error("Error processing winners for racing {$racingId}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function checkIfBetWins($bet, $results)
    {
        // Lógica para determinar si la apuesta gana según el tipo de apuesta
        $betTypeId = $bet->bet_type_id;

        //$betNumbers = json_decode($bet->numbers, true);
        // *$betNumbers = $bet->{"step_1"};

        switch ($betTypeId) {
            case 1: // Ganador
                // Filtrar resultados por tipo de apuesta
                $betResults = $results->where('bet_type_id', $betTypeId);
                return $this->checkWinner($bet, $betResults);
            
            case 2: // Place
                // Filtrar resultados por tipo de apuesta
                $betResults = $results->whereIn('bet_type_id', [1, 2]);
                return $this->checkPlace($bet, $betResults);
            
            case 3: // Show
                // Filtrar resultados por tipo de apuesta
                $betResults = $results->whereIn('bet_type_id', [1, 2, 3]);
                return $this->checkShow($bet, $betResults);
            
            /* case 4: // Exacta
                return $this->checkExacta($betNumbers, $betResults);
            
            case 5: // Quiniela
                return $this->checkQuiniela($betNumbers, $betResults); */
            
            // Agregar más tipos de apuesta según necesites
            
            default:
                return false;
        }
    }

    private function checkWinner($bet, $results)
    {
        // Para ganador, el número debe estar en posición 1 (puede haber empates)
        $isLoser = true;
        $aditionalDividendo = (500 / 1000);
        foreach ($results as $key => $result) {
            if ($bet->step_1 == $result->number) {
                $div_1 = ($result->dividendo + $aditionalDividendo) / 2;

                $bet->type = 'win';
                $bet->status = 'processed';
                $bet->amount_pay = $bet->amount * $div_1;
                $bet->amount_paid = 0;
                $bet->save();
                return true;
            } else {
                $isLoser = true;
            }
        }

        if ($isLoser) {
            $bet->type = 'lost';
            $bet->status = 'processed';
            $bet->amount_pay = 0;
            $bet->amount_paid = 0;
            $bet->save();
            return false;
        }
    }

    private function checkPlace($bet, $results)
    {
        // Para place, el número debe estar en posición 1 o 2 (puede haber empates)
        $isLoser = true;
        $aditionalDividendo = 0;
        foreach ($results as $key => $result) {
            if ($bet->step_2 == $result->number) {
                $div_1 = ($result->dividendo + $aditionalDividendo) / 2;

                $bet->type = 'win';
                $bet->status = 'processed';
                $bet->amount_pay = $bet->amount * $div_1;
                $bet->amount_paid = 0;
                $bet->save();
                return true;
            } else {
                $isLoser = true;
            }
        }

        if ($isLoser) {
            $bet->type = 'lost';
            $bet->status = 'processed';
            $bet->amount_pay = 0;
            $bet->amount_paid = 0;
            $bet->save();
            return false;
        }
    }

    private function checkShow($bet, $results)
    {
        // Para show, el número debe estar en posición 1, 2 o 3 (puede haber empates)
        $isLoser = true;
        $aditionalDividendo = 0;
        foreach ($results as $key => $result) {
            if ($bet->step_3 == $result->number) {
                $div_1 = ($result->dividendo + $aditionalDividendo) / 2;

                $bet->type = 'win';
                $bet->status = 'processed';
                $bet->amount_pay = $bet->amount * $div_1;
                $bet->amount_paid = 0;
                $bet->save();
                return true;
            } else {
                $isLoser = true;
            }
        }

        if ($isLoser) {
            $bet->type = 'lost';
            $bet->status = 'processed';
            $bet->amount_pay = 0;
            $bet->amount_paid = 0;
            $bet->save();
            return false;
        }
    }

    private function checkExacta($betNumbers, $results)
    {
        // Para exacta, debe acertar 1ro y 2do en orden exacto
        $first = $results->where('position', 1)->pluck('number')->toArray();
        $second = $results->where('position', 2)->pluck('number')->toArray();
        
        // En caso de empates, cualquiera de los números empatados cuenta
        return in_array($betNumbers[0], $first) && in_array($betNumbers[1], $second);
    }

    private function checkQuiniela($betNumbers, $results)
    {
        // Para quiniela, debe acertar 1ro y 2do en cualquier orden
        $first = $results->where('position', 1)->pluck('number')->toArray();
        $second = $results->where('position', 2)->pluck('number')->toArray();
        
        $winningNumbers = array_merge($first, $second);
        return count(array_intersect($betNumbers, $winningNumbers)) == 2;
    }

    private function calculatePayout($bet, $results)
    {
        // Calcular el pago basado en el dividendo y el monto apostado
        $betTypeId = $bet->bet_type_id;
        $betAmount = $bet->amount;
        
        $betResults = $results->where('bet_type_id', $betTypeId);
        
        // Para apuestas simples (Ganador, Place, Show)
        if (in_array($betTypeId, [1, 2, 3])) {
            $winningResult = $betResults->where('position', 1)->first();
            if ($winningResult) {
                return $betAmount * $winningResult->dividendo;
            }
        }
        
        // Para apuestas combinadas (Exacta, Quiniela, etc.)
        // Aquí necesitarías lógica más compleja según tus reglas
        
        return $betAmount; // Fallback
    }

    private function payUser($userId, $amount)
    {
        // Aquí implementas la lógica para pagar al usuario
        // Por ejemplo, actualizar su balance
        
        $user = User::find($userId);
        if ($user) {
            $user->increment('balance', $amount);
            
            // Opcional: crear registro de transacción
            // Transaction::create([...]);
        }
    }
}
