<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputQuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'input_quiz_questions';
    protected $primaryKey = 'id_input_quiz_question';

    protected $fillable = [
        'id_input_quizezz',
        'question_quiz',
        'description_question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'question_answer',
    ];

    public function inputQuiz()
    {
        return $this->belongsTo(InputQuiz::class, 'id_input_quizezz', 'id_input_quizezz');
    }
}
