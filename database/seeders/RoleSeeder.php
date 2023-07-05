<?php

namespace Database\Seeders;

use App\Enums\UserRoleTypeEnum;
use App\Models\Userdata\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => UserRoleTypeEnum::ADMIN->name ],
            ['name' => UserRoleTypeEnum::EDITOR->name ],
            ['name' => UserRoleTypeEnum::USER->name ]
        ];

        Role::query()->insert($roles);
    }
}
