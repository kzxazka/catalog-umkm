<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        // 1. Buat User baru (Owner UMKM)
        $user = User::create([
            'name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'umkm',
        ]);

        // 2. Buat Record Toko untuk User tersebut
        Store::create([
            'user_id' => $user->id,
            'name' => $request->store_name,
            'slug' => Str::slug($request->store_name),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Akun UMKM berhasil dibuat.');
    }
}
