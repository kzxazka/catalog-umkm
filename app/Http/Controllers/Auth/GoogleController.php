<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Autentikasi Google gagal atau dibatalkan.');
        }

        // Find user by Google ID or by Email
        $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();

        if ($user) {
            // Update Google ID if not set
            if (empty($user->google_id)) {
                $user->google_id = $googleUser->id;
                $user->save();
            }
        } else {
            // Register a new user as buyer
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'role' => 'buyer',
                'password' => Hash::make(Str::random(16)), // secure random password
            ]);
        }

        // Log in the user
        Auth::login($user, true);

        // Redirect based on role
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.laporan')->with('success', 'Selamat datang kembali, Admin!');
        } elseif ($user->role === 'owner') {
            return redirect()->route('dashboard')->with('success', 'Selamat datang di Dashboard Toko Anda!');
        }

        return redirect()->route('catalog.index')->with('success', 'Berhasil masuk menggunakan akun Google!');
    }
}
