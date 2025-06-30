<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDefaultTypeAssetsOnInputAndOptionQuizzes extends Migration
{
    public function up()
    {
        // Set default '0' untuk input_quizzes
        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->default('0')->change();
        });

        // Set default '1' untuk option_quizzes
        Schema::table('option_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->default('1')->change();
        });
    }

    public function down()
    {
        // Rollback ke nullable tanpa default
        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->nullable()->default(null)->change();
        });

        Schema::table('option_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->nullable()->default(null)->change();
        });
    }
}
