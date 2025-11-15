<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Headquarter;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Luz Fernandez',
            'email' => 'luzmfernandezp77@gmail.com',
            'username' => 'helluzbaby',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        $headquarter = Headquarter::find(1);

        $user->headquarters()->sync($headquarter->id);

        $user->assignRole('SuperAdmin');
    }
}
