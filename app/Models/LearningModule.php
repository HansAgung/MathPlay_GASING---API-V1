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

    public function userModuleHistories()
    {
        return $this->hasMany(UserModuleHistory::class, 'id_learning_modules', 'id_learning_modules');
    }


    // public function subject()
    // {
    //     return $this->belongsTo(LearningSubject::class, 'id_learning_subjects', 'id_learning_subjects');
    // }
}
