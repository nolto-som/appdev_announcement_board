<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnnouncementStatus extends Model
{
    public function up(): void
    {
        Schema::create('announcement_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. published, draft, archived
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_statuses');
    }
}
