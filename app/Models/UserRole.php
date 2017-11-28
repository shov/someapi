<?php

namespace App\Models;

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
