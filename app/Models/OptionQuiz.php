<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionQuiz extends Model
{
    protected $table = 'option_quizzes'; 
    protected $primaryKey = 'id_option_quizezz';
    protected $fillable = [
         'id_learning_units', 
        'title_question', 
        'set_time', 
        'type_assets',
        'test_type',
        'energy_cost',
        'id_badges',
        'reward',
    ];

    protected $casts = [
        'set_time' => 'integer',
        'energy_cost' => 'integer',
        'id_badges' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function userUnitHistory()
    {
        return $this->belongsTo(userUnitsHistory::class, 'id_user_history', 'id_user_history');
    }

    // Accessor untuk format reward jika diperlukan
    public function getFormattedRewardAttribute()
    {
        if (is_null($this->reward)) {
            return null;
        }
        
        // Jika reward berupa JSON, decode
        if (is_string($this->reward) && json_decode($this->reward)) {
            return json_decode($this->reward, true);
        }
        
        return $this->reward;
    }

    // Mutator untuk reward jika diperlukan
    public function setRewardAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['reward'] = json_encode($value);
        } else {
            $this->attributes['reward'] = $value;
        }
    }
}

