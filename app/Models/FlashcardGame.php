<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardGame extends Model
{
    protected $primaryKey = 'id_flashcard_game';
    protected $table = 'flashcard_minigame';
    protected $fillable = [
        'id_learning_units', 'patternCount', 'matchCount', 'cards', 'set_time','type_assets'
    ];

    public function unit()
    {
        return $this->belongsTo(LearningUnit::class, 'id_learning_units');
    }

    public function cards()
    {
        return $this->hasMany(FlashcardCard::class, 'id_flashcard_game');
    }
}

