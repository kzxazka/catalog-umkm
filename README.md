# 🏬 Portal UMKM Indonesia (Katalog & Showcase)

Sistem informasi dan katalog etalase digital untuk UMKM (Usaha Mikro Kecil Menengah) di bawah naungan Dinas Perdagangan. Aplikasi ini mengusung arsitektur **Multi-Tenant** di mana UMKM yang lolos verifikasi dapat memiliki "Toko Mandiri" (*storefront*) untuk mempromosikan produk mereka.

Platform ini berfokus sebagai batu loncatan (*Showcase/Catalog Platform*) untuk meningkatkan *traffic* pembeli ke kanal penjualan utama masing-masing UMKM (seperti WhatsApp, Shopee, Tokopedia, dan TikTok Shop).

---

## 🚀 Fitur Utama

### 🛡️ Admin Dinas (Superadmin)
- **Verifikasi UMKM**: Menyetujui atau menolak pendaftaran UMKM berdasarkan legalitas (NIB).
- **SME Database**: Daftar lengkap seluruh UMKM terdaftar dilengkapi fitur *search*, filter *real-time* dengan Alpine.js, dan fitur *Export to CSV*.
- **Laporan Wilayah**: Pemantauan statistik UMKM per wilayah dan fitur cetak mandiri.

### 🏪 UMKM (Store Owner / Tenant)
- **Dashboard Ringkasan**: Memantau statistik dasar jumlah produk.
- **Manajemen Produk**: Sistem kelola etalase (Tambah, Edit, Hapus) menggunakan modal Alpine.js modern.
- **Tautan Eksternal**: Integrasi langsung dengan berbagai *channel* seperti WhatsApp, Instagram, Shopee, Tokopedia, dan TikTok.
- **Pengaturan Profil Toko**: Mengelola Nama, Kategori, Deskripsi, dan Logo Toko (NIB terenkripsi dan bersifat *read-only* jika terverifikasi).

### 👥 Publik (Pengunjung)
- **Katalog UMKM**: Penjelajahan daftar toko UMKM terverifikasi.
- **Etalase Toko**: Halaman *landing page* spesifik untuk setiap toko (contoh: `/store/jagat-boemi`) yang menampilkan deretan produk dan link langsung ke *marketplace*.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11+ (Breeze Authentication)
- **Database**: MongoDB (menggunakan `mongodb/laravel-mongodb`)
- **Frontend / UI**:
  - Tailwind CSS (Sistem Desain Kustom/Stitch Design System)
  - Alpine.js (Untuk Interaktivitas SPA, Modal, dan AJAX)
  - Blade Templates
- **Lainnya**: Laravel Security (Casts Encryption) untuk mengamankan data PII (seperti NIK dan NIB).

---

## ⚙️ Panduan Instalasi (Development)

Untuk menjalankan proyek ini di *local environment* menggunakan Laragon/XAMPP:

1. **Clone repositori**
   ```bash
   git clone https://github.com/kzxazka/catalog-umkm.git
   cd catalog-umkm
   ```

2. **Instal dependensi PHP & Node.js**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment**
   - Salin `.env.example` menjadi `.env`.
   - Pastikan Anda menggunakan `DB_CONNECTION=mongodb` dan sesuaikan koneksi (biasanya port `27017`).
   - Ubah `SESSION_DRIVER=file` dan `CACHE_STORE=file` untuk mencegah *internal server error* akibat driver DB.
   ```bash
   php artisan key:generate
   php artisan config:clear
   ```

4. **Migrasi dan Seeding Data**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Jalankan Aplikasi**
   Proyek dapat diakses melalui host virtual (misal: `http://catalog-umkm.test`) atau jalankan *development server*:
   ```bash
   php artisan serve
   ```

---

## 🔒 Akun Pengujian (Testing Accounts)

Setelah menjalankan `db:seed`, gunakan akun berikut untuk mencoba sistem:

**Superadmin (Admin Dinas):**
- Email: `admin@dinas.go.id`
- Password: `password123`

**UMKM Owner:**
- Email: `owner@jagatboemi.com`
- Password: `password123`

---

*Dikembangkan dengan <3 untuk memajukan UMKM Indonesia.*
