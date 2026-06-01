@php
    // Render buyer layout langsung — komponen wrapper
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? config('app.name', 'galeriukmbdl') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/disperdaglogo.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary': '#011a48',
                        'primary-container': '#1b305e',
                        'secondary': '#9e421e',
                        'secondary-container': '#fc895f',
                        'surface': '#f8f9ff',
                        'surface-container': '#e5eeff',
                        'surface-container-low': '#eff4ff',
                        'surface-container-high': '#dce9ff',
                        'surface-container-highest': '#d3e4fe',
                        'on-surface': '#0b1c30',
                        'on-surface-variant': '#44464f',
                        'outline': '#757780',
                        'outline-variant': '#c5c6d0',
                        'on-primary': '#ffffff',
                        'on-secondary': '#ffffff',
                        'primary-fixed': '#dae2ff',
                        'primary-fixed-dim': '#b2c5fd',
                        'inverse-primary': '#b2c5fd',
                        'error': '#ba1a1a',
                        'error-container': '#ffdad6',
                    },
                    fontFamily: { sans: ['Public Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Public Sans', sans-serif; box-sizing: border-box; }
        body { background: #f8f9ff; color: #0b1c30; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        [x-cloak] { display: none !important; }
        .popup-enter { animation: popIn .15s ease-out; }
        @keyframes popIn { from { opacity: 0; transform: scale(.95) translateY(-4px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .bottom-safe { padding-bottom: max(env(safe-area-inset-bottom, 0px), 8px); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    {{ $head ?? '' }}
</head>
<body class="min-h-screen bg-surface" x-data="{
    profileOpen: false,
    notifOpen: false,
    get notifications() {
        let n = [];
        @auth
        @if(auth()->user()->hasPendingMitraApplication())
        n.push({ icon: 'pending', text: 'Pengajuan mitra Anda sedang diproses', time: 'Baru', type: 'info' });
        @endif
        @if(auth()->user()->isApprovedMitra())
        n.push({ icon: 'verified', text: 'Selamat! Pengajuan mitra Anda disetujui', time: 'Baru', type: 'success' });
        @endif
        @endauth
        return n;
    }
}">

{{-- ═══ TOP NAVBAR ═══ --}}
<header class="bg-primary sticky top-0 z-50 shadow-md">
    <nav class="max-w-screen-xl mx-auto px-4 md:px-6 h-14 md:h-16 flex items-center justify-between">

        {{-- Brand --}}
        <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 shrink-0">
            <img src="{{ asset('img/disperdaglogo.png') }}" class="w-8 h-8 object-contain" alt="Logo Disperdag">
            <div>
                <span class="text-white font-bold text-sm leading-none block">galeriukmbdl</span>
                <span class="text-blue-200 text-[10px]">Dinas Perdagangan</span>
            </div>
        </a>

        @auth
        {{-- Buyer action icons --}}
        <div class="flex items-center gap-0.5">

            {{-- Chat Icon --}}
            <a href="{{ route('catalog.index') }}" title="Riwayat Chat"
               class="w-10 h-10 rounded-full flex items-center justify-center text-blue-200 hover:bg-white/10 hover:text-white transition-all">
                <span class="material-symbols-outlined" style="font-size:22px">forum</span>
            </a>

            {{-- Favorit Icon --}}
            <a href="{{ route('catalog.index') }}?filter=favorit" title="Produk Favorit"
               class="w-10 h-10 rounded-full flex items-center justify-center text-blue-200 hover:bg-white/10 hover:text-white transition-all">
                <span class="material-symbols-outlined" style="font-size:22px">favorite</span>
            </a>

            {{-- Notifikasi Popup --}}
            <div class="relative">
                <button @click="notifOpen = !notifOpen; profileOpen = false"
                        class="relative w-10 h-10 rounded-full flex items-center justify-center text-blue-200 hover:bg-white/10 hover:text-white transition-all">
                    <span class="material-symbols-outlined" style="font-size:22px">notifications</span>
                    @if(auth()->user()->hasPendingMitraApplication() || auth()->user()->isApprovedMitra())
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-400 rounded-full border border-primary"></span>
                    @endif
                </button>
                <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false"
                     class="absolute right-0 top-12 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-outline-variant overflow-hidden popup-enter z-50">
                    <div class="px-4 py-3 border-b border-outline-variant flex items-center justify-between">
                        <span class="font-bold text-sm text-on-surface">Notifikasi</span>
                        <button @click="notifOpen = false"><span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px">close</span></button>
                    </div>
                    <div class="max-h-60 overflow-y-auto">
                        <template x-if="notifications.length === 0">
                            <div class="py-8 text-center text-on-surface-variant text-sm">
                                <span class="material-symbols-outlined block mb-2" style="font-size:36px">notifications_none</span>
                                Belum ada notifikasi
                            </div>
                        </template>
                        <template x-for="n in notifications" :key="n.text">
                            <div class="px-4 py-3 flex items-start gap-3 hover:bg-surface-container-low border-b border-outline-variant/40 last:border-0">
                                <div :class="n.type === 'success' ? 'bg-green-100' : 'bg-blue-100'" class="w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-sm" :class="n.type === 'success' ? 'text-green-600' : 'text-blue-600'" x-text="n.icon"></span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-on-surface" x-text="n.text"></p>
                                    <p class="text-[10px] text-on-surface-variant mt-0.5" x-text="n.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="px-4 py-2 border-t border-outline-variant">
                        <a href="{{ route('mitra.status') }}" class="text-xs text-primary font-bold hover:underline">Status Mitra →</a>
                    </div>
                </div>
            </div>

            {{-- Profile Popup --}}
            <div class="relative ml-1">
                <button @click="profileOpen = !profileOpen; notifOpen = false"
                        class="w-9 h-9 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/70 transition-all bg-secondary text-white font-bold text-sm flex items-center justify-center">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="" class="w-full h-full object-cover" />
                    @else
                        {{ auth()->user()->initials }}
                    @endif
                </button>

                {{-- Profile Dropdown --}}
                <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                     class="absolute right-0 top-12 w-64 sm:w-72 bg-white rounded-2xl shadow-2xl border border-outline-variant overflow-hidden popup-enter z-50">
                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-primary to-primary-container px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white/40 bg-secondary text-white font-bold flex items-center justify-center text-sm">
                                @if(auth()->user()->avatar_path)
                                    <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="" class="w-full h-full object-cover" />
                                @else
                                    {{ auth()->user()->initials }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-bold text-sm truncate">{{ auth()->user()->nickname ?: explode(' ', auth()->user()->name)[0] }}</p>
                                <p class="text-blue-200 text-xs truncate">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->isApprovedMitra())
                                <span class="inline-flex items-center gap-0.5 bg-green-500/20 text-green-300 text-[10px] font-bold px-2 py-0.5 rounded-full mt-0.5">
                                    <span class="material-symbols-outlined" style="font-size:10px">verified</span> Mitra
                                </span>
                                @elseif(auth()->user()->hasPendingMitraApplication())
                                <span class="inline-flex items-center gap-0.5 bg-amber-500/20 text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-full mt-0.5">
                                    <span class="material-symbols-outlined" style="font-size:10px">pending</span> Diproses
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Menu items --}}
                    <div class="py-1">
                        <a href="{{ route('buyer.profile') }}" @click="profileOpen = false"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low text-on-surface text-sm transition-colors">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:19px">person</span>
                            <span class="font-semibold">Profil Saya</span>
                        </a>
                        <a href="{{ route('mitra.status') }}" @click="profileOpen = false"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low text-on-surface text-sm transition-colors">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:19px">verified_user</span>
                            <span class="font-semibold">Status Mitra</span>
                        </a>
                        @if(!auth()->user()->hasPendingMitraApplication() && !auth()->user()->isApprovedMitra())
                        <a href="{{ route('mitra.register') }}" @click="profileOpen = false"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low text-secondary text-sm transition-colors">
                            <span class="material-symbols-outlined text-secondary" style="font-size:19px">add_business</span>
                            <span class="font-semibold">Daftar Jadi Mitra</span>
                        </a>
                        @endif
                        <div class="h-px bg-outline-variant/50 mx-4 my-1"></div>
                        {{-- Bantuan --}}
                        <button onclick="document.getElementById('help-modal-bl').classList.remove('hidden')"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low text-on-surface text-sm transition-colors text-left">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:19px">help</span>
                            <span class="font-semibold">Bantuan</span>
                        </button>
                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}" id="bl-logout-form">@csrf</form>
                        <button onclick="document.getElementById('logout-modal-bl').classList.remove('hidden')"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 text-error text-sm transition-colors text-left">
                            <span class="material-symbols-outlined text-error" style="font-size:19px">logout</span>
                            <span class="font-semibold">Keluar</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        @else
        <div class="flex items-center gap-2">
            <a href="{{ route('login') }}" class="text-blue-200 hover:text-white text-sm font-bold px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">Masuk</a>
            <a href="{{ route('register') }}" class="bg-secondary text-white text-sm font-bold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">Daftar</a>
        </div>
        @endauth

    </nav>
</header>

{{-- MAIN --}}
<main class="pb-20 md:pb-6">
    {{ $slot }}
</main>

{{-- BOTTOM NAV — Mobile only --}}
<nav class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-outline-variant z-40 bottom-safe shadow-lg">
    <div class="flex">
        @php $cr = Route::currentRouteName() ?? ''; @endphp
        <a href="{{ route('catalog.index') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 transition-colors {{ str_starts_with($cr, 'catalog') ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined" style="font-size:22px">storefront</span>
            <span class="text-[10px] font-bold">Katalog</span>
        </a>
        @auth
        <a href="{{ route('buyer.profile') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 transition-colors {{ $cr === 'buyer.profile' ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined" style="font-size:22px">favorite</span>
            <span class="text-[10px] font-bold">Favorit</span>
        </a>
        <a href="{{ route('mitra.status') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 transition-colors {{ str_starts_with($cr, 'mitra') ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined" style="font-size:22px">verified_user</span>
            <span class="text-[10px] font-bold">Mitra</span>
        </a>
        <a href="{{ route('buyer.profile') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 transition-colors {{ $cr === 'buyer.profile' ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined" style="font-size:22px">person</span>
            <span class="text-[10px] font-bold">Profil</span>
        </a>
        @else
        <a href="{{ route('login') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-on-surface-variant">
            <span class="material-symbols-outlined" style="font-size:22px">login</span>
            <span class="text-[10px] font-bold">Masuk</span>
        </a>
        @endauth
    </div>
</nav>

{{-- MODAL: Logout --}}
<div id="logout-modal-bl" class="hidden fixed inset-0 z-[100] flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('logout-modal-bl').classList.add('hidden')"></div>
    <div class="relative bg-white w-full sm:w-80 rounded-t-2xl sm:rounded-2xl p-6 shadow-2xl mx-4">
        <div class="text-center">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-outlined text-error" style="font-size:24px">logout</span>
            </div>
            <h3 class="text-sm font-bold text-on-surface mb-1">Konfirmasi Keluar</h3>
            <p class="text-xs text-on-surface-variant mb-5">Yakin ingin keluar dari akun?</p>
            <div class="flex gap-3">
                <button onclick="document.getElementById('logout-modal-bl').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-xl border border-outline-variant text-on-surface font-bold text-sm">Batal</button>
                <button onclick="document.getElementById('bl-logout-form').submit()"
                        class="flex-1 py-2.5 rounded-xl bg-error text-white font-bold text-sm">Keluar</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Bantuan --}}
