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
        Schema::create('option_quiz_questions', function (Blueprint $table) {
            $table->id('id_option_quiz_question');
            $table->unsignedBigInteger('id_option_quizezz');

            $table->text('question_quiz');
            $table->text('description_question');
            $table->text('option_1');
            $table->text('option_2');
            $table->text('option_3');
            $table->text('option_4');
            $table->integer('question_answer');

            $table->timestamps();

            $table->foreign('id_option_quizezz')
                ->references('id_option_quizezz')
                ->on('option_quizzes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_quiz_questions');
    }
};
