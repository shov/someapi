<?php declare(strict_types=1);

namespace App\Domain\UserRole;

use App\Helpers\AppMake;

class UserRoleService
{
    /**
     * Right way to get user role by name
     * @param string $name
     * @return UserRole
     * @throws \InvalidArgumentException
     */
    public function getUserRole(string $name): UserRole
    {
        /** @var UserRole $theRole */
        $theRole = AppMake::UserRole()
            ->newQuery()
            ->where('name', $name)
            ->first();

        if (is_null($theRole)) {
            throw new \InvalidArgumentException(
                sprintf("Wrong user's role name! \"%s\" given.", $name));
        }

        return $theRole;
    }

    /**
     * Get admin role
     * @return UserRole
     */
    public function admin(): UserRole
    {
        return $this->getUserRole(UserRole::ROLES['ADMIN']);
    }

    /**
     * Get editor role
     * @return UserRole
     */
    public function editor(): UserRole
    {
        return $this->getUserRole(UserRole::ROLES['EDITOR']);
    }

    /**
     * Get subscriber role
     * @return UserRole
     */
    public function subscriber(): UserRole
    {
        return $this->getUserRole(UserRole::ROLES['SUBSCRIBER']);
    }
}