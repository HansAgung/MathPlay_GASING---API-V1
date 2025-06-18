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
            $table->dropForeign(['id_user_history']);
            $table->dropColumn('id_user_history');
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user_history')->nullable();

            $table->foreign('id_user_history')
                ->references('id_user_history')
                ->on('user_units_history')
                ->onDelete('cascade');
        });
    }
};
