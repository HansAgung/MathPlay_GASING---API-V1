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
        Schema::create('video_lesson_contents', function (Blueprint $table) {
            $table->id('id_video_lesson_contents');
            $table->unsignedBigInteger('id_video_lessons');
            $table->string('title_material')->nullable();       // Judul konten
            $table->text('description_material')->nullable();   // Penjelasan konten
            $table->string('video_url')->nullable();            // Video opsional
            $table->string('material_img_support')->nullable(); // Gambar pendukung opsional
            $table->integer('order')->default(0);               // Untuk urutan tampil
            $table->timestamps();

            $table->foreign('id_video_lessons')
                ->references('id_video_lessons')
                ->on('video_lessons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_lesson_contents');
    }
};
