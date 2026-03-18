<?php

namespace App\Models;

use App\Enums\AdminRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use HasFactory;
    protected $table = 'admin_users';

    protected $fillable = ['login_id', 'name', 'password', 'role', 'is_active'];

    protected $hidden = ['password'];

    protected $casts = [
        'password'  => 'hashed',
        'role'      => AdminRole::class,
        'is_active' => 'boolean',
    ];
}
