<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningUnit extends Model
{
    protected $primaryKey = 'id_learning_units';
    protected $fillable = [
        'id_learning_modules',
        'unit_learning_order'
    ];

    public function inputQuizzes()
    {
        return $this->hasMany(InputQuiz::class, 'id_learning_units', 'id_learning_units');
    }

    public function optionQuizzes()
    {
        return $this->hasMany(OptionQuiz::class, 'id_learning_units', 'id_learning_units');
    }

    public function videoLessons()
    {
        return $this->hasMany(VideoLesson::class, 'id_learning_units', 'id_learning_units');
    }

    public function flashcardGames()
    {
        return $this->hasMany(FlashcardGame::class, 'id_learning_units', 'id_learning_units');
    }
}
