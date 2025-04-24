<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'id_admins';

    protected $fillable = [
        'is_approved',
        'role_admins',
        'email',
        'password',
        'username',
        'profile_img',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
