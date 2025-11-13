<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TracksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/tracks.json'); // Ruta al archivo JSON
        $json = File::get($path);
        $data = json_decode($json, true);
        
        foreach ($data as $item) {
            DB::table('tracks')->insert([
                'name' => $item['name'],
                'code' => $item['code'],
                'division_factor' => 2.00,
                'status' => true,
            ]);
        }

        DB::table('tracks')->insert([
            'name' => 'La Rinconada',
            'code' => 'LRNDA',
            'division_factor' => 50.00,
            'status' => true,
        ]);
    }
}
