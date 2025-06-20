<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add foreign key columns
            $table->unsignedBigInteger('role_id')->nullable()->after('email');
            $table->unsignedBigInteger('status_id')->nullable()->after('role_id');

            // Set foreign key constraints
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');

            // Optional: drop old string columns if they exist
            // $table->dropColumn('role');
            // $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['status_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('status_id');

            // Optionally restore dropped columns
            // $table->string('role')->default('user');
            // $table->string('status')->default('active');
        });
    }
};
