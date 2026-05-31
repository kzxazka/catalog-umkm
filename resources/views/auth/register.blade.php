<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8 text-center md:text-left">
        <img alt="Logo Portal UMKM" class="h-14 w-auto mb-5 mx-auto md:mx-0"
            src="{{ asset('img/disperdaglogo.png') }}" />
        <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Daftar Akun Pengunjung</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">
            Bergabung untuk mengakses detail produk dan menghubungi UMKM lokal.
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Nama Lengkap --}}
        <div>
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="name">
                Nama Lengkap
            </label>
            <input
                class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                id="name" name="name" type="text" value="{{ old('name') }}"
                required autofocus autocomplete="name"
                placeholder="Masukkan nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-error text-sm" />
        </div>

        {{-- Email --}}
        <div>
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="email">
                Alamat Email
            </label>
            <input
                class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                id="email" name="email" type="email" value="{{ old('email') }}"
                required autocomplete="username"
                placeholder="contoh@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-sm" />
        </div>

        {{-- Password --}}
        <div>
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="password">
                Kata Sandi
            </label>
            <div class="relative">
                <input
                    class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                    id="password" name="password" type="password"
                    required autocomplete="new-password"
                    placeholder="Minimal 8 karakter" />
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors"
                    type="button"
                    onclick="togglePassword('password', this)">
                    <span class="material-symbols-outlined">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-sm" />
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label class="block font-label-md text-label-md text-on-surface mb-2" for="password_confirmation">
                Konfirmasi Kata Sandi
            </label>
            <div class="relative">
                <input
                    class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all text-body-md bg-white text-on-surface"
                    id="password_confirmation" name="password_confirmation" type="password"
                    required autocomplete="new-password"
                    placeholder="Ulangi kata sandi Anda" />
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors"
                    type="button"
                    onclick="togglePassword('password_confirmation', this)">
                    <span class="material-symbols-outlined">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-error text-sm" />
        </div>

        {{-- Info --}}
        <div class="bg-surface-container-low rounded-lg p-4 flex gap-3 items-start">
            <span class="material-symbols-outlined text-primary mt-0.5" style="font-size:18px;">info</span>
            <p class="font-label-sm text-label-sm text-on-surface-variant">
                Akun yang didaftarkan di sini adalah akun <strong class="text-on-surface">Pengunjung / Buyer</strong>.
                Akun pemilik UMKM hanya bisa dibuat oleh Admin Dinas Perdagangan.
            </p>
        </div>

        {{-- Submit --}}
        <button
            class="w-full bg-secondary text-on-secondary py-4 rounded-lg font-label-md text-label-md hover:opacity-90 active:scale-95 transition-all shadow-md"
            type="submit">
            Buat Akun Pengunjung
        </button>
    </form>

    <p class="mt-8 text-center font-body-md text-body-md text-on-surface-variant">
        Sudah punya akun?
        <a class="text-primary font-bold hover:underline" href="{{ route('login') }}">Masuk ke Portal</a>
    </p>

    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon  = btn.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</x-guest-layout>
