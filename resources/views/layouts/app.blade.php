<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Portal UMKM') }} - Dashboard</title>

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
                        "on-tertiary-container": "#c68e5c",
                        "on-primary-fixed-variant": "#324574",
                        "primary-fixed-dim": "#b2c5fd",
                        "surface-container": "#e5eeff",
                        "secondary-container": "#fc895f",
                        "tertiary-fixed": "#ffdcc0",
                        "on-surface-variant": "#44464f",
                        "on-error": "#ffffff",
                        "surface-tint": "#4a5d8e",
                        "primary": "#011a48",
                        "on-surface": "#0b1c30",
                        "tertiary-fixed-dim": "#f7ba84",
                        "secondary-fixed-dim": "#ffb59c",
                        "primary-container": "#1b305e",
                        "surface-variant": "#d3e4fe",
                        "surface-bright": "#f8f9ff",
                        "on-secondary-fixed-variant": "#7f2b07",
                        "on-primary-container": "#8599cd",
                        "on-tertiary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "outline": "#757780",
                        "on-secondary-fixed": "#380c00",
                        "error": "#ba1a1a",
                        "on-tertiary-fixed": "#2d1600",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c5c6d0",
                        "inverse-surface": "#213145",
                        "surface-dim": "#cbdbf5",
                        "surface": "#f8f9ff",
                        "on-error-container": "#93000a",
                        "secondary-fixed": "#ffdbcf",
                        "background": "#f8f9ff",
                        "tertiary-container": "#4d2900",
                        "tertiary": "#2f1700",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed-variant": "#673d12",
                        "secondary": "#9e421e",
                        "on-primary-fixed": "#001946",
                        "inverse-on-surface": "#eaf1ff",
                        "surface-container-highest": "#d3e4fe",
                        "surface-container-low": "#eff4ff",
                        "surface-container-high": "#dce9ff"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "container-max": "1280px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "base": "8px",
                        "margin-desktop": "40px"
                    },
                    fontFamily: {
                        "headline-md": ["Public Sans"],
                        "body-md": ["Public Sans"],
                        "headline-lg-mobile": ["Public Sans"],
                        "body-lg": ["Public Sans"],
                        "label-sm": ["Public Sans"],
                        "display-lg": ["Public Sans"],
                        "label-md": ["Public Sans"],
                        "headline-lg": ["Public Sans"]
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
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface min-h-screen flex" x-data="{ sidebarOpen: false }">
    @include('layouts.navigation')

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 flex flex-col relative min-h-screen">
        <!-- Batik Overlay -->
        <div class="absolute inset-0 bg-batik-texture pointer-events-none"></div>

        <!-- TopNavBar -->
        <header class="flex justify-between items-center px-4 md:px-10 w-full sticky top-0 z-30 bg-surface border-b border-outline-variant h-16 shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-on-surface-variant hover:bg-surface-container rounded-full">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                @if(auth()->user()->role !== 'superadmin')
                <h1 class="font-headline-md text-headline-md font-bold text-primary hidden sm:block">Portal UMKM Indonesia</h1>
                @else
                <div class="relative hidden lg:block w-96">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-surface-container border border-outline-variant rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Cari UMKM, NIK, atau Pemilik..." type="text"/>
                </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <button class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant relative">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="flex items-center gap-3 ml-2 pl-4 border-l border-outline-variant">
                    <div class="text-right hidden sm:block">
                        <p class="font-label-md text-primary font-bold">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider">{{ auth()->user()->role === 'superadmin' ? 'Admin Dinas' : 'Owner' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold border-2 border-primary-fixed-dim">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Canvas -->
        <div class="p-4 md:p-8 space-y-8 relative z-10 flex-1 overflow-y-auto">
            {{ $slot }}
        </div>
        
        <!-- Footer -->
        <footer class="mt-auto px-10 py-6 border-t border-outline-variant text-center relative z-10">
            <p class="text-[12px] text-on-surface-variant">
                © 2023 Portal UMKM Indonesia - Direktorat Jenderal Koperasi dan Usaha Kecil Menengah.
            </p>
        </footer>
    </main>
</body>
</html>
