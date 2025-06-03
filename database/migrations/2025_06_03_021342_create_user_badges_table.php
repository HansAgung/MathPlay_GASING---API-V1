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
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id('id_user_badges'); 
            $table->unsignedBigInteger('id_badges'); 
            $table->unsignedBigInteger('id_users');  
            $table->timestamp('earned_at');          

            $table->timestamps();

            $table->foreign('id_badges')
                ->references('id_badges')
                ->on('badges')
                ->onDelete('cascade');

            $table->foreign('id_users')
                ->references('id_users')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
