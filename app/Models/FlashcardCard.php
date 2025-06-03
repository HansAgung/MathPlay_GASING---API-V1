<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardCard extends Model
{
    protected $primaryKey = 'id_cards';
    public $timestamps = false;

    protected $fillable = [
        'id_flashcard_game', 'img_cards', 'created_at'
    ];

    public function game()
    {
        return $this->belongsTo(FlashcardGame::class, 'id_flashcard_game');
    }
}

