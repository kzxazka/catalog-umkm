<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicStoreController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Langsung redirect ke halaman login
Route::redirect('/', '/login');

// Halaman Katalog Publik per Toko
Route::get('/store/{slug}', [PublicStoreController::class, 'show'])->name('store.public');
Route::get('/store/{slug}/product/{id}', [PublicStoreController::class, 'showProduct'])->name('store.product');
// Cybersecurity: Rate limiting (maksimal 10 klik per menit dari IP yang sama) untuk menghindari spam/bot
Route::post('/track-click/{id}', [PublicStoreController::class, 'trackClick'])->name('store.track-click')->middleware('throttle:10,1');

Route::get('/dashboard', function () {
    $store = auth()->user()->store;
    $products = [];
    if($store) {
        $products = \App\Models\Product::where('store_id', $store->id)->get();
    }
    return view('dashboard', compact('store', 'products'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Fitur Export CSV (Government Standard)
Route::get('/dashboard/export', function () {
    $store = auth()->user()->store;
    if (!$store) return redirect()->route('dashboard');
    
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
        ->header('Content-Disposition', 'attachment; filename="laporan_analytics_'.$store->slug.'.csv"');
})->middleware(['auth', 'verified'])->name('dashboard.export');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rute Superadmin
    Route::get('/admin/verifikasi', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');
    Route::get('/admin/sme-database', [AdminController::class, 'smeDatabase'])->name('admin.sme_database');
    Route::get('/admin/sme-database/export', [AdminController::class, 'exportSmeCsv'])->name('admin.sme_database.export');
    Route::get('/admin/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
    
    // API Endpoint for Alpine.js AJAX
    Route::get('/admin/api/stores', [AdminController::class, 'getStores'])->name('admin.api.stores');
    
    // Rute UMKM Owner
    Route::get('/owner/products', [\App\Http\Controllers\OwnerController::class, 'products'])->name('owner.products');
    Route::get('/owner/links', [\App\Http\Controllers\OwnerController::class, 'links'])->name('owner.links');
    Route::get('/owner/settings', [\App\Http\Controllers\OwnerController::class, 'settings'])->name('owner.settings');
});

require __DIR__.'/auth.php';
