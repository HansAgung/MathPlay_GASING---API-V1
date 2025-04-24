<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_users');
            $table->integer('lives')->default(5);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username')->unique();
            $table->string('fullname');
            $table->date('birth');
            $table->enum('gender',['male','female'])->nullable();
            $table->string('character_img')->nullable();
            $table->text('user_desc')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
