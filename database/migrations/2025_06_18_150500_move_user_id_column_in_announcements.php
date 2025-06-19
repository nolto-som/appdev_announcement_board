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
        Schema::table('announcements', function (Blueprint $table) {
             // Move user_id column after status and made it not nullable
            DB::statement("ALTER TABLE announcements MODIFY user_id BIGINT UNSIGNED NOT NULL AFTER status");
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
             // Move it back to the end (optional — not always necessary)
            DB::statement("ALTER TABLE announcements MODIFY user_id BIGINT UNSIGNED NOT NULL");
        });
    }
};
