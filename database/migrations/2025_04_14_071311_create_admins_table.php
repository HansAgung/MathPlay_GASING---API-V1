<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('id_admins');
            $table->boolean('is_approved')->default(false);
            $table->enum('role_admins', ['0', '1'])->default('0');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username')->unique();
            $table->string('profile_img')->nullable();
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
