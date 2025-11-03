<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Amankan User default menggunakan firstOrCreate()
        // Ini memastikan user 'test@example.com' HANYA dibuat sekali.
        User::firstOrCreate(
            ['email' => 'test@example.com'], // Kriteria untuk mencari
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ] // Data yang akan dibuat jika tidak ditemukan
        );

        // 2. Panggil StaffSeeder yang berisi semua data user queue Anda
        $this->call([
            StaffSeeder::class, 
            // Tambahkan seeder lain di sini (misalnya ServiceSeeder::class)
        ]);
    }
}
