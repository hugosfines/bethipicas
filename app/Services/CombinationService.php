<?php

namespace App\Services;

class CombinationService
{
    public $betData = [];

    public function __construct($betData)
    {
        $this->betData = $betData;
    }

    // Función para generar combinaciones entre steps sin repetición
    public function combineStepsWithoutRepetition($stepsArrays)
    {
        $result = [[]];
        
        foreach ($stepsArrays as $step) {
            $newResult = [];
            foreach ($result as $current) {
                foreach ($step as $value) {
                    // Verificar si el valor ya existe en la combinación actual
                    if (!in_array($value, $current)) {
                        $newResult[] = array_merge($current, [$value]);
                    }
                }
            }
            $result = $newResult;
        }
        
        return $result;
    }

    // Función más eficiente para muchos elementos
    public function generateUniqueCombinations($stepsArrays)
    {
        return $this->generateCombinationsRecursive($stepsArrays);
    }

    private function generateCombinationsRecursive($stepsArrays, $current = [], $index = 0)
    {
        if ($index == count($stepsArrays)) {
            return [$current];
        }

        $combinations = [];
        foreach ($stepsArrays[$index] as $value) {
            if (!in_array($value, $current)) {
                $newCurrent = array_merge($current, [$value]);
                $combinations = array_merge(
                    $combinations, 
                    $this->generateCombinationsRecursive($stepsArrays, $newCurrent, $index + 1)
                );
            }
        }

        return $combinations;
    }

    /* public function getAllUniqueCombinations()
    {
        $steps = $this->betData[1][4];
        
        // Para 2 steps
        $combinations2 = $this->combineStepsWithoutRepetition([
            $steps['1'],
            $steps['2']
        ]);

        // Si tuvieras step_3
        //$combinations3 = $this->combineStepsWithoutRepetition([
        //    $steps['step_1'],
        //    $steps['step_2'],
        //    $steps['step_3']
        //]);
        

        return [
            'step_1_step_2' => $this->formatCombinations($combinations2),
            // 'step_1_step_2_step_3' => $this->formatCombinations($combinations3)
        ];
    } */
    public function getAllUniqueCombinations($race, $betTypeId, $stepNumber)
    {
        $steps = $this->betData[$race][$betTypeId];
        $stepsArr = [];

        for ($i=1; $i <= $stepNumber ; $i++) { 
            $stepsArr[] = $steps[$i];
        }
        
        // Para 2 steps
        $combinations2 = $this->combineStepsWithoutRepetition($stepsArr);

        return [
            'steps' => $this->formatCombinations($combinations2),
        ];
    }

    // Función para formatear las combinaciones
    private function formatCombinations($combinations)
    {
        return array_map(function($combination) {
            return implode(', ', $combination);
        }, $combinations);
    }

    // Versión que detecta automáticamente los steps disponibles
    public function getStepCombinationsAuto($race, $betTypeId)
    {
        $stepsData = $this->betData[$race][$betTypeId];
        $stepsArrays = array_values($stepsData);
        
        $combinations = $this->generateUniqueCombinations($stepsArrays);
        
        return $this->formatCombinations($combinations);
    }
    /* public function getStepCombinationsAuto()
    {
        $stepsData = $this->betData[1][4];
        $stepsArrays = array_values($stepsData);
        
        $combinations = $this->generateUniqueCombinations($stepsArrays);
        
        return $this->formatCombinations($combinations);
    } */
    
    // Función para generar combinaciones entre múltiples steps
    public function combineSteps($stepsArrays)
    {
        $result = [[]];
        
        foreach ($stepsArrays as $step) {
            $newResult = [];
            foreach ($result as $current) {
                foreach ($step as $value) {
                    $newResult[] = array_merge($current, [$value]);
                }
            }
            $result = $newResult;
        }
        
        return $result;
    }

    public function getAllCombinations()
    {
        $steps = $this->betData[1][4];
        
        // Para 2 steps (step_1 y step_2)
        $combinations2 = $this->combineSteps([
            $steps['1'],
            $steps['2']
        ]);

        // Si tuvieras step_3, sería así:
        //$combinations3 = $this->combineSteps([
        //    $steps['step_1'],
        //    $steps['step_2'],
        //    $steps['step_3']
        //]);

        return [
            'step_1_step_2' => $this->formatCombinations($combinations2),
            // 'step_1_step_2_step_3' => $this->formatCombinations($combinations3)
        ];
    }

    /** */

    // **** combinaciones para picks
    // **** *********************** */
    /**
     * 
     * Extrae todos los valores de las carreras de forma segura
     */
    private function getRaceValues($betData, $betTypeId)
    {
        $values = [];
        
        foreach ($betData as $raceName => $raceData) {
            // Navegar por la estructura: carrera -> type_8 -> primer array
            if (isset($raceData[$betTypeId]) && is_array($raceData[$betTypeId])) {
                foreach ($raceData[$betTypeId] as $key => $data) {
                    if (is_array($data)) {
                        $values[$raceName] = $data;
                        break; // Tomamos solo el primer array de valores
                    }
                }
            }
        }
        
        // Ordenar por clave para mantener consistencia
        ksort($values);
        
        return $values;
    }

    /**
     * Producto cartesiano entre múltiples arrays
     */
    private function generateCartesianProduct($arrays)
    {
        // Si solo hay un array, retornar sus valores como arrays individuales
        if (count($arrays) === 1) {
            return array_map(function($item) {
                return [$item];
            }, current($arrays));
        }
        
        $result = [[]];
        
        foreach ($arrays as $raceName => $values) {
            $newResult = [];
            foreach ($result as $currentCombination) {
                foreach ($values as $value) {
                    $newResult[] = array_merge($currentCombination, [$value]);
                }
            }
            $result = $newResult;
        }
        
        return $result;
    }

    /**
     * Genera todas las combinaciones posibles entre carreras
     */
    public function getRaceCombinations($betTypeId)
    {
        // Obtener valores de cada carrera
        $raceValues = $this->getRaceValues($this->betData, $betTypeId);
        
        // Generar combinaciones
        $combinations = $this->generateCartesianProduct($raceValues);
        
        // Formatear resultado
        $formatted = array_map(function($combination) {
            return implode(', ', $combination);
        }, $combinations);
        
        return [
            'combinations' => $formatted,
            'race_count' => count($raceValues),
            'total_combinations' => count($combinations)
        ];
    }

    /**
     * Ejemplo de cómo usar con datos dinámicos
     */
    public function withThreeRacesExample($race, $betTypeId)
    {
        /* $exampleData = [
            'carr_1' => [
                'type_8' => [
                    1 => ["1", "2"]
                ]
            ],
            'carr_2' => [
                'type_8' => [
                    2 => ["1", "2", "3"]
                ]
            ],
            'carr_3' => [
                'type_8' => [
                    3 => ["5"]
                ]
            ]
        ]; */
        $exampleData = $this->betData;
        
        $raceValues = $this->getRaceValues($exampleData, $betTypeId);
        
        $combinations = $this->generateCartesianProduct($raceValues);
        
        return array_map(function($combination) {
            return implode(', ', $combination);
        }, $combinations);
    }


}