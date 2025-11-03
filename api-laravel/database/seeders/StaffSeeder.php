<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Flag untuk testing cepat.
     * Jika true → staff langsung assign loket 1,2,3.
     * Jika false → staff biasa counter_number = null (harus pilih saat login)
     */
    protected bool $assignCounter = false;

    public function run(): void
    {
        $staffData = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@counter.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => false,
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@counter.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => false,
            ],
            [
                'name' => 'Ahmad Dahlan',
                'email' => 'ahmad@counter.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => false,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@counter.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => false,
            ],
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi@counter.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => false,
            ],
            // Admin
            [
                'name' => 'Admin User',
                'email' => 'admin@counter.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ],
        ];

        foreach ($staffData as $index => $staff) {
            // Tentukan counter_number jika assignCounter = true dan bukan admin
            if ($this->assignCounter && $staff['role'] !== 'admin') {
                $staff['counter_number'] = $index + 1; // Loket 1,2,3,...
            } else {
                $staff['counter_number'] = null;
            }

            Staff::updateOrCreate(
                ['email' => $staff['email']],
                $staff
            );
        }
    }
}
