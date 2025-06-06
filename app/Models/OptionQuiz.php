<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionQuiz extends Model
{
    protected $primaryKey = 'id_option_quizezz';
    protected $fillable = [
        'id_learning_units',
        'title_question', 
        'set_time', 
        'type_assets',
        'test_type',
        'energy_cost', 
        'status', 
    ];

    public function unit()
    {
        return $this->belongsTo(LearningUnit::class, 'id_learning_units');
    }

    public function questionsQuiz()
    {
        return $this->hasMany(OptionQuizQuestion::class, 'id_option_quizezz', 'id_option_quizezz');
    }

    public function learningSubject()
    {
        return $this->belongsTo(LearningSubject::class, 'id_learning_units', 'id_learning_subjects');
    }
}

