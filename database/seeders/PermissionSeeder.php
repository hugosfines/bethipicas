<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'sales']);

        // Super Admin
        $superAdmin = Role::create(['name' => 'SuperAdmin']);
        $superAdmin->givePermissionTo([
            'sales',
        ]);
        $user = User::find(1); // firts user
        $user->assignRole('SuperAdmin');
    }
}
