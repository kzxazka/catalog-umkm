<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function products()
    {
        $user = auth()->user();
        $store = $user->store;
        $storeCategory = $store ? ($store->category ?? 'Fashion') : 'Fashion';

        return view('owner.products', compact('storeCategory'));
    }

    public function links()
    {
        $user = auth()->user();
        $store = $user->store;
        return view('owner.links', compact('store'));
    }

    public function updateLinks(Request $request)
    {
        $user = auth()->user();
        $store = $user->store;

        if (!$store) {
            return redirect()->back()->with('error', 'Toko tidak ditemukan. Hubungi Admin.');
        }

        $request->validate([
            'whatsapp'  => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'shopee'    => 'nullable|url|max:255',
            'tokopedia' => 'nullable|url|max:255',
        ]);

        $wa = $request->whatsapp;
        if (!empty($wa)) {
            $wa = preg_replace('/[^0-9]/', '', $wa);
            if (str_starts_with($wa, '62')) {
                $wa = substr($wa, 2);
            } elseif (str_starts_with($wa, '0')) {
                $wa = substr($wa, 1);
            }
        }

        $socialLinks = [
            'instagram' => $request->instagram ? 'https://instagram.com/' . ltrim(parse_url($request->instagram, PHP_URL_PATH) ?: $request->instagram, '/') : null,
            'shopee'    => $request->shopee,
            'tokopedia' => $request->tokopedia,
            'whatsapp'  => $wa ? 'https://wa.me/62' . $wa : null,
        ];

        $store->update([
            'whatsapp'     => $wa,
            'instagram'    => $request->instagram,
            'social_links' => $socialLinks,
        ]);

        return redirect()->back()->with('success', 'Tautan media sosial & marketplace berhasil diperbarui!');
    }

    public function settings()
    {
        $user = auth()->user();
        $store = $user->store;

        return view('owner.settings', compact('store'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        $store = $user->store;

        if (!$store) {
            return redirect()->back()->with('error', 'Toko tidak ditemukan. Hubungi Admin.');
        }

        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'category'    => 'required|string|in:Fashion,Food & Beverage,Healthy Product,Other',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($store->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($store->logo);
            }

            $logoPath = $request->file('logo')->store('stores/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $store->update($data);

        return redirect()->back()->with('success', 'Profil toko berhasil diperbarui!');
    }
}
