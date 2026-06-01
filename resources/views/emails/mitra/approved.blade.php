<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><style>
body{font-family:'Segoe UI',sans-serif;background:#f8f9ff;margin:0;padding:20px}
.wrap{max-width:600px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}
.header{background:linear-gradient(135deg,#011a48,#1b305e);padding:32px 40px;text-align:center}
.header h1{color:#fff;margin:0;font-size:24px;font-weight:800}
.header p{color:#b2c5fd;margin:8px 0 0;font-size:14px}
.icon{font-size:48px;margin-bottom:16px}
.body{padding:32px 40px;text-align:center}
.body h2{color:#011a48;font-size:20px;font-weight:700;margin:0 0 16px}
.body p{color:#44464f;font-size:14px;line-height:1.7;margin:0 0 24px}
.store-name{display:inline-block;background:#e5eeff;color:#011a48;padding:8px 20px;border-radius:8px;font-weight:700;font-size:16px;margin-bottom:24px}
.btn{display:inline-block;background:#9e421e;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px}
.footer{background:#f8f9ff;padding:20px 40px;font-size:12px;color:#44464f;border-top:1px solid #e5eeff;text-align:center}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <div class="icon">🎉</div>
        <h1>Selamat, Pengajuan Disetujui!</h1>
        <p>Portal UMKM — Dinas Perdagangan</p>
    </div>
    <div class="body">
        <h2>Halo, {{ $application->user->name ?? 'Mitra' }}!</h2>
        <p>Pengajuan kemitraan Anda telah <strong>disetujui</strong> oleh Tim Dinas Perdagangan. Usaha Anda telah resmi bergabung sebagai mitra UMKM.</p>
        <div class="store-name">🏪 {{ $application->business_name }}</div>
        <p>Akun Anda kini telah otomatis di-upgrade menjadi <strong>Owner (Mitra UMKM)</strong> dan toko Anda telah aktif. Anda dapat langsung masuk ke dashboard menggunakan email &amp; password yang Anda daftarkan sebelumnya (atau masuk dengan akun Google) untuk mulai mengelola produk toko Anda.</p>
        <a href="{{ url('/dashboard') }}" class="btn">Masuk ke Dashboard Toko</a>
    </div>
    <div class="footer">
        Email otomatis dari Portal UMKM Dinas Perdagangan · <a href="{{ url('/') }}">portalumkm.go.id</a>
    </div>
</div>
</body></html>
