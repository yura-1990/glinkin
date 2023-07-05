<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Userdata\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->count() == 0) {
            $role = Role::query()->where('name', 'admin')->firstOrFail();

            User::query()->create([
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'phone'          => fake()->e164PhoneNumber(),
                'password'       => bcrypt('password'),
                'remember_token' => Str::random(60),
                'role_id'        => $role->id,
            ]);
        }
    }
}
