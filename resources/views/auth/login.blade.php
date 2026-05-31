<x-guest-layout>
    <div class="mb-10 text-center md:text-left">
        <img alt="Logo Portal UMKM Indonesia" class="h-16 w-auto mb-6 mx-auto md:mx-0"
            src="{{ asset('img/disperdaglogo.png') }}" />
        <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Selamat Datang di Portal UMKM</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Silakan masuk ke akun Anda untuk mengakses
            dashboard dan sumber daya UMKM.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        <div>
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="email">NIK atau Email</label>
            <input
                class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                id="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="Masukkan NIK atau email terdaftar" type="text" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-sm" />
        </div>
        <div>
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="password">Kata Sandi</label>
            <div class="relative">
                <input
                    class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                    id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                    type="password" />
                <button
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors"
                    type="button">
                    <span class="material-symbols-outlined">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-sm" />
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary transition-all"
                    type="checkbox" name="remember" />
                <span
                    class="font-label-md text-label-md text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat
                    Saya</span>
            </label>
            @if (Route::has('password.request'))
                <a class="font-label-md text-label-md text-secondary hover:underline"
                    href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
            @endif
        </div>

        <button
            class="w-full bg-primary text-on-primary py-4 rounded-lg font-label-md text-label-md hover:opacity-90 active:scale-95 transition-all shadow-md"
            type="submit">
            Masuk
        </button>

        <div class="relative flex items-center py-4">
            <div class="flex-grow border-t border-outline-variant"></div>
            <span class="flex-shrink mx-4 font-label-sm text-label-sm text-outline">Atau masuk dengan Keamanan
                Ekstra</span>
            <div class="flex-grow border-t border-outline-variant"></div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <button
                class="flex items-center justify-center gap-2 border border-outline-variant py-3 rounded-lg hover:bg-surface-container-low transition-colors"
                type="button">
                <span class="material-symbols-outlined text-body-md">account_balance</span>
                <span class="font-label-sm text-label-sm text-on-surface">E-KTP</span>
            </button>
            <button
                class="flex items-center justify-center gap-2 border border-outline-variant py-3 rounded-lg hover:bg-surface-container-low transition-colors"
                type="button">
                <span class="material-symbols-outlined text-body-md">fingerprint</span>
                <span class="font-label-sm text-label-sm text-on-surface">Digital ID</span>
            </button>
        </div>
    </form>

    <p class="mt-6 text-center font-body-md text-body-md text-on-surface-variant">
        Belum memiliki akun? <a class="text-secondary font-bold hover:underline" href="{{ route('register') }}">Daftar
            Sekarang</a>
    </p>
    <p class="mt-3 text-center">
        <a href="{{ route('catalog.index') }}" class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary flex items-center justify-center gap-1 transition-colors">
            <span class="material-symbols-outlined" style="font-size:15px;">storefront</span>
            Lihat Katalog Tanpa Login
        </a>
    </p>
</x-guest-layout>