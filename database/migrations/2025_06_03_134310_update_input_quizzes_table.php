<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'question_quiz',
                'description_question',
                'option_1',
                'option_2',
                'option_3',
                'option_4',
                'question_answer',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->text('question_quiz');
            $table->text('description_question');
            $table->text('option_1');
            $table->text('option_2');
            $table->text('option_3');
            $table->text('option_4');
            $table->integer('question_answer');
        });
    }
};