<div id="help-modal-bl" class="hidden fixed inset-0 z-[100] flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('help-modal-bl').classList.add('hidden')"></div>
    <div class="relative bg-white w-full sm:w-96 rounded-t-2xl sm:rounded-2xl shadow-2xl mx-4 overflow-hidden">
        <div class="bg-primary px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold text-sm">Pusat Bantuan</h3>
            <button onclick="document.getElementById('help-modal-bl').classList.add('hidden')" class="text-blue-200">
                <span class="material-symbols-outlined" style="font-size:20px">close</span>
            </button>
        </div>
        <div class="p-5 space-y-3">
            @foreach([
                ['icon' => 'add_business',  'q' => 'Cara menjadi Mitra UMKM?',    'a' => 'Klik ikon Profil → Daftar Jadi Mitra, lengkapi formulir dan upload KTP & NIB.'],
                ['icon' => 'forum',         'q' => 'Cara menghubungi toko?',       'a' => 'Buka detail produk → klik Tanya via WhatsApp atau Chat Langsung.'],
                ['icon' => 'help',          'q' => 'Butuh bantuan lain?',           'a' => 'Hubungi Dinas Perdagangan via WhatsApp resmi atau kunjungi kantor dinas.'],
            ] as $item)
            <div class="flex items-start gap-3 p-3 bg-surface-container-low rounded-xl">
                <span class="material-symbols-outlined text-primary mt-0.5" style="font-size:18px">{{ $item['icon'] }}</span>
                <div>
                    <p class="text-xs font-bold text-on-surface">{{ $item['q'] }}</p>
                    <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">{{ $item['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-5 pb-5">
            <button onclick="document.getElementById('help-modal-bl').classList.add('hidden')"
                    class="block w-full text-center py-2.5 rounded-xl bg-primary text-white text-sm font-bold">Tutup</button>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
