<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicStoreController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MitraController;
use Illuminate\Support\Facades\Route;

// Root → katalog publik
Route::redirect('/', '/catalog');

// ================================================================
// PUBLIC — Katalog Semua Produk (Visitor bisa akses tanpa login)
// ================================================================
Route::get('/catalog', [PublicCatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/product/{id}', [PublicCatalogController::class, 'show'])->name('catalog.product');
Route::get('/events/{id}', [\App\Http\Controllers\PublicEventController::class, 'show'])->name('events.show');

// Toko per UMKM
Route::get('/store/{slug}', [PublicStoreController::class, 'show'])->name('store.public');
Route::get('/store/{slug}/product/{id}', [PublicStoreController::class, 'showProduct'])->name('store.product');
Route::post('/track-click/{id}', [PublicStoreController::class, 'trackClick'])
    ->name('store.track-click')->middleware('throttle:10,1');

// ================================================================
// DASHBOARD — Role-based redirect
// ================================================================
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin' || $user->role === 'superadmin') {
        return redirect()->route('admin.verifikasi');
    }
    if ($user->role === 'buyer') {
        return redirect()->route($user->hasPendingMitraApplication() ? 'mitra.status' : 'mitra.register');
    }
    // UMKM Owner
    $store = $user->store;
    $products = $store ? \App\Models\Product::where('store_id', $store->id)->get() : [];
    return view('dashboard', compact('store', 'products'));
})->middleware(['auth', 'verified'])->name('dashboard');

// CSV Export
Route::get('/dashboard/export', function () {
    $store = auth()->user()->store;
    if (!$store)
        return redirect()->route('dashboard');
    $products = \App\Models\Product::where('store_id', $store->id)->get();
    $csvData = "Nama Produk,Klik WhatsApp,Klik Marketplace,Total Klik\n";
    foreach ($products as $product) {
        $clicks = $product->clicks ?? [];
        $wa = $clicks['WhatsApp'] ?? 0;
        $marketplace = array_sum(array_diff_key($clicks, array_flip(['WhatsApp'])));
        $total = array_sum($clicks);
        $csvData .= "\"{$product->name}\",{$wa},{$marketplace},{$total}\n";
    }
    return response($csvData)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="laporan_analytics_' . $store->slug . '.csv"');
})->middleware(['auth', 'verified'])->name('dashboard.export');

