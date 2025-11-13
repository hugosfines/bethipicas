<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Simples',
            'type_follow' => 'current',
            'description' => 'Se consideran ganadores los ejemplares que crucen en primer lugar la meta y sean ratificados por el resultado oficial.',
        ]);

        Category::create([
            'name' => 'Exóticas',
            'type_follow' => 'current',
            'description' => 'Las que permiten realizar combinaciones en el orden de llegada de los dos (2), tres (3) o cuatro (4) primeros lugares.',
        ]);

        Category::create([
            'name' => 'Múltiples',
            'type_follow' => 'next',
            'description' => 'Consiste en acertar el primer lugar en dos o más carreras en una reunión hípica.',
        ]);
    }
}
