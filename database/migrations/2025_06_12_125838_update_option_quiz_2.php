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
        Schema::table('option_quizzes',function(Blueprint $table){
            $table->dropForeign(['id_learning_units']);
            $table->dropColumn('id_learning_units');
            $table->dropColumn('status');

            $table->unsignedBigInteger('id_user_history')->after('id_option_quizezz');

            $table->foreign('id_user_history')
                ->references('id_user_history')
                ->on('user_units_history')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('option_quizzes', function (Blueprint $table) {
            $table->dropForeign(['id_user_history']);
            $table->dropColumn('id_user_history');
            $table->dropColumn('status');

            $table->unsignedBigInteger('id_learning_units')->after('id_option_quizezz');

            $table->foreign('id_learning_units')
                  ->references('id_learning_units')
                  ->on('learning_units')
                  ->onDelete('cascade');
        });
    }
};
