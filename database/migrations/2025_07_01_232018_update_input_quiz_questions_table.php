<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInputQuizQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('input_quiz_questions', function (Blueprint $table) {
            // Drop kolom lama
            if (Schema::hasColumn('input_quiz_questions', 'question_quiz')) {
                $table->dropColumn('question_quiz');
            }

            // Tambah kolom type_question
            if (!Schema::hasColumn('input_quiz_questions', 'type_question')) {
                $table->enum('type_question', ['text', 'image'])->after('id_input_quizezz')->default('text');
            }

            // Ubah kolom option_1 s.d option_4 jadi JSON
            foreach (['option_1', 'option_2', 'option_3', 'option_4'] as $option) {
                if (Schema::hasColumn('input_quiz_questions', $option)) {
                    $table->json($option)->change();
                } else {
                    $table->json($option)->nullable();
                }
            }

            // Ubah description_question jadi nullable text (untuk soal image bisa berupa URL)
            if (Schema::hasColumn('input_quiz_questions', 'description_question')) {
                $table->text('description_question')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('input_quiz_questions', function (Blueprint $table) {
            // Rollback perubahan
            $table->dropColumn('type_question');

            // Ubah kembali ke string biasa jika diperlukan (optional)
            foreach (['option_1', 'option_2', 'option_3', 'option_4'] as $option) {
                $table->string($option, 255)->nullable()->change();
            }

            $table->string('question_quiz')->nullable();
        });
    }
}
