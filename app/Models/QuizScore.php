<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizScore extends Model
{
    use HasFactory;

    protected $table = 'quiz_scores';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_users',
        'quiz_id',
        'type_assets',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inputQuiz()
    {
        return $this->belongsTo(InputQuiz::class, 'quiz_id', 'id_input_quizezz');
    }

    public function optionQuiz()
    {
        return $this->belongsTo(OptionQuiz::class, 'quiz_id', 'id_option_quizezz');
    }
}
