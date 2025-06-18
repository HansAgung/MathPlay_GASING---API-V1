<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputQuiz extends Model
{
    protected $table = 'input_quizzes'; 
    protected $primaryKey = 'id_input_quizezz';
    
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

    // Relasi ke LearningUnit
    public function unit() 
    {
        return $this->belongsTo(LearningUnit::class, 'id_learning_units', 'id_learning_units');
    }

    // Relasi ke Badge
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'id_badges', 'id_badges');
    }

    // Relasi ke InputQuizQuestion
    public function questions()
    {
        return $this->hasMany(InputQuizQuestion::class, 'id_input_quizezz', 'id_input_quizezz');
    }

    // Alias untuk questions (jika diperlukan)
    public function questionsQuiz()
    {
        return $this->hasMany(InputQuizQuestion::class, 'id_input_quizezz', 'id_input_quizezz');
    }

    public function learningSubject()
    {
        return $this->belongsTo(LearningSubject::class, 'id_learning_units', 'id_learning_subjects');
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