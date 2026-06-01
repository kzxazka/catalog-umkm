<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        User::truncate();
        Store::truncate();
        Product::truncate();

        // 1. Buat Akun Owner UMKM
        $user = User::create([
            'name' => 'Gatot Kartiko',
            'email' => 'owner@jagatboemi.com',
            'password' => Hash::make('password123'),
            'role' => 'umkm',
        ]);

        // 1.5. Buat Akun Superadmin Dinas
        User::create([
            'name' => 'Admin Dinas Perdagangan',
            'email' => 'admin@dinas.go.id',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);


        // 2. Buat Profil Toko (Jagatboemi)
        $store = Store::create([
            'user_id' => $user->id,
            'name' => 'Jagatboemi',
            'slug' => 'jagat-boemi',
            'description' => "Batik Gabovira secara resmi didirikan pada 25 Februari 2000.",
            'category' => 'Fashion',
            'nib' => '9120381029381',
            'social_links' => [
                'instagram' => 'https://instagram.com/jagat.boemi',
                'whatsapp' => '628123456789'
            ]
        ]);

        // 3. Buat Sample Produk
        Product::create([
            'store_id' => $store->id,
            'name' => 'Batik Siger Slim Fit Brown',
            'category' => 'Koleksi Pria',
            'description' => 'Kemeja batik tulis kombinasi cap dengan motif Siger Lampung yang elegan.',
            'price' => 350000,
            'images' => ['sample-pria.webp'],
            'links' => [
                ['platform' => 'WhatsApp', 'url' => 'https://wa.me/', 'color' => '#25D366'],
                ['platform' => 'Shopee', 'url' => 'https://shopee.co.id/', 'color' => '#EE4D2D']
            ]
        ]);

        // 4. Buat UMKM Dummy tambahan untuk test Filter & Pagination
        $dummyStores = [
            ['name' => 'Kopi Kenangan Senja', 'slug' => 'kopi-kenangan-senja', 'category' => 'Food & Beverage', 'nib' => '8273910283712'],
            ['name' => 'Kriya Rotan Nusantara', 'slug' => 'kriya-rotan', 'category' => 'Other', 'nib' => '1029384756123'],
            ['name' => 'Servis AC Mandiri', 'slug' => 'servis-ac', 'category' => 'Other', 'nib' => ''],
            ['name' => 'Pempek Cek Lina', 'slug' => 'pempek-cek-lina', 'category' => 'Food & Beverage', 'nib' => '5647382910394'],
            ['name' => 'Kaos Polos Lampung', 'slug' => 'kaos-polos-lampung', 'category' => 'Fashion', 'nib' => '9081726354123'],
        ];

        foreach ($dummyStores as $ds) {
            Store::create([
                'user_id' => $user->id, // Dummy aja pakai user yg sama
                'name' => $ds['name'],
                'slug' => $ds['slug'],
                'category' => $ds['category'],
                'nib' => $ds['nib'],
                'description' => 'Deskripsi untuk ' . $ds['name'],
            ]);
        }
    }
}
