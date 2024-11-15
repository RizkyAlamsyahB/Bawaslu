<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'id' => (string) Str::uuid(), // Gunakan UUID untuk ID
            'name' => 'Kota',
            'phone' => '1234567890', // Nomor telepon (sesuaikan)
            'username' => 'kota', // Username untuk super admin
            'password' => Hash::make('password123'), // Password yang terenkripsi
            'role' => 'kota', // Role user
        ]
    );
    }
}
