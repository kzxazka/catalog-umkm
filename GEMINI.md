# Project Context: Catalog UMKM (E-Commerce Multi-Tenant)

## Project Overview
Website katalog belanja fashion dan UMKM di bawah naungan Dinas Perdagangan. Sistem ini memungkinkan UMKM yang terverifikasi untuk memiliki "toko mandiri" di dalam platform utama.

## Tech Stack
- **Framework:** Laravel 11+
- **Database:** MongoDB (Primary Database)
- **Frontend:** Tailwind CSS, Blade, Swiper JS (untuk Product Slider)
- **Backend Packages:** `mongodb/laravel-mongodb`
- **Design Inspiration:** Nike.com (High-impact visuals, bold typography, clean UI)

## Business Logic & Flow
1. **Admin Dinas (Superadmin):** - Memverifikasi UMKM berdasarkan sertifikat bisnis.
   - Membuatkan akun awal untuk UMKM.
2. **UMKM (Tenant/Store Owner):**
   - Mengelola dashboard toko sendiri.
   - CRUD Produk (Foto slider, Nama, Merk, Deskripsi Singkat).
   - Mengelola tautan eksternal (WA, IG, Marketplace).
3. **Public (Visitors):**
   - Menjelajah katalog berdasarkan kategori.
   - Akses langsung ke landing page spesifik UMKM (misal: `/store/{slug}`).

## Database Structure (MongoDB Collections)
- `users`: (Admin & UMKM Owners)
- `stores`: (Slug, Name, Description, Social Links, Owner ID)
- `products`: (Store ID, Name, Brand, Description, Category, Image Array)
- `categories`: (Name, Slug)

## Development Rules
- Utamakan **Responsive Design** (Mobile-first).
- Pastikan isolasi data antar tenant (UMKM A tidak bisa edit produk UMKM B).
- Gunakan Eloquent khusus MongoDB: `MongoDB\Laravel\Eloquent\Model`.