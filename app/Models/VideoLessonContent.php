<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLessonContent extends Model
{
    protected $primaryKey = 'id_video_lesson_contents';
    protected $fillable = [
        'id_video_lessons',
        'title_material',
        'description_material',
        'video_url',
        'material_img_support',
    ];

    public function videoLesson()
    {
        return $this->belongsTo(VideoLesson::class, 'id_video_lessons');
    }
}

