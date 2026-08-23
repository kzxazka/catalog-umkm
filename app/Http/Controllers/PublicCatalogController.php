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

        // Catatan: ->latest() tidak kompatibel MongoDB Laravel (SortDirection enum issue).
        // Gunakan integer -1 (desc) secara eksplisit.
        $products = $query->orderBy('_id', -1)->paginate(24);

        // Daftar kategori unik
        $categories = collect(Product::raw()->distinct('category'))
            ->filter(fn($val) => !empty($val))
            ->sort()
            ->values();

        // Daftar kota unik dari semua toko (untuk filter lokasi)
        $cities = collect(Store::raw()->distinct('city'))
            ->filter(fn($val) => !empty($val))
            ->sort()
            ->values();

        // Fetch active events
        $events = \App\Models\Event::where('status', 'active')
            ->orderBy('_id', -1)
            ->get();

        return view('catalog.index', compact(
            'products', 'categories', 'cities',
            'selectedCategory', 'selectedCity', 'events'
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

        return view('catalog.show', compact('product', 'store', 'related'));
    }
}
