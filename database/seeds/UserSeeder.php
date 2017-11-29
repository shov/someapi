<?php

use App\Domain\UserRole\Facades\UserRoleService;
use App\Helpers\AppMake;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    const USER_SEEDS = [
        'ADMIN' => [
            'name' => "Jim Doe",
            'email' => "jim@some.com",
            'password' => "qwerty12345",
        ],
        'EDITOR' => [
            'name' => "Jerry Doe",
            'email' => "jerry@some.com",
            'password' => "24vfdb3bGG",
        ],
        'SUBSCRIBER' => [
            'name' => "John Doe",
            'email' => "john@some.com",
            'password' => "jhgcujc42624",
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = AppMake::User();
        $admin->name = self::USER_SEEDS['ADMIN']['name'];
        $admin->email = self::USER_SEEDS['ADMIN']['email'];
        $admin->password = bcrypt(self::USER_SEEDS['ADMIN']['password']);
        $admin->userRole()->associate(UserRoleService::admin());
        $admin->save();

        $editor = AppMake::User();
        $editor->name = self::USER_SEEDS['EDITOR']['name'];
        $editor->email = self::USER_SEEDS['EDITOR']['email'];
        $editor->password = bcrypt(self::USER_SEEDS['EDITOR']['password']);
        $editor->userRole()->associate(UserRoleService::editor());
        $editor->save();

        $subscriber = AppMake::User();
        $subscriber->name = self::USER_SEEDS['SUBSCRIBER']['name'];
        $subscriber->email = self::USER_SEEDS['SUBSCRIBER']['email'];
        $subscriber->password = bcrypt(self::USER_SEEDS['SUBSCRIBER']['password']);
        $subscriber->userRole()->associate(UserRoleService::subscriber());
        $subscriber->save();
    }
}
