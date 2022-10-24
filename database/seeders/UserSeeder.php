<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'name' => 'Test Doe',
            'email' => 'testdoe@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Testdoe1.'),
            'remember_token' => Str::random(10),
        ]);

        $role = Role::where('name', 'super-admin')->first();
        $user->assignRole($role);
    }
}
