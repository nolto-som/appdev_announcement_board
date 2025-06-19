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
        // Optional: update existing values to valid ENUM values
        DB::table('announcements')->whereNotIn('status', ['pending', 'approved', 'archived'])->update(['status' => 'pending']);


        Schema::table('announcements', function (Blueprint $table) {
             $table->enum('status', ['pending', 'approved', 'archived'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }
};
