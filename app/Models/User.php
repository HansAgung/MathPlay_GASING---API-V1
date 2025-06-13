<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_users';
    public $incrementing = true;
    protected $keyType = 'int';

    public function getAuthIdentifierName()
    {
        return 'id_users';
    }

    protected $fillable = [
        'lives',
        'email',
        'password',
        'username',
        'fullname',
        'birth',
        'gender',
        'character_img',
        'user_desc',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function lessonHistories()
    {
        return $this->hasMany(UserLessonHistory::class, 'id_users');
    }

    public function moduleHistories()
    {
        return $this->hasMany(UserModuleHistory::class, 'id_users', 'id_users');
    }
}
