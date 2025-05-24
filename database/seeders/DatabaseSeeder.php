<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat admin default
        User::factory()->create([
            'no_telp' => '081234567890',
            'password' => bcrypt('password'),
            'jenjang_sekolah' => 'SMA'
        ]);

        User::factory()->create([
            'no_telp' => '081234567891',
            'password' => bcrypt('password'),
            'jenjang_sekolah' => 'SMP'
        ]);

        // Membuat beberapa user dummy
        // User::factory(5)->create();
    }
}
