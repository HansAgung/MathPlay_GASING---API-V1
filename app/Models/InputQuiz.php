<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputQuiz extends Model
{
    protected $primaryKey = 'id_input_quizezz';
    protected $fillable = [
        'id_learning_units', 'title_question', 'set_time', 'type_assets',
        'energy_cost', 'status', 'question_quiz', 'description_question',
        'option_1', 'option_2', 'option_3', 'option_4', 'question_answer'
    ];

    public function unit()
    {
        return $this->belongsTo(LearningUnit::class, 'id_learning_units');
    }
}

