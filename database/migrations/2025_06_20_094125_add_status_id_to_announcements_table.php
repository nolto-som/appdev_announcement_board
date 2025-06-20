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
            $table->unsignedBigInteger('announcementStatus_id')->default(1)->after('content'); // 1 = published
            $table->foreign('announcementStatus_id')->references('id')->on('announcement_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['announcementStatus_id']);
            $table->dropColumn('announcementStatus_id');
        });
    }
};
