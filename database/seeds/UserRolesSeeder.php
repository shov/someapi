<?php

use App\AppMake;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesNames = UserRole::ROLES;

        foreach ($rolesNames as $curRole) {
            $userRole = AppMake::UserRole();
            $userRole->name = $curRole;
            $userRole->save();
            $userRole = null;
        }
    }
}
