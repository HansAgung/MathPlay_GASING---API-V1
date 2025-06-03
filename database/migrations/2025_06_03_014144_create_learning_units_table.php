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
        Schema::create('learning_units', function (Blueprint $table) {
            $table->id('id_learning_units');
            $table->unsignedBigInteger('id_learning_modules');
            $table->string('title_learning_unit');
            $table->text('description_unit')->nullable();
            $table->integer('unit_learning_order');
            $table->timestamps();

            $table->foreign('id_learning_modules')
                ->references('id_learning_modules')
                ->on('learning_modules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_units');
    }
};
