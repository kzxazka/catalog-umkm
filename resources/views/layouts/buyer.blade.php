<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Portal UMKM' }}</title>
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
        /* Popup animation */
        [x-cloak] { display: none !important; }
        .popup-enter { animation: popIn .15s ease-out; }
        @keyframes popIn { from { opacity: 0; transform: scale(.95) translateY(-4px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        /* Bottom nav safe area */
        .bottom-safe { padding-bottom: env(safe-area-inset-bottom, 8px); }
    </style>
    {{ $head ?? '' }}
</head>
<body class="min-h-screen" x-data="{
    profileOpen: false,
    notifOpen: false,
    notifications: [
        @if(auth()->check() && auth()->user()->hasPendingMitraApplication())
        { icon: 'pending', text: 'Pengajuan mitra Anda sedang diproses', time: 'Baru', type: 'info' },
        @endif
        @if(auth()->check() && auth()->user()->isApprovedMitra())
        { icon: 'verified', text: 'Selamat! Pengajuan mitra Anda disetujui', time: 'Baru', type: 'success' },
        @endif
    ]
}">

{{-- ═══════════════════════════════════════════════════
     TOP NAVBAR — Buyer
     ═══════════════════════════════════════════════════ --}}
<header class="bg-primary sticky top-0 z-50 shadow-lg">
    <nav class="max-w-screen-xl mx-auto px-4 md:px-6 h-14 md:h-16 flex items-center justify-between">

        {{-- Brand --}}
        <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 shrink-0">
            <div class="w-8 h-8 md:w-9 md:h-9 bg-white/15 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-white" style="font-size:18px">storefront</span>
            </div>
            <div class="hidden sm:block">
                <span class="text-white font-bold text-sm leading-tight block">Portal UMKM</span>
                <span class="text-blue-200 text-[10px]">Dinas Perdagangan</span>
            </div>
        </a>

        {{-- Right icons --}}
        @auth
        <div class="flex items-center gap-1">

            {{-- Chat —riwayat chat buyer --}}
            <a href="{{ route('chat.buyer.inbox') }}"
               title="Pesan Saya"
               class="relative w-10 h-10 rounded-full flex items-center justify-center text-blue-200 hover:bg-white/10 hover:text-white transition-all">
                <span class="material-symbols-outlined" style="font-size:22px">forum</span>
            </a>

            {{-- Favorit — hidden di mobile (ada di bottom nav), visible di desktop --}}
            <a href="{{ route('catalog.index') }}?filter=favorit"
               title="Produk Favorit"
               class="relative hidden md:flex w-10 h-10 rounded-full items-center justify-center text-blue-200 hover:bg-white/10 hover:text-white transition-all">
                <span class="material-symbols-outlined" style="font-size:22px">favorite</span>
                @auth
                @php $favCount = count(auth()->user()->favorited_products ?? []); @endphp
                @if($favCount > 0)
                <span class="absolute top-1 right-1 w-4 h-4 bg-secondary rounded-full text-[9px] font-black text-white flex items-center justify-center">{{ $favCount > 9 ? '9+' : $favCount }}</span>
                @endif
                @endauth
            </a>

            {{-- Notifikasi --}}
            <div class="relative">
                <button @click="notifOpen = !notifOpen; profileOpen = false"
                        class="relative w-10 h-10 rounded-full flex items-center justify-center text-blue-200 hover:bg-white/10 hover:text-white transition-all">
                    <span class="material-symbols-outlined" style="font-size:22px">notifications</span>
                    @if(auth()->user()->hasPendingMitraApplication() || auth()->user()->isApprovedMitra())
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-400 rounded-full border border-primary"></span>
                    @endif
                </button>
                {{-- Notif Popup --}}
                <div x-show="notifOpen" x-cloak @click.outside="notifOpen = false"
                     class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-2xl border border-outline-variant overflow-hidden popup-enter z-50">
                    <div class="px-4 py-3 border-b border-outline-variant flex items-center justify-between">
                        <span class="font-bold text-sm text-on-surface">Notifikasi</span>
                        <button @click="notifOpen = false" class="text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined" style="font-size:18px">close</span>
                        </button>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <template x-if="notifications.length === 0">
                            <div class="py-8 text-center text-on-surface-variant text-sm">
                                <span class="material-symbols-outlined text-3xl block mb-2">notifications_none</span>
                                Belum ada notifikasi
                            </div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.text">
                            <div class="px-4 py-3 flex items-start gap-3 hover:bg-surface-container-low border-b border-outline-variant/50 last:border-0">
                                <div :class="notif.type === 'success' ? 'bg-green-100' : 'bg-blue-100'" class="w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-sm" :class="notif.type === 'success' ? 'text-green-600' : 'text-blue-600'" x-text="notif.icon"></span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-on-surface" x-text="notif.text"></p>
                                    <p class="text-[10px] text-on-surface-variant mt-0.5" x-text="notif.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="px-4 py-2 border-t border-outline-variant">
                        <a href="{{ route('mitra.status') }}" class="text-xs text-primary font-bold hover:underline">
                            Lihat status mitra →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Profile Avatar + Popup --}}
            <div class="relative ml-1">
                <button @click="profileOpen = !profileOpen; notifOpen = false"
                        class="w-9 h-9 rounded-full overflow-hidden border-2 border-white/30 hover:border-white/70 transition-all flex items-center justify-center bg-secondary text-white font-bold text-sm">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="" class="w-full h-full object-cover" />
                    @else
                        {{ auth()->user()->initials }}
                    @endif
                </button>

                {{-- Profile Popup --}}
                <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                     class="absolute right-0 top-12 w-72 bg-white rounded-2xl shadow-2xl border border-outline-variant overflow-hidden popup-enter z-50">

                    {{-- User info header --}}
                    <div class="px-5 py-4 bg-gradient-to-r from-primary to-primary-container">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white/40 flex items-center justify-center bg-secondary text-white font-bold">
                                @if(auth()->user()->avatar_path)
                                    <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="" class="w-full h-full object-cover" />
                                @else
                                    {{ auth()->user()->initials }}
                                @endif
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">{{ auth()->user()->nickname ?: explode(' ', auth()->user()->name)[0] }}</p>
                                <p class="text-blue-200 text-xs">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->isApprovedMitra())
                                <span class="inline-flex items-center gap-1 bg-green-500/20 text-green-300 text-[10px] font-bold px-2 py-0.5 rounded-full mt-1">
                                    <span class="material-symbols-outlined" style="font-size:10px">verified</span> Mitra UMKM
                                </span>
                                @elseif(auth()->user()->hasPendingMitraApplication())
                                <span class="inline-flex items-center gap-1 bg-amber-500/20 text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-full mt-1">
                                    <span class="material-symbols-outlined" style="font-size:10px">pending</span> Pengajuan Diproses
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 bg-white/10 text-blue-200 text-[10px] font-bold px-2 py-0.5 rounded-full mt-1">
                                    Buyer
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Menu --}}
                    <div class="py-2">
                        <a href="{{ route('buyer.profile') }}" @click="profileOpen = false"
                           class="flex items-center gap-3 px-5 py-3 hover:bg-surface-container-low text-on-surface text-sm transition-colors">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">person</span>
                            <span class="font-semibold">Profil Saya</span>
                        </a>
                        <a href="{{ route('mitra.status') }}" @click="profileOpen = false"
                           class="flex items-center gap-3 px-5 py-3 hover:bg-surface-container-low text-on-surface text-sm transition-colors">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">add_business</span>
                            <span class="font-semibold">Status Kemitraan</span>
                        </a>
                        @if(!auth()->user()->hasPendingMitraApplication() && !auth()->user()->isApprovedMitra())
                        <a href="{{ route('mitra.register') }}" @click="profileOpen = false"
                           class="flex items-center gap-3 px-5 py-3 hover:bg-surface-container-low text-secondary text-sm transition-colors">
                            <span class="material-symbols-outlined text-secondary" style="font-size:20px">store</span>
                            <span class="font-semibold">Daftar Jadi Mitra</span>
                        </a>
                        @endif

                        <div class="h-px bg-outline-variant mx-4 my-1"></div>

                        {{-- Bantuan --}}
                        <button onclick="document.getElementById('help-modal').classList.remove('hidden')"
                                class="w-full flex items-center gap-3 px-5 py-3 hover:bg-surface-container-low text-on-surface text-sm transition-colors text-left">
                            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">help</span>
                            <span class="font-semibold">Bantuan</span>
                        </button>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}" id="logout-form-popup">@csrf</form>
                        <button onclick="document.getElementById('logout-confirm').classList.remove('hidden')"
                                class="w-full flex items-center gap-3 px-5 py-3 hover:bg-red-50 text-error text-sm transition-colors text-left">
                            <span class="material-symbols-outlined text-error" style="font-size:20px">logout</span>
                            <span class="font-semibold">Keluar</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        @else
        {{-- Guest actions --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('login') }}" class="text-blue-200 hover:text-white text-sm font-bold px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="bg-secondary text-white text-sm font-bold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                Daftar
            </a>
        </div>
        @endauth
    </nav>
