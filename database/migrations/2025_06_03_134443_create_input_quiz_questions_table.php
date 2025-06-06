<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('input_quiz_questions', function (Blueprint $table) {
            $table->id('id_input_quiz_question');
            $table->unsignedBigInteger('id_input_quizezz');

            $table->text('question_quiz');
            $table->text('description_question');
            $table->text('option_1');
            $table->text('option_2');
            $table->text('option_3');
            $table->text('option_4');
            $table->integer('question_answer');

            $table->timestamps();

            $table->foreign('id_input_quizezz')
                ->references('id_input_quizezz')
                ->on('input_quizzes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('input_quiz_questions');
    }
};
