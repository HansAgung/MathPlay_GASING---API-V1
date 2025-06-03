<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_modules', function (Blueprint $table) {
            $table->id('id_learning_modules');
            $table->unsignedBigInteger('id_learning_subjects');
            $table->string('title_modules');
            $table->text('description_modules');
            $table->timestamps();

            $table->foreign('id_learning_subjects')
                ->references('id_learning_subjects')
                ->on('learning_subject')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_modules');
    }
};
