<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'galeriukmbdl') }} - Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('img/disperdaglogo.png') }}" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "inverse-primary": "#b2c5fd",
                        "primary-fixed": "#dae2ff",
                        "error-container": "#ffdad6",
                        "on-secondary-container": "#722200",
                        "on-background": "#0b1c30",
                        "surface-container": "#e5eeff",
                        "secondary-container": "#fc895f",
                        "on-surface-variant": "#44464f",
                        "on-error": "#ffffff",
                        "surface-tint": "#4a5d8e",
                        "primary": "#011a48",
                        "on-surface": "#0b1c30",
                        "primary-container": "#1b305e",
                        "surface-variant": "#d3e4fe",
                        "surface-bright": "#f8f9ff",
                        "on-primary-container": "#8599cd",
                        "on-tertiary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "outline": "#757780",
                        "error": "#ba1a1a",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c5c6d0",
                        "inverse-surface": "#213145",
                        "surface-dim": "#cbdbf5",
                        "surface": "#f8f9ff",
                        "on-error-container": "#93000a",
                        "background": "#f8f9ff",
                        "on-secondary": "#ffffff",
                        "secondary": "#9e421e",
                        "inverse-on-surface": "#eaf1ff",
                        "surface-container-highest": "#d3e4fe",
                        "surface-container-low": "#eff4ff",
                        "surface-container-high": "#dce9ff",
                        "primary-fixed-dim": "#b2c5fd",
                    },
                    fontFamily: {
                        "sans": ["Public Sans", "sans-serif"],
                        "headline-md": ["Public Sans"],
                        "body-md": ["Public Sans"],
                        "label-md": ["Public Sans"],
                        "headline-lg": ["Public Sans"],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f8f9ff; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .bg-batik-texture {
            background-color: #f8f9ff;
            background-image: radial-gradient(#011a48 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            opacity: 0.03;
        }
        [x-cloak] { display: none !important; }
        .popup-enter { animation: popIn .15s ease-out; }
        @keyframes popIn { from { opacity: 0; transform: scale(.95) translateY(-4px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        /* Premium Shimmer Loading Skeleton */
        .shimmer-bg {
            background: linear-gradient(90deg, #eff4ff 25%, #d3e4fe 50%, #eff4ff 75%);
            background-size: 200% 100%;
            animation: shimmer 1.6s infinite linear;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface min-h-screen flex"
      x-data="{
          sidebarOpen: false,
          profileOpen: false,
          logoutOpen: false
      }">

    {{-- ═══ SIDEBAR (Admin & Owner only) ═══ --}}
    @php $isAdminOrOwner = auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin', 'owner']); @endphp

    @if($isAdminOrOwner)
    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak
         class="fixed inset-0 z-40 bg-black/50 md:hidden"
         @click="sidebarOpen = false"></div>

    @include('layouts.navigation')
    @endif

    {{-- ═══ MAIN CONTENT ═══ --}}
    <main class="{{ $isAdminOrOwner ? 'flex-1 md:ml-64' : 'flex-1' }} flex flex-col relative min-h-screen">
        <div class="absolute inset-0 bg-batik-texture pointer-events-none"></div>

        {{-- ═══ TOP NAVBAR ═══ --}}
        <header class="flex justify-between items-center px-4 md:px-8 w-full sticky top-0 z-30 bg-surface border-b border-outline-variant h-16 shrink-0">
            <div class="flex items-center gap-3">
                {{-- Hamburger (mobile, hanya untuk admin & owner) --}}
                @if($isAdminOrOwner)
                <button @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container rounded-full transition-colors">
                    <span class="material-symbols-outlined" style="font-size:24px">menu</span>
                </button>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                {{-- Admin: search bar --}}
                <div class="relative hidden lg:block w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size:18px">search</span>
                    <input class="w-full pl-9 pr-4 py-2 bg-surface-container border border-outline-variant rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                           placeholder="Cari UMKM, nama, atau pemilik..." type="text"/>
                </div>
                @else
                <h1 class="font-bold text-primary text-base hidden sm:block">Portal UMKM Indonesia</h1>
                @endif
            </div>

            <div class="flex items-center gap-1">
                {{-- Notifikasi --}}
                <button class="w-10 h-10 flex items-center justify-center hover:bg-surface-container rounded-full text-on-surface-variant transition-colors relative">
                    <span class="material-symbols-outlined" style="font-size:22px">notifications</span>
                </button>

                {{-- Profile popup trigger --}}
                <div class="relative ml-1">
                    <button @click="profileOpen = !profileOpen; logoutOpen = false"
                            class="flex items-center gap-2 pl-3 border-l border-outline-variant hover:bg-surface-container rounded-lg px-2 py-1.5 transition-colors">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-primary leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider">
                                {{ in_array(auth()->user()->role, ['admin','superadmin']) ? 'Admin Dinas' : 'Owner UMKM' }}
                            </p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant hidden sm:block" style="font-size:18px">expand_more</span>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div x-show="profileOpen" x-cloak
                         @click.outside="profileOpen = false"
                         class="absolute right-0 top-12 w-56 bg-white rounded-2xl shadow-2xl border border-outline-variant overflow-hidden popup-enter z-50">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-primary to-primary-container px-4 py-3">
                            <p class="text-white font-bold text-sm truncate">{{ Auth::user()->name }}</p>
                            <p class="text-blue-200 text-xs truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1 text-[10px] font-bold text-blue-100 bg-white/10 px-2 py-0.5 rounded-full">
                                {{ in_array(auth()->user()->role, ['admin','superadmin']) ? 'Admin Dinas Perdagangan' : 'Owner UMKM' }}
                            </span>
                        </div>
                        <div class="py-1">
                            @if(auth()->user()->role === 'owner')
                            <a href="{{ auth()->user()->store ? route('store.public', auth()->user()->store->slug) : '#' }}"
                               target="_blank"
                               class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low text-on-surface text-sm transition-colors">
                                <span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px">open_in_new</span>
                                <span class="font-semibold">Lihat Etalase</span>
                            </a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low text-on-surface text-sm transition-colors">
                                <span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px">manage_accounts</span>
                                <span class="font-semibold">Pengaturan Akun</span>
                            </a>
                            <div class="h-px bg-outline-variant/50 mx-3 my-1"></div>
                            <button @click="profileOpen = false; logoutOpen = true"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 text-error text-sm transition-colors text-left">
                                <span class="material-symbols-outlined text-error" style="font-size:18px">logout</span>
                                <span class="font-semibold">Keluar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ═══ CONTENT SLOT ═══ --}}
        <div class="p-4 md:p-8 space-y-8 relative z-10 flex-1">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <footer class="mt-auto px-8 py-4 border-t border-outline-variant text-center relative z-10">
            <p class="text-[11px] text-on-surface-variant">© 2024 Portal UMKM — Dinas Perdagangan</p>
        </footer>
    </main>

    {{-- ═══ BOTTOM NAV (Owner — Mobile only) ═══ --}}
    @if(auth()->check() && auth()->user()->role === 'owner')
    <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-outline-variant z-40 shadow-lg">
        <div class="flex" style="padding-bottom: env(safe-area-inset-bottom, 0px)">
            @php $cr = Route::currentRouteName() ?? ''; @endphp
            <a href="{{ route('dashboard') }}"
               class="flex-1 flex flex-col items-center justify-center py-2.5 gap-0.5 {{ $cr === 'dashboard' ? 'text-primary' : 'text-on-surface-variant' }} transition-colors">
                <span class="material-symbols-outlined" style="font-size:22px">dashboard</span>
                <span class="text-[10px] font-bold">Dashboard</span>
            </a>
            <a href="{{ route('owner.products') }}"
               class="flex-1 flex flex-col items-center justify-center py-2.5 gap-0.5 {{ $cr === 'owner.products' ? 'text-primary' : 'text-on-surface-variant' }} transition-colors">
                <span class="material-symbols-outlined" style="font-size:22px">inventory_2</span>
                <span class="text-[10px] font-bold">Produk</span>
            </a>
            <a href="{{ route('owner.settings') }}"
               class="flex-1 flex flex-col items-center justify-center py-2.5 gap-0.5 {{ $cr === 'owner.settings' ? 'text-primary' : 'text-on-surface-variant' }} transition-colors">
                <span class="material-symbols-outlined" style="font-size:22px">settings</span>
                <span class="text-[10px] font-bold">Pengaturan</span>
            </a>
            {{-- Hamburger — buka sidebar --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="flex-1 flex flex-col items-center justify-center py-2.5 gap-0.5 text-on-surface-variant transition-colors"
                    :class="sidebarOpen ? 'text-primary' : ''">
                <span class="material-symbols-outlined" style="font-size:22px">menu</span>
                <span class="text-[10px] font-bold">Menu</span>
            </button>
        </div>
    </nav>
    @endif


    {{-- ═══ MODAL: Logout Konfirmasi ═══ --}}
    <div x-show="logoutOpen" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="logoutOpen = false"></div>
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-error" style="font-size:28px">logout</span>
                </div>
                <h3 class="text-base font-bold text-on-surface mb-1">Konfirmasi Keluar</h3>
                <p class="text-sm text-on-surface-variant mb-6">Yakin ingin keluar dari sesi ini?</p>
                <div class="flex gap-3">
                    <button @click="logoutOpen = false"
                            class="flex-1 py-2.5 rounded-xl border-2 border-outline-variant text-on-surface font-bold text-sm hover:bg-surface-container transition-colors">
                        Batal
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-error text-white font-bold text-sm hover:opacity-90 transition-opacity">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
