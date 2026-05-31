<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductInquiryController extends Controller
{
    // Template pertanyaan yang tersedia
    const TEMPLATES = [
        'stock'     => 'Apakah produk ini masih tersedia/ready stock?',
        'size'      => 'Apakah tersedia dalam ukuran lain?',
        'color'     => 'Apakah tersedia dalam pilihan warna lain?',
        'custom'    => 'Apakah bisa custom/pesan sesuai keinginan?',
        'wholesale' => 'Apakah ada harga khusus untuk pembelian dalam jumlah banyak?',
        'shipping'  => 'Apakah bisa dikirim ke luar kota?',
        'material'  => 'Terbuat dari bahan apa produk ini?',
    ];

    /** Buyer: kirim pertanyaan produk */
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'question'     => 'required|string|max:500',
            'template_key' => 'nullable|string',
        ]);

        // Rate limit: 1 pertanyaan per produk per buyer per hari
        $existing = ProductInquiry::where('product_id', $productId)
            ->where('buyer_id', auth()->id())
            ->whereDate('created_at', today())
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengirim pertanyaan untuk produk ini hari ini.');
        }

        ProductInquiry::create([
            'product_id'   => $productId,
            'store_id'     => $product->store_id,
            'buyer_id'     => auth()->id(),
            'question'     => $request->question,
            'template_key' => $request->template_key,
            'is_public'    => false,
            'reply'        => null,
        ]);

        return back()->with('success', 'Pertanyaan terkirim! Pemilik toko akan segera membalas.');
    }

    /** Owner: balas pertanyaan */
    public function reply(Request $request, $inquiryId)
    {
        $inquiry = ProductInquiry::findOrFail($inquiryId);

        // Pastikan hanya owner toko yang bisa membalas
        $store = auth()->user()->store;
        if (!$store || (string) $store->id !== (string) $inquiry->store_id) {
            abort(403);
        }

        $request->validate(['reply' => 'required|string|max:1000']);

        $inquiry->update([
            'reply'       => $request->reply,
            'replied_at'  => now(),
            'replied_by'  => auth()->id(),
            'is_public'   => $request->boolean('is_public', false),
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    /** Buyer: daftar pertanyaan terkirim */
    public function buyerList()
    {
        $buyerId = auth()->id();
        $inquiries = ProductInquiry::where('buyer_id', $buyerId)
            ->orderBy('_id', -1)
            ->paginate(15);

        // Load manual product & store
        foreach ($inquiries as $inq) {
            $inq->product = Product::find($inq->product_id);
            $inq->store = Store::find($inq->store_id);
        }

        return view('buyer.inquiries', compact('inquiries'));
    }

    /** Owner: daftar pertanyaan masuk */
    public function ownerList()
    {
        $store = auth()->user()->store;
        if (!$store) return redirect()->route('dashboard');

        $inquiries = ProductInquiry::where('store_id', $store->id)
            ->orderBy('_id', -1)
            ->paginate(20);

        return view('owner.inquiries', compact('store', 'inquiries'));
    }

    public static function getTemplates(): array
    {
        return self::TEMPLATES;
    }
}
