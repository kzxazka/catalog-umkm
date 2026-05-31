<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><style>
body { font-family: 'Segoe UI', sans-serif; background: #f8f9ff; margin: 0; padding: 20px; }
.wrap { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
.header { background: #011a48; padding: 32px 40px; }
.header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
.header p  { color: #b2c5fd; margin: 8px 0 0; font-size: 14px; }
.body { padding: 32px 40px; }
.label { font-size: 11px; font-weight: 700; color: #9e421e; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
.value { font-size: 15px; font-weight: 600; color: #011a48; margin-bottom: 20px; }
.badge { display: inline-block; background: #fff8f5; border: 1px solid #ffddd0; color: #9e421e; padding: 6px 16px; border-radius: 100px; font-size: 12px; font-weight: 700; margin-bottom: 24px; }
.btn { display: inline-block; background: #9e421e; color: #fff; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 14px; }
.footer { background: #f8f9ff; padding: 20px 40px; font-size: 12px; color: #44464f; border-top: 1px solid #e5eeff; }
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🏪 Pengajuan Kemitraan Baru</h1>
        <p>Portal UMKM — Dinas Perdagangan</p>
    </div>
    <div class="body">
        <span class="badge">⏳ Menunggu Verifikasi</span>
        <div class="label">Nama Usaha</div>
        <div class="value">{{ $application->business_name }}</div>
        <div class="label">Kategori</div>
        <div class="value">{{ $application->business_category }}</div>
        <div class="label">Kota / Kecamatan</div>
        <div class="value">{{ $application->city }}, {{ $application->district }}</div>
        <div class="label">Alamat Usaha</div>
        <div class="value">{{ $application->business_address }}</div>
        <div class="label">WhatsApp</div>
        <div class="value">{{ $application->whatsapp }}</div>
        <div class="label">Deskripsi</div>
        <div class="value">{{ $application->description }}</div>
        <p style="font-size:14px;color:#44464f;margin:24px 0 8px;">Silakan login ke panel admin untuk meninjau dan memverifikasi pengajuan ini.</p>
        <a href="{{ url('/admin/mitra') }}" class="btn">Buka Panel Admin</a>
    </div>
    <div class="footer">
        Email otomatis dari Portal UMKM Dinas Perdagangan. Jangan membalas email ini.
    </div>
</div>
</body>
</html>
