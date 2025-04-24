<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'badges';
    protected $primaryKey = 'id_badges';

    protected $fillable = [
        'title_badges',
        'badges_desc',
        'badges_img',
    ];
}

