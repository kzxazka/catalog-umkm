<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><style>
body{font-family:'Segoe UI',sans-serif;background:#f8f9ff;margin:0;padding:20px}
.wrap{max-width:600px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}
.header{background:#1b305e;padding:32px 40px;text-align:center}
.header h1{color:#fff;margin:0;font-size:22px;font-weight:800}
.header p{color:#b2c5fd;margin:8px 0 0;font-size:14px}
.body{padding:32px 40px}
.body h2{color:#011a48;font-size:18px;font-weight:700;margin:0 0 16px}
.body p{color:#44464f;font-size:14px;line-height:1.7;margin:0 0 16px}
.reason-box{background:#fff8f5;border:1px solid #ffddd0;border-left:4px solid #9e421e;padding:16px 20px;border-radius:8px;margin:20px 0}
.reason-label{font-size:11px;font-weight:700;color:#9e421e;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px}
.reason-text{font-size:14px;color:#011a48;line-height:1.6}
.btn{display:inline-block;background:#011a48;color:#fff;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px}
.footer{background:#f8f9ff;padding:20px 40px;font-size:12px;color:#44464f;border-top:1px solid #e5eeff;text-align:center}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Pembaruan Status Pengajuan Kemitraan</h1>
        <p>Portal UMKM — Dinas Perdagangan</p>
    </div>
    <div class="body">
        <h2>Halo, {{ $application->user->name ?? 'Pemohon' }}</h2>
        <p>Setelah melalui proses peninjauan, Tim Dinas Perdagangan menyampaikan bahwa pengajuan kemitraan atas nama usaha <strong>{{ $application->business_name }}</strong> belum dapat kami setujui pada saat ini.</p>
        @if($application->rejection_reason)
        <div class="reason-box">
            <div class="reason-label">Alasan Peninjauan Ulang</div>
            <div class="reason-text">{{ $application->rejection_reason }}</div>
        </div>
        @endif
        <p>Anda dipersilakan untuk melengkapi dokumen dan mengajukan kembali permohonan melalui portal setelah memenuhi persyaratan yang diperlukan.</p>
        <a href="{{ url('/mitra/daftar') }}" class="btn">Ajukan Kembali</a>
    </div>
    <div class="footer">
        Email otomatis dari Portal UMKM Dinas Perdagangan · <a href="{{ url('/') }}">portalumkm.go.id</a>
    </div>
</div>
</body></html>
