<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BetType;

class BetTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BetType::create([
            'name' => 'Ganador',
            'category_id' => 1,
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer lugar la meta y sean ratificados por el resultado oficial.',
            'follow' => 1,
            'positions' => [1],
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Place',
            'category_id' => 1,
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer o segundo lugar la meta y sean ratificados por el resultado oficial.',
            'follow' => 1,
            'positions' => [1,2],
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Show',
            'category_id' => 1,
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer, segundo o tercer lugar la meta y sean ratificados por el resultado oficial.',
            'follow' => 1,
            'positions' => [1,2,3],
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Exacta',
            'category_id' => 2,
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer y segundo lugar la meta, en el orden exacto en que se indica.',
            'follow' => 2,
            'box' => 'yes',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Trifecta',
            'category_id' => 2,
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer, segundo y tercer lugar la meta, en el orden exacto en que se indica.',
            'follow' => 3,
            'box' => 'yes',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Superfecta',
            'category_id' => 2,
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer, segundo, tercer y cuarto lugar la meta, en el orden exacto en que se indica.',
            'follow' => 4,
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Quiniela',
            'category_id' => 2,
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer y segundo lugar la meta, en el orden que se indica.',
            'follow' => 2,
            'box' => 'no',
            'is_active' => false,
        ]);

        BetType::create([
            'name' => 'Doble',
            'category_id' => 3,
            'description' => 'Consiste en acertar el primer lugar en dos carreras consecutivas de una reunión hípica.',
            'follow' => 2,
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Pick 3',
            'category_id' => 3,
            'description' => 'Consiste en acertar el primer lugar en tres carreras consecutivas de una reunión hípica.',
            'follow' => 3,
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Pick 4',
            'category_id' => 3,
            'description' => 'Consiste en acertar el primer lugar en cuatro carreras consecutivas de una reunión hípica.',
            'follow' => 4,
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Pick 5',
            'category_id' => 3,
            'description' => 'Consiste en acertar el primer lugar en cinco carreras consecutivas de una reunión hípica.',
            'follow' => 5,
            'box' => 'no',
            'is_active' => true,
        ]);

        BetType::create([
            'name' => 'Pick 6',
            'category_id' => 3,
            'description' => 'Consiste en acertar el primer lugar en seis carreras consecutivas de una reunión hípica.',
            'follow' => 6,
            'box' => 'no',
            'is_active' => false,
        ]);
        
    }
}
