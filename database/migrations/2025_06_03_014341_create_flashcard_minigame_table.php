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
        Schema::create('flashcard_minigame', function (Blueprint $table) {
            $table->id('id_flashcard_game');
            $table->unsignedBigInteger('id_learning_units');
            $table->integer('patternCount')->default(0);
            $table->integer('matchCount')->default(0);
            $table->integer('cards')->default(0);
            $table->integer('set_time')->nullable();
            $table->timestamps();

            $table->foreign('id_learning_units')
                ->references('id_learning_units')
                ->on('learning_units')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_minigame');
    }
};
