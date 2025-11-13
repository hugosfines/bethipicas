<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Company;
use App\Models\Headquarter;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        $user = User::create([
            'name' => 'Hugo Rivero',
            'email' => 'hugosfines@gmail.com',
            'username' => 'hugosfines',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        $companny = Company::create([
            'name' => 'Las Nieves',
            'country' => 'Colombia',
            'city' => 'Bogotá',
            'phone' => '1234567890',
        ]);

        $headquarter = Headquarter::create([
            'company_id' => $companny->id,
            'name' => 'Nieves',
            'email' => 'lasnievesbogota8@gmail.com',
            'phone' => '1234567890',
            'address' => 'Calle 123 # 45-67',
            'is_active' => true,
        ]);

        $user->headquarters()->sync($headquarter->id);

        $this->call([
            TracksTableSeeder::class,
            CategoryTableSeeder::class,
            BetTypeTableSeeder::class,
            CalendarTableSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
