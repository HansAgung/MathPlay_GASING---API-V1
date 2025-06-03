<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSubject extends Model
{
    use HasFactory;

    protected $table = 'learning_subject';
    protected $primaryKey = 'id_learning_subjects';

    protected $fillable = [
        'id_admins',
        'title_learning_subject',
        'descripsion_learning_subject',
        'img_card_subject',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admins');
    }

    public function lessonHistories()
    {
        return $this->hasMany(UserLessonHistory::class, 'id_learning_subjects');
    }
}
