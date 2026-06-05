<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class PublicStoreController extends Controller
{
    public function show(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();

        // Tab aktif: toko | produk | kategori
        $tab = $request->get('tab', 'toko');

        $allProducts = Product::where('store_id', $store->id)->orderBy('_id', -1)->get();

        // Filter by kategori
        $selectedCategory = $request->get('category', '');
        $query = Product::where('store_id', $store->id)->orderBy('_id', -1);
        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }
        $products = $query->paginate(20);

        // Kategori unik dari produk toko ini
        $categories = $allProducts->pluck('category')->filter()->unique()->sort()->values();

        // Stats toko
        $totalProducts = $allProducts->count();
        $totalClicks   = $allProducts->sum(function ($p) {
            return is_array($p->clicks) ? array_sum($p->clicks) : 0;
        });

        // Produk terlaris (berdasarkan total klik)
        $topProducts = $allProducts->sortByDesc(function ($p) {
            return is_array($p->clicks) ? array_sum($p->clicks) : 0;
        })->take(4)->values();

        // Follow status
        $isFollowing = false;

        return view('storefront.show', compact(
            'store', 'products', 'allProducts', 'categories',
            'selectedCategory', 'tab', 'totalProducts', 'totalClicks', 'topProducts', 'isFollowing'
        ));
    }

    public function showProduct($slug, $id)
    {
        $store   = Store::where('slug', $slug)->firstOrFail();
        $product = Product::where('store_id', $store->id)->findOrFail($id);

        $recommendations = Product::where('store_id', $store->id)
            ->where('_id', '!=', $id)
            ->limit(4)
            ->get();

        return view('storefront.product', compact('store', 'product', 'recommendations'));
    }

    public function trackClick(Request $request, $id)
    {
        $platform = $request->input('platform');
        $product  = Product::findOrFail($id);

        $clicks              = $product->clicks ?? [];
        $clicks[$platform]   = ($clicks[$platform] ?? 0) + 1;
        $product->clicks     = $clicks;
        $product->save();

        return response()->json(['success' => true]);
    }


}
