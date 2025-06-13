<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_units_history', function (Blueprint $table) {
            $table->id('id_user_history'); // Primary Key
            $table->unsignedBigInteger('id_users'); // FK ke users
            $table->unsignedBigInteger('id_learning_units'); // FK ke learning_units
            $table->enum('status', ['toDo', 'onProgress', 'complete'])->default('toDo');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('id_learning_units')->references('id_learning_units')->on('learning_units')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_units_history');
    }
};
