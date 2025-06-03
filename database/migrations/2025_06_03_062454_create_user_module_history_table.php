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
        Schema::create('user_module_history', function (Blueprint $table) {
            $table->id('id_module_history'); // Primary Key

            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_learning_modules'); 
            $table->enum('status', ['complete', 'uncomplete'])->default('uncomplete');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
            $table->foreign('id_learning_modules')->references('id_learning_modules')->on('learning_modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_module_history');
    }
};
