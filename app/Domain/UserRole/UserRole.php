<?php

namespace App\Domain\UserRole;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    const ROLES = [
        'ADMIN' => 'admin',
        'EDITOR' => 'editor',
        'SUBSCRIBER' => 'subscriber',
    ];

    public $timestamps = false;
}