// ================================================================
// AUTH REQUIRED ROUTES
// ================================================================
Route::middleware('auth')->group(function () {

    // Profile Laravel default
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── MITRA ─────────────────────────────────────────────────
    Route::get('/mitra/daftar', [MitraController::class, 'create'])->name('mitra.register');
    Route::post('/mitra/daftar', [MitraController::class, 'store'])->name('mitra.store');
    Route::get('/mitra/status', [MitraController::class, 'status'])->name('mitra.status');

    // ── ADMIN ─────────────────────────────────────────────────
    Route::get('/admin/verifikasi', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');
    Route::get('/admin/sme-database', [AdminController::class, 'smeDatabase'])->name('admin.sme_database');
    Route::get('/admin/sme-database/export', [AdminController::class, 'exportSmeCsv'])->name('admin.sme_database.export');
    Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    Route::get('/admin/api/stores', [AdminController::class, 'getStores'])->name('admin.api.stores');
    Route::get('/admin/api/applications', [AdminController::class, 'getApplications'])->name('admin.api.applications');
    Route::get('/admin/mitra/{id}/document/{type}', [AdminController::class, 'viewDocument'])->name('admin.mitra.document');

    // Admin: Mitra Applications
    Route::get('/admin/mitra', [MitraController::class, 'adminList'])->name('admin.mitra');
    Route::post('/admin/mitra/{id}/approve', [MitraController::class, 'approve'])->name('admin.mitra.approve');
    Route::post('/admin/mitra/{id}/reject', [MitraController::class, 'reject'])->name('admin.mitra.reject');
    Route::post('/admin/mitra/{id}/revision', [MitraController::class, 'revision'])->name('admin.mitra.revision');

    // Admin: Event Management CRUD
    Route::get('/admin/events', [\App\Http\Controllers\AdminEventController::class, 'index'])->name('admin.events.index');
    Route::get('/admin/events/create', [\App\Http\Controllers\AdminEventController::class, 'create'])->name('admin.events.create');
    Route::post('/admin/events', [\App\Http\Controllers\AdminEventController::class, 'store'])->name('admin.events.store');
    Route::get('/admin/events/{id}/edit', [\App\Http\Controllers\AdminEventController::class, 'edit'])->name('admin.events.edit');
    Route::post('/admin/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{id}', [\App\Http\Controllers\AdminEventController::class, 'destroy'])->name('admin.events.destroy');

    // ── OWNER (UMKM) ──────────────────────────────────────────
    Route::resource('products', ProductController::class);
    Route::get('/owner/products', [\App\Http\Controllers\OwnerController::class, 'products'])->name('owner.products');
    Route::get('/owner/links', [\App\Http\Controllers\OwnerController::class, 'links'])->name('owner.links');
    Route::post('/owner/links', [\App\Http\Controllers\OwnerController::class, 'updateLinks'])->name('owner.links.update');
    Route::get('/owner/settings', [\App\Http\Controllers\OwnerController::class, 'settings'])->name('owner.settings');
    Route::post('/owner/settings', [\App\Http\Controllers\OwnerController::class, 'updateSettings'])->name('owner.settings.update');
});

Route::get('/repair-symlink', function () {
    $target = storage_path('app/public');
    $shortcut = public_path('storage');
    $message = '';
    $status = 'pending';
    $ssh_command = "ln -sf " . escapeshellarg($target) . " " . escapeshellarg($shortcut);
    
    try {
        if (is_link($shortcut)) {
            @unlink($shortcut);
        } elseif (is_dir($shortcut)) {
            @rename($shortcut, $shortcut . '_backup_' . time());
        }
        
        if (!function_exists('symlink')) {
            throw new \Exception("Fungsi PHP 'symlink()' dinonaktifkan oleh Hostinger demi alasan keamanan.");
        }
        
        if (file_exists($shortcut) || is_link($shortcut)) {
            throw new \Exception("Folder atau file 'storage' sudah ada di folder public. Silakan hapus atau rename terlebih dahulu.");
        }
        
        $result = @symlink($target, $shortcut);
        if ($result) {
            $status = 'success';
            $message = 'Symbolic link berhasil dibuat! Silakan cek kembali gambar produk Anda.';
        } else {
            throw new \Exception("Gagal membuat symbolic link menggunakan PHP (kemungkinan masalah izin/permission).");
        }
    } catch (\Throwable $e) {
        $status = 'error';
        $message = $e->getMessage();
    }
    
    $html = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perbaikan Storage Symlink</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-50 font-[\'Inter\'] min-h-screen flex items-center justify-center p-4">
        <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-6 md:p-8">
            <h1 class="text-xl font-extrabold text-slate-900 mb-2">Pemberes Hubungan Storage (Symlink)</h1>
            <p class="text-xs text-slate-500 mb-6">Membantu menghubungkan folder public_html dengan media produk Anda.</p>
            
            ' . ($status === 'success' ? '
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-5 mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-xl">✅</span>
                    <div>
                        <h3 class="font-bold text-sm">Berhasil!</h3>
                        <p class="text-xs text-emerald-600 mt-1 leading-relaxed">' . e($message) . '</p>
                    </div>
                </div>
            </div>
            ' : '
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-5 mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-xl">❌</span>
                    <div>
                        <h3 class="font-bold text-sm">Gagal Membuat Otomatis</h3>
                        <p class="text-xs text-rose-600 mt-1 leading-relaxed">' . e($message) . '</p>
                    </div>
                </div>
            </div>
            
            <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50">
                <h3 class="font-bold text-xs text-slate-700 uppercase tracking-wider mb-3">Langkah Manual via SSH Terminal</h3>
                <p class="text-xs text-slate-600 mb-3 leading-relaxed">Karena PHP symlink dibatasi oleh hosting Anda, silakan jalankan perintah berikut langsung di SSH terminal Anda:</p>
                <div class="bg-slate-900 text-slate-100 font-mono text-xs p-3.5 rounded-xl select-all overflow-x-auto mb-4 border border-slate-800">
                    ' . e($ssh_command) . '
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed"><em>Tips: Jalankan perintah ini di dalam folder utama proyek (public_html atau folder root Laravel).</em></p>
            </div>
            ') . '
            
            <div class="mt-6 flex justify-end gap-3">
                <a href="/catalog" class="bg-primary text-white text-xs font-bold px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 transition-colors">
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </body>
    </html>
    ';
    
    return response($html);
});

require __DIR__ . '/auth.php';
