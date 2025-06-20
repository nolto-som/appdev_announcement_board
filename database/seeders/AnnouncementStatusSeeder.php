<?php

namespace Database\Seeders;

use App\Models\AnnouncementStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AnnouncementStatus::insert([
            ['name' => 'published'],
            ['name' => 'archived'],
        ]);
    }
}
