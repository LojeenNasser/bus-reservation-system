<?php

namespace Database\Seeders;

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRoles = Role::all();

        // Admins and test user account
        User::factory()->create([
            'email' => 'superadmin@bbs.xyz',
            'password' => Hash::make('BbsSuper123')
        ])->roles()->attach($userRoles);

        User::factory()->create([
            'email' => 'admin@bbs.xyz',
            'password' => Hash::make('BbsAdmin123')
        ])->roles()->attach($userRoles->whereNotIn('name', 'super_admin'));

        User::factory()->create([
            'email' => 'user@bbs.xyz',
            'password' => Hash::make('BbsUser123')
        ])->roles()->attach($userRoles->where('name', 'user'));

        // Dummy users
        User::factory(50)->create()->each(function ($user) use ($userRoles) {
            $user->roles()->attach($userRoles->where('name', 'user'));
        });
    }
}
