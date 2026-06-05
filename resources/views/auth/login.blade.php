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
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="email">Email</label>
            <input
                class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan Email"
                type="text" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-sm" />
        </div>
        <div x-data="{ showPassword: false }">
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="password">Kata Sandi</label>
            <div class="relative">
                <input
                    class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                    id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                    :type="showPassword ? 'text' : 'password'" />
                <button @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors"
                    type="button">
                    <span class="material-symbols-outlined"
                        x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
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

        {{--
        <div class="relative flex items-center py-4">
            <div class="flex-grow border-t border-outline-variant"></div>
            <span class="flex-shrink mx-4 font-label-sm text-label-sm text-outline">Atau masuk dengan</span>
            <div class="flex-grow border-t border-outline-variant"></div>
        </div>

        <a href="{{ route('auth.google') }}"
            class="flex items-center justify-center gap-3 border border-outline-variant py-3 rounded-lg hover:bg-surface-container-low transition-all font-label-md text-label-md text-on-surface">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                    fill="#4285F4" />
                <path
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                    fill="#34A853" />
                <path
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
                    fill="#FBBC05" />
                <path
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"
                    fill="#EA4335" />
            </svg>
            <span>Google</span>
        </a>
        --}}
    </form>

    <p class="mt-6 text-center font-body-md text-body-md text-on-surface-variant">
        Belum memiliki akun? <a class="text-secondary font-bold hover:underline" href="{{ route('register') }}">Daftar
            Sekarang</a>
    </p>
    <p class="mt-3 text-center">
        <a href="{{ route('catalog.index') }}"
            class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary flex items-center justify-center gap-1 transition-colors">
            <span class="material-symbols-outlined" style="font-size:15px;">storefront</span>
            Lihat Katalog Tanpa Login
        </a>
    </p>
</x-guest-layout>