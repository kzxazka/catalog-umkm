<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('deploy:clean-db', function () {
    $this->info('Starting database cleanup for deployment...');

    // Truncate other collections
    $this->info('Truncating stores, products, and mitra_applications...');
    \App\Models\Store::truncate();
    \App\Models\Product::truncate();
    \App\Models\MitraApplication::truncate();

    // Preserve admin and delete others
    $this->info('Cleaning users collection...');

    $admins = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->get();

    if ($admins->isEmpty()) {
        $this->warn('No admin user found! Creating default admin user...');
        \App\Models\User::create([
            'name' => 'Admin Dinas Perdagangan',
            'email' => 'perdaganganbl@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('@Herl1n4PW'),
            'role' => 'admin',
        ]);
        $this->info('Default admin user created: admin@disperdagkota.go.id / password');
    }

    \App\Models\User::whereNotIn('role', ['admin', 'superadmin'])->delete();

    $this->info('Database cleanup completed successfully!');
})->purpose('Clean up database for production deployment preserving only admin accounts');
