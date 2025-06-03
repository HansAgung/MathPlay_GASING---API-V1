<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    protected $table = 'user_lesson_history';
    protected $primaryKey = 'id_lesson_history';

    protected $fillable = [
        'id_users',
        'id_learning_subject',
        'status',
    ];
}
