<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('input_quiz_questions', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('input_quiz_questions', 'question_quiz')) {
                $table->dropColumn('question_quiz');
            }
            if (Schema::hasColumn('input_quiz_questions', 'option_1')) {
                $table->dropColumn(['option_1', 'option_2', 'option_3', 'option_4']);
            }

            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('input_quiz_questions', 'type_question')) {
                $table->enum('type_question', ['text', 'image'])->after('id_input_quizezz');
            }

            if (!Schema::hasColumn('input_quiz_questions', 'description_question')) {
                $table->text('description_question')->nullable()->after('type_question');
            }

            if (!Schema::hasColumn('input_quiz_questions', 'input_guideline')) {
                $table->json('input_guideline')->after('description_question');
            }
        });
    }

    public function down()
    {
        Schema::table('input_quiz_questions', function (Blueprint $table) {
            $table->dropColumn(['type_question', 'description_question', 'input_guideline']);

            // (Opsional) Tambahkan kembali kolom lama saat rollback
            $table->text('question_quiz')->nullable();
            $table->string('option_1')->nullable();
            $table->string('option_2')->nullable();
            $table->string('option_3')->nullable();
            $table->string('option_4')->nullable();
        });
    }
};
