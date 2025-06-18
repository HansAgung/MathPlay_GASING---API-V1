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
        Schema::table('video_lessons', function(Blueprint $table){
            $table->unsignedBigInteger('id_learning_units')->after('id_video_lessons');

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
        $table->dropForeign(['id_learning_units']);
        $table->dropColumn('id_learning_units');
    }
};