</header>

{{-- ═══════════════════════════════════════════════════
     MAIN SLOT
     ═══════════════════════════════════════════════════ --}}
<main class="pb-20 md:pb-8">
    {{ $slot }}
</main>

{{-- ═══════════════════════════════════════════════════
     BOTTOM NAV — Mobile Only
     ═══════════════════════════════════════════════════ --}}
<nav class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-outline-variant z-40 bottom-safe shadow-lg">
    <div class="flex justify-around items-stretch">
        @php 
            $currentRoute = Route::currentRouteName();
            $filter = request()->get('filter');
            $navItems = [
                [
                    'url' => route('catalog.index'), 
                    'icon' => 'storefront', 
                    'label' => 'Katalog', 
                    'active' => ($currentRoute === 'catalog.index' && $filter !== 'favorit')
                ],
                [
                    'url' => route('catalog.index') . '?filter=favorit', 
                    'icon' => 'favorite', 
                    'label' => 'Favorit', 
                    'active' => ($currentRoute === 'catalog.index' && $filter === 'favorit')
                ],
                [
                    'url' => auth()->check() ? route('mitra.status') : route('login'), 
                    'icon' => 'verified_user', 
                    'label' => 'Mitra', 
                    'active' => str_starts_with($currentRoute ?? '', 'mitra')
                ],
                [
                    'url' => auth()->check() ? route('buyer.profile') : route('login'), 
                    'icon' => 'person', 
                    'label' => 'Profil', 
                    'active' => str_starts_with($currentRoute ?? '', 'buyer.profile')
                ],
            ];
        @endphp
        @foreach($navItems as $item)
        <a href="{{ $item['url'] }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 transition-colors {{ $item['active'] ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined" style="font-size:22px">{{ $item['icon'] }}</span>
            <span class="text-[10px] font-bold">{{ $item['label'] }}</span>
        </a>
        @endforeach
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════
     MODAL: Konfirmasi Logout
     ═══════════════════════════════════════════════════ --}}
