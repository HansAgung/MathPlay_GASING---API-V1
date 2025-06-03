<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_subject', function (Blueprint $table) {
            $table->id('id_learning_subjects');
            $table->unsignedBigInteger('id_admins');
            $table->string('title_learning_subject');
            $table->string('descripsion_learning_subject');
            $table->string('img_card_subject');
            $table->timestamps();

            $table->foreign('id_admins')->references('id_admins')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_subject');
    }
};
