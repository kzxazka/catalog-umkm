<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicStoreController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuyerProfileController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductInquiryController;
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
        return redirect()->route('catalog.index');
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

    // ── BUYER PROFILE ─────────────────────────────────────────
    Route::get('/buyer/profile', [BuyerProfileController::class, 'show'])->name('buyer.profile');
    Route::post('/buyer/profile', [BuyerProfileController::class, 'update'])->name('buyer.profile.update');
    Route::post('/buyer/profile/password', [BuyerProfileController::class, 'updatePassword'])->name('buyer.profile.password');

    // ── MITRA ─────────────────────────────────────────────────
    Route::get('/mitra/daftar', [MitraController::class, 'create'])->name('mitra.register');
    Route::post('/mitra/daftar', [MitraController::class, 'store'])->name('mitra.store');
    Route::get('/mitra/status', [MitraController::class, 'status'])->name('mitra.status');

    // ── FAVORIT & FOLLOW ──────────────────────────────────────
    Route::post('/catalog/favorite/{productId}', [PublicCatalogController::class, 'toggleFavorite'])->name('catalog.favorite.toggle');
    Route::post('/store/{slug}/follow', [PublicStoreController::class, 'toggleFollow'])->name('store.follow.toggle');

    // ── PRODUCT INQUIRY (Tanya Produk) ────────────────────────
    Route::post('/inquiry/{productId}', [ProductInquiryController::class, 'store'])->name('inquiry.store');
    Route::get('/inquiries', [ProductInquiryController::class, 'buyerList'])->name('buyer.inquiries');

    // ── CHAT — BUYER ──────────────────────────────────────────
    Route::get('/chat', [ChatController::class, 'buyerInbox'])->name('chat.buyer.inbox');
    Route::get('/chat/{storeSlug}', [ChatController::class, 'buyerChat'])->name('chat.buyer');
    Route::post('/chat/{storeSlug}/send', [ChatController::class, 'buyerSend'])->name('chat.buyer.send');
    Route::get('/chat/poll', [ChatController::class, 'poll'])->name('chat.poll');

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

    // Owner: Product Inquiries
    Route::get('/owner/inquiries', [ProductInquiryController::class, 'ownerList'])->name('owner.inquiries');
    Route::post('/owner/inquiries/{id}/reply', [ProductInquiryController::class, 'reply'])->name('owner.inquiry.reply');

    // Owner: Live Chat
    Route::get('/owner/chat', [ChatController::class, 'ownerInbox'])->name('owner.chat.inbox');
    Route::get('/owner/chat/{buyerId}', [ChatController::class, 'ownerChat'])->name('owner.chat');
    Route::post('/owner/chat/{buyerId}/send', [ChatController::class, 'ownerSend'])->name('owner.chat.send');
});

require __DIR__ . '/auth.php';
