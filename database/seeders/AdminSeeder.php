<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin123@gmail.com',
                'password' => Hash::make('admin123'), // Change this for production
                'role_id' => 1, // Assuming role_id 1 is for admin
                'status_id' => 1,
            ]);
        }
    }
}
