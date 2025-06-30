<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAssetsToVideoLessonsAndFlashcardMinigame extends Migration
{
    public function up()
    {
        Schema::table('video_lessons', function (Blueprint $table) {
            $table->string('type_assets')->default('2')->after('description_contents');
        });

        Schema::table('flashcard_minigame', function (Blueprint $table) {
            $table->string('type_assets')->default('3')->after('set_time');
        });
    }

    public function down()
    {
        Schema::table('video_lessons', function (Blueprint $table) {
            $table->dropColumn('type_assets');
        });

        Schema::table('flashcard_minigame', function (Blueprint $table) {
            $table->dropColumn('type_assets');
        });
    }
}