<div id="logout-confirm" class="hidden fixed inset-0 z-[100] flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('logout-confirm').classList.add('hidden')"></div>
    <div class="relative bg-white w-full sm:w-80 rounded-t-2xl sm:rounded-2xl p-6 shadow-2xl mx-4">
        <div class="text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-error" style="font-size:28px">logout</span>
            </div>
            <h3 class="text-base font-bold text-on-surface mb-1">Konfirmasi Keluar</h3>
            <p class="text-sm text-on-surface-variant mb-6">Apakah Anda yakin ingin keluar dari akun ini?</p>
            <div class="flex gap-3">
                <button onclick="document.getElementById('logout-confirm').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-xl border border-outline-variant text-on-surface font-bold text-sm hover:bg-surface-container-low transition-colors">
                    Batal
                </button>
                <button onclick="document.getElementById('logout-form-popup').submit()"
                        class="flex-1 py-2.5 rounded-xl bg-error text-white font-bold text-sm hover:opacity-90 transition-opacity">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL: Bantuan
     ═══════════════════════════════════════════════════ --}}
<div id="help-modal" class="hidden fixed inset-0 z-[100] flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('help-modal').classList.add('hidden')"></div>
    <div class="relative bg-white w-full sm:w-96 rounded-t-2xl sm:rounded-2xl shadow-2xl mx-4 overflow-hidden">
        <div class="bg-primary px-5 py-4 flex items-center justify-between">
            <h3 class="text-white font-bold text-sm">Pusat Bantuan</h3>
            <button onclick="document.getElementById('help-modal').classList.add('hidden')" class="text-blue-200 hover:text-white">
                <span class="material-symbols-outlined" style="font-size:20px">close</span>
            </button>
        </div>
        <div class="p-5 space-y-3">
            @foreach([
                ['icon' => 'storefront', 'q' => 'Cara menjadi Mitra UMKM?', 'a' => 'Klik Profil → Daftar Jadi Mitra, lengkapi formulir dan upload dokumen KTP & NIB.'],
                ['icon' => 'forum', 'q' => 'Cara menghubungi toko?', 'a' => 'Buka detail produk → klik Tanya via WhatsApp atau Chat Langsung dengan Toko.'],
                ['icon' => 'help', 'q' => 'Butuh bantuan lain?', 'a' => 'Hubungi Dinas Perdagangan melalui WhatsApp resmi atau datang ke kantor dinas.'],
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
            <a href="{{ route('catalog.index') }}"
               onclick="document.getElementById('help-modal').classList.add('hidden')"
               class="block text-center py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:opacity-90 transition-opacity">
                Tutup
            </a>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
