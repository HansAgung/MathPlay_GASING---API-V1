<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->enum('test_type', ['pre', 'post'])->after('type_assets')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('input_quizzes', function (Blueprint $table) {
            $table->dropColumn('test_type');
        });
    }
};
