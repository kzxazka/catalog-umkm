<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed Superadmin Dinas Perdagangan
        User::updateOrCreate(
            ['email' => 'perdaganganbl@gmail.com'],
            [
                'name' => 'Dinas Perdagangan',
                'password' => \Illuminate\Support\Facades\Hash::make('@Herl1n4PW'),
                'role' => 'admin',
            ]
        );
    }
}
