<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionQuizQuestion extends Model
{
   use HasFactory;

    protected $table = 'option_quiz_questions';
    protected $primaryKey = 'id_option_quiz_question';

    protected $fillable = [
        'id_option_quizezz',
        'question_quiz',
        'description_question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'question_answer',
    ];

    public function optionQuiz()
    {
        return $this->belongsTo(OptionQuiz::class, 'id_option_quizezz', 'id_option_quizezz');
    }
}
