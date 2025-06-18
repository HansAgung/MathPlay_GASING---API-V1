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
        Schema::table('learning_units', function (Blueprint $table) {
            $table->dropColumn('title_learning_unit');
            $table->dropColumn('description_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_units', function (Blueprint $table) {
            $table->string('title_learning_unit', 255)->nullable();
            $table->text('description_unit')->nullable();
        });
    }
};
