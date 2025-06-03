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
        Schema::create('video_lessons', function (Blueprint $table) {
            $table->id('id_video_lessons');
            $table->unsignedBigInteger('id_learning_units');
            $table->string('title_lessons');
            $table->string('video_url_lessons');
            $table->text('description_contents')->nullable();
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
        Schema::dropIfExists('video_lesson');
    }
};
