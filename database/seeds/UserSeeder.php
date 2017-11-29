<?php

use App\Domain\UserRole\Facades\UserRoleService;
use App\Helpers\AppMake;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = AppMake::User();
        $admin->name = "Jim Doe";
        $admin->email = "jim@some.com";
        $admin->password = bcrypt("qwerty12345");
        $admin->userRole()->associate(UserRoleService::admin());
        $admin->save();

        $editor = AppMake::User();
        $editor->name = "Jerry Doe";
        $editor->email = "jerry@some.com";
        $editor->password = bcrypt("asdg1245");
        $editor->userRole()->associate(UserRoleService::editor());
        $editor->save();

        $subscriber = AppMake::User();
        $subscriber->name = "John Doe";
        $subscriber->email = "john@some.com";
        $subscriber->password = bcrypt("vvcvzcv5657");
        $subscriber->userRole()->associate(UserRoleService::subscriber());
        $subscriber->save();
    }
}
