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
        Schema::create('input_quizzes', function (Blueprint $table) {
            $table->id('id_input_quizezz');
            $table->unsignedBigInteger('id_learning_units');
            $table->string('title_question');
            $table->integer('set_time');
            $table->integer('type_assets');
            $table->integer('energy_cost');
            $table->boolean('status')->default(true);
            $table->text('question_quiz');          
            $table->text('description_question');   
            $table->text('option_1');               
            $table->text('option_2');
            $table->text('option_3');
            $table->text('option_4');
            $table->integer('question_answer');     
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
        Schema::dropIfExists('input_quizzes');
    }
};
