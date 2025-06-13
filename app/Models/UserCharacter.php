<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCharacter extends Model
{
    protected $table = 'user_character';
    protected $primaryKey = 'id_user_character';
    public $timestamps = false; // karena hanya ada created_at

    protected $fillable = [
        'img_character',
        'description',
        'created_at',
    ];
}
