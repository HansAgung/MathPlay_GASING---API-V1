<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLesson extends Model
{
    protected $primaryKey = 'id_video_lessons';
    protected $fillable = [
        'id_learning_units', 
        'title_lessons', 
        'video_url_lessons', 
        'description_contents'
    ];

    public function unit()
    {
        return $this->belongsTo(LearningUnit::class, 'id_learning_units');
    }

    public function contents()
    {
        // return $this->hasMany(VideoLessonContent::class, 'id_video_lessons')->orderBy('order');
        return $this->hasMany(VideoLessonContent::class, 'id_video_lessons');
    }

}

