<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flashcard_cards', function (Blueprint $table) {
            $table->id('id_cards');
            $table->unsignedBigInteger('id_flashcard_game');
            $table->string('img_cards');
            $table->timestamps();

            $table->foreign('id_flashcard_game')
                ->references('id_flashcard_game')
                ->on('flashcard_minigame')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_cards');
    }
};
