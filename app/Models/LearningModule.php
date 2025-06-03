<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningModule extends Model
{
    use HasFactory;

    protected $table = 'learning_modules';

    protected $primaryKey = 'id_learning_modules';

    protected $fillable = [
        'id_learning_subjects',
        'title_modules',
        'description_modules',
    ];
}
