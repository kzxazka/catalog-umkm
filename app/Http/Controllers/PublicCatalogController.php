<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\Store;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    /**
     * Halaman katalog publik — semua produk dari semua toko.
     * Filter: kategori + lokasi (kota/kecamatan toko).
     */
    public function index(Request $request)
    {
        $selectedCategory = $request->get('category', 'Semua');
        $selectedCity     = $request->get('city', '');
        $isFavoriteFilter = $request->get('filter') === 'favorit';

        // Filter lokasi: cari store_ids yang cocok dengan kota
        $storeIds = null;
        if ($selectedCity) {
            $storeIds = Store::where('city', 'like', '%'.$selectedCity.'%')
                ->pluck('_id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        }

        $query = Product::with('store');

        if ($selectedCategory && $selectedCategory !== 'Semua') {
            $query->where('category', $selectedCategory);
        }

        if ($storeIds !== null) {
            $query->whereIn('store_id', $storeIds);
        }

        if ($isFavoriteFilter) {
            if (auth()->check()) {
                $favs = auth()->user()->favorited_products ?? [];
                // pastikan jika favs kosong query tidak mereturn apa pun
                if (empty($favs)) {
                    $query->whereIn('_id', ['non-existent-id']);
                } else {
                    $query->whereIn('_id', $favs);
                }
            } else {
                return redirect()->route('login');
            }
        }

        // Catatan: ->latest() tidak kompatibel MongoDB Laravel (SortDirection enum issue).
        // Gunakan integer -1 (desc) secara eksplisit.
        $products = $query->orderBy('_id', -1)->paginate(24);

        // Daftar kategori unik
        $categories = Product::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        // Daftar kota unik dari semua toko (untuk filter lokasi)
        $cities = Store::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        // Fetch active events
        $events = \App\Models\Event::where('status', 'active')
            ->orderBy('_id', -1)
            ->get();

        return view('catalog.index', compact(
            'products', 'categories', 'cities',
            'selectedCategory', 'selectedCity', 'isFavoriteFilter', 'events'
        ));
    }

    /**
     * Halaman detail produk — Dapat diakses oleh tamu/guest maupun pembeli.
     */
    public function show($productId)
    {

        $product = Product::findOrFail($productId);
        $store   = Store::find($product->store_id);

        // Produk terkait dari toko yang sama
        $related = Product::where('store_id', $product->store_id)
            ->where('_id', '!=', $productId)
            ->limit(6)
            ->get();

        // Pertanyaan publik yang sudah dijawab untuk produk ini
        $inquiries = ProductInquiry::where('product_id', $productId)
            ->where('is_public', true)
            ->whereNotNull('reply')
            ->orderBy('_id', -1)
            ->limit(5)
            ->get();

        // Template pertanyaan
        $questionTemplates = \App\Http\Controllers\ProductInquiryController::getTemplates();

        return view('catalog.show', compact('product', 'store', 'related', 'inquiries', 'questionTemplates'));
    }

    /**
     * Toggle favorit produk — AJAX only, butuh login.
     */
    public function toggleFavorite(Request $request, $productId)
    {
        $user  = auth()->user();
        $favs  = $user->favorited_products ?? [];

        if (in_array($productId, $favs)) {
            $favs = array_values(array_diff($favs, [$productId]));
            $state = false;
        } else {
            $favs[] = $productId;
            $state  = true;
        }

        $user->favorited_products = $favs;
        $user->save();

        return response()->json(['favorited' => $state, 'total' => count($favs)]);
    }
}
