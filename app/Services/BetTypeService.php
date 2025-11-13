<?php

namespace App\Services;

class BetTypeService
{
    public function getEjemplares($calendarId, $race)
    {
        $racing = \App\Models\Racing::where('status', 'open')
            ->where('calendar_id', $calendarId)
            ->where('race', $race)
            ->first();

        $ejemplares = isset($racing->racing_horses) ? $racing->racing_horses : [];

        return $ejemplares;
    }

    public function addCorredores($calendarId, $race, $betTypeId=1)
    {
        $ejemplares = $this->getEjemplares($calendarId, $race);
               
        switch ($betTypeId) {
            case '1':
            case '2':
            case '3':
                $corredores = (object) [
                    'follows' => (object) [
                        (object) [
                            'bet_type_id' => 1,
                            'tipo_apuesta' => 'Ganador',
                            'title' => 'Ganador',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                        (object) [
                            'bet_type_id' => 2,
                            'tipo_apuesta' => 'Place',
                            'title' => 'Place',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                        (object) [
                            'bet_type_id' => 3,
                            'tipo_apuesta' => 'Show',
                            'title' => 'Show',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                    ]
                ];
                
                return $corredores;
                break;

            case '4': // exacta
                $corredores = (object) [
                    'bet_type_id' => 4,
                    'tipo_apuesta' => 'Exacta',
                    'follows' => (object) [
                        (object) [
                            'title' => '1er Lugar',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                        (object) [
                            'title' => '2do Lugar',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                    ]
                ];
                
                return $corredores;
                break;

            case '5': // trifecta
                $corredores = (object) [
                    'bet_type_id' => 5,
                    'tipo_apuesta' => 'Trifecta',
                    'follows' => (object) [
                        (object) [
                            'title' => '1er Lugar',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                        (object) [
                            'title' => '2do Lugar',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                        (object) [
                            'title' => '3er Lugar',
                            'ejemplares' => $ejemplares,
                            'race' => $race,
                        ],
                    ]
                ];
                
                return $corredores;
                break;

            case '8': // doble, pick3, pick4, pick5
            case '9':
            case '10':
            case '11':
                $races = [];
                $betType = \App\Models\BetType::find($betTypeId);

                for ($i=$race; $i < ($race + $betType->follow); $i++) { 
                    array_push($races, $i);
                }
                
                $followsArray = [];
                foreach ($races as $key => $newRace) {
                    $newEjemplares = $this->getEjemplares($calendarId, $newRace);

                    $followsArray[] = (object) [
                        'title' => $newRace . '° Carr.',
                        'ejemplares' => $newEjemplares,
                        'race' => $newRace,
                    ];
                }
                
                $corredores = (object) [
                    'bet_type_id' => $betTypeId,
                    'tipo_apuesta' => 'Doble',
                    'follows' => (object) $followsArray,
                ];

                return $corredores;
                break;

            
            default:
                $corredores = (object) [
                    'follows' => (object) []
                ];
                return $corredores;
                break;
        }
    }

    // examples
    /* $corredores = (object) [
        '1' => (object) [
            'bet_type_id' => 1,
            'tipo_apuesta' => 'Ganador',
            'title' => 'Ganador',
            'ejemplares' => $ejemplares
        ],
        '2' => (object) [
            'bet_type_id' => 2,
            'tipo_apuesta' => 'Placé',
            'title' => 'Placé',
            'ejemplares' => $ejemplares
        ],
        '3' => (object) [
            'bet_type_id' => 3,
            'tipo_apuesta' => 'Show',
            'title' => 'Show',
            'ejemplares' => $ejemplares
        ],
    ]; */

    /* $ejemplares = (object) [
        (object) [
            'nro' => 1,
            'status' => 'active',
        ],
        (object) [
            'nro' => 2,
            'status' => 'active',
        ],
        (object) [
            'nro' => 3,
            'status' => 'scratch',
        ],
        (object) [
            'nro' => 4,
            'status' => 'active',
        ],
        (object) [
            'nro' => 5,
            'status' => 'active',
        ],
        (object) [
            'nro' => 6,
            'status' => 'active',
        ],
        (object) [
            'nro' => 7,
            'status' => 'scratch',
        ],
        (object) [
            'nro' => 8,
            'status' => 'active',
        ],
    ]; */
}