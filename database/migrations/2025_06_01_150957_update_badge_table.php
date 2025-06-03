<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('badges', function(Blueprint $table) {
            $table->integer('point')->default(0)->fter('badges_desc');
            $table->integer('energy')->default(0)->after('point');
        });
    }

    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->dropColumn(['point','energy']);
        });
    }
};
