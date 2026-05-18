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
        
        $query = Product::where('store_id', $store->id);

        if ($request->has('category') && $request->category != 'Semua') {
            $query->where('category', $request->category);
        }

        $products = $query->get();
        $categories = Product::where('store_id', $store->id)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('storefront.show', compact('store', 'products', 'categories'));
    }

    public function showProduct($slug, $id)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        $product = Product::where('store_id', $store->id)->findOrFail($id);
        
        // Dapatkan produk rekomendasi (produk lain dari toko yang sama)
        $recommendations = Product::where('store_id', $store->id)
                                  ->where('_id', '!=', $id)
                                  ->limit(4)
                                  ->get();

        return view('storefront.product', compact('store', 'product', 'recommendations'));
    }

    public function trackClick(Request $request, $id)
    {
        $platform = $request->input('platform');
        $product = Product::findOrFail($id);
        
        // MongoDB allow dynamic fields, let's store clicks as an array/object
        $clicks = $product->clicks ?? [];
        $clicks[$platform] = ($clicks[$platform] ?? 0) + 1;
        $product->clicks = $clicks;
        $product->save();

        return response()->json(['success' => true]);
    }
}
