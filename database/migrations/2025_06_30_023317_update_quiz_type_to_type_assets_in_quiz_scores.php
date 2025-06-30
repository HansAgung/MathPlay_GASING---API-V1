<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateQuizTypeToTypeAssetsInQuizScores extends Migration
{
    public function up()
    {
        // Tambah kolom baru type_assets
        Schema::table('quiz_scores', function (Blueprint $table) {
            $table->tinyInteger('type_assets')->nullable()->after('quiz_id')->comment('0: input, 1: option');
        });

        // Salin nilai dari quiz_type ke type_assets
        DB::table('quiz_scores')->update([
            'type_assets' => DB::raw("CASE 
                WHEN quiz_type = 'input' THEN 0
                WHEN quiz_type = 'option' THEN 1
                ELSE NULL
            END")
        ]);

        // Hapus kolom quiz_type
        Schema::table('quiz_scores', function (Blueprint $table) {
            $table->dropColumn('quiz_type');
        });
    }

    public function down()
    {
        // Tambah kembali kolom quiz_type
        Schema::table('quiz_scores', function (Blueprint $table) {
            $table->string('quiz_type')->nullable()->after('quiz_id');
        });

        // Kembalikan nilai dari type_assets ke quiz_type
        DB::table('quiz_scores')->update([
            'quiz_type' => DB::raw("CASE 
                WHEN type_assets = 0 THEN 'input'
                WHEN type_assets = 1 THEN 'option'
                ELSE NULL
            END")
        ]);

        // Hapus kolom type_assets
        Schema::table('quiz_scores', function (Blueprint $table) {
            $table->dropColumn('type_assets');
        });
    }
}
