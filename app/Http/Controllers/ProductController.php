<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan daftar produk milik UMKM
    public function index()
    {
        // Isolasi Data: Hanya ambil produk yang store_id-nya sama dengan toko milik user yang sedang login
        $store = auth()->user()->store;
        
        if (!$store) {
            return redirect()->route('dashboard')->with('error', 'Anda belum memiliki toko. Hubungi Superadmin.');
        }

        // Filter berdasarkan store_id
        $products = Product::where('store_id', $store->id)->latest()->get();
        return view('products.index', compact('products'));
    }

    // Form tambah produk
    public function create()
    {
        return view('products.create');
    }

    // Simpan produk
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        ]);

        $images = [];
        if($request->hasFile('photos')) {
            // Pastikan direktori ada
            if (!Storage::exists('public/products')) {
                Storage::makeDirectory('public/products');
            }

            foreach($request->file('photos') as $photo) {
                // Hashing berbasis konten untuk menghindari duplikasi file di storage
                $hash = md5_file($photo->getRealPath());
                $finalName = $hash . '.webp';
                
                // Proses Kompresi: Resize ke 1200px & Ubah ke WebP kualitas 80%
                $imgPath = storage_path('app/public/products/' . $finalName);
                
                // Cek apakah file dengan hash ini sudah ada (Deduplikasi)
                if (!file_exists($imgPath)) {
                    $img = Image::read($photo);
                    $img->scale(width: 1200);
                    $img->toWebp(80)->save($imgPath);
                }
                
                $images[] = $finalName;
            }
        }

        Product::create([
            'store_id' => auth()->user()->store->id,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'images' => $images, // Array path foto 
            'links' => $request->links ?? [], // Array link marketplace dinamis
        ]);
        
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Form edit produk
    public function edit(Product $product)
    {
        // Isolasi Data: Pastikan produk yang diedit adalah milik tokonya
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('products.edit', compact('product'));
    }

    // Update produk
    public function update(Request $request, Product $product)
    {
        // Isolasi Data
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $images = $product->images ?? [];
        if($request->hasFile('photos')) {
            foreach($request->file('photos') as $photo) {
                $hash = md5_file($photo->getRealPath());
                $finalName = $hash . '.webp';
                
                $imgPath = storage_path('app/public/products/' . $finalName);
                
                if (!file_exists($imgPath)) {
                    $img = Image::read($photo);
                    $img->scale(width: 1200);
                    $img->toWebp(80)->save($imgPath);
                }
                
                $images[] = $finalName;
            }
        }

        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'images' => $images,
            'links' => $request->links ?? $product->links,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
        // Isolasi Data
        if ($product->store_id !== auth()->user()->store->id) {
            abort(403, 'Unauthorized action.');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
