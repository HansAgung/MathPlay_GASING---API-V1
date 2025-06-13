<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum lama menjadi enum baru
        DB::statement("ALTER TABLE user_module_history MODIFY status ENUM('toDo', 'onProgress', 'complete') DEFAULT 'toDo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke enum sebelumnya jika dibutuhkan
        DB::statement("ALTER TABLE user_module_history MODIFY status ENUM('complete', 'uncomplete') DEFAULT 'uncomplete'");
    }
};

