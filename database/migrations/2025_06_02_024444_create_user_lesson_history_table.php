<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_lesson_history', function (Blueprint $table) {
            $table->id('id_lesson_history');
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_learning_subjects');
            $table->enum('status', ['toDo','onProgress','complete']);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('id_learning_subjects')->references('id_learning_subjects')->on('learning_subject')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_lesson_history');
    }
};
