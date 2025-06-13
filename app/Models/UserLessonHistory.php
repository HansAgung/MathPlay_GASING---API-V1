<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLessonHistory extends Model
{
    protected $table = 'user_lesson_history'; 
    protected $primaryKey = 'id_lesson_history'; 

    public $timestamps = false; 

    protected $fillable = [
        'id_users',
        'id_learning_subjects',
        'status',
        'created_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    // Relasi ke LearningSubject
    public function subject()
    {
        return $this->belongsTo(LearningSubject::class, 'id_learning_subjects', 'id_learning_subjects');
    }
}
