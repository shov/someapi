<?php

use App\Domain\UserRole\UserRole;
use App\Helpers\AppMake;
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
