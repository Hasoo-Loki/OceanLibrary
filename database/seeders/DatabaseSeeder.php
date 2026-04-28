<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN UTAMA
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'kelas' => '-',
            'nis' => '-',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        // USER BIASA (optional)
        User::create([
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'kelas' => 'XII RPL',
            'nis' => '12345',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);
    }
}