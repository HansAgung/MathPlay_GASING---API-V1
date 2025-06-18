<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'character_img')) {
                $table->dropColumn('character_img');
            }
            if (Schema::hasColumn('users', 'user_desc')) {
                $table->dropColumn('user_desc');
            }

            $table->unsignedBigInteger('id_user_character')->nullable()->after('gender');
            $table->foreign('id_user_character')
                ->references('id_user_character')
                ->on('user_character')
                ->onDelete('set null');
        });

        Schema::create('quiz_score_pre', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_users');
            $table->string('quiz_id');
            $table->string('quiz_type');
            $table->integer('score');
            $table->timestamps();

            $table->foreign('id_users')
                ->references('id_users')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->default('input')->change();

            $table->unsignedBigInteger('id_badges')->nullable()->after('energy_cost');
            $table->foreign('id_badges')
                ->references('id_badges')
                ->on('badges')
                ->onDelete('set null');

            $table->json('reward')->nullable()->after('id_badges');
        });

        Schema::table('option_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->default('option')->change();

            $table->unsignedBigInteger('id_badges')->nullable()->after('energy_cost');
            $table->foreign('id_badges')
                ->references('id_badges')
                ->on('badges')
                ->onDelete('set null');

            $table->json('reward')->nullable()->after('id_badges');
        });

        Schema::table('badges', function (Blueprint $table) {
            $table->enum('condition', ['0', '1'])->default('0')->after('energy');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_user_character']);
            $table->dropColumn('id_user_character');

            $table->string('character_img')->nullable();
            $table->string('user_desc')->nullable();
        });

        Schema::dropIfExists('quiz_score_pre');

        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->change();
            $table->dropForeign(['id_badges']);
            $table->dropColumn(['id_badges', 'reward']);
        });

        Schema::table('option_quizzes', function (Blueprint $table) {
            $table->string('type_assets')->change(); 
            $table->dropForeign(['id_badges']);
            $table->dropColumn(['id_badges', 'reward']);
        });

        Schema::table('badges', function (Blueprint $table) {
            $table->dropColumn('condition');
        });
    }
};