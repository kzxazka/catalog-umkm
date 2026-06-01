<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'galeriukmbdl') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/disperdaglogo.png') }}" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-on-surface": "#eaf1ff",
                        "on-tertiary-fixed-variant": "#673d12",
                        "tertiary-fixed": "#ffdcc0",
                        "on-background": "#0b1c30",
                        "on-primary-container": "#8599cd",
                        "tertiary-fixed-dim": "#f7ba84",
                        "primary-fixed": "#dae2ff",
                        "outline": "#757780",
                        "on-primary": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed-dim": "#ffb59c",
                        "background": "#f8f9ff",
                        "on-error-container": "#93000a",
                        "error-container": "#ffdad6",
                        "primary": "#011a48",
                        "on-secondary-fixed": "#380c00",
                        "surface-container-lowest": "#ffffff",
                        "surface-dim": "#cbdbf5",
                        "on-secondary": "#ffffff",
                        "error": "#ba1a1a",
                        "inverse-surface": "#213145",
                        "on-surface": "#0b1c30",
                        "on-primary-fixed": "#001946",
                        "on-surface-variant": "#44464f",
                        "on-primary-fixed-variant": "#324574",
                        "secondary": "#9e421e",
                        "on-tertiary-fixed": "#2d1600",
                        "surface": "#f8f9ff",
                        "outline-variant": "#c5c6d0",
                        "on-secondary-container": "#722200",
                        "surface-container-high": "#dce9ff",
                        "secondary-fixed": "#ffdbcf",
                        "tertiary-container": "#4d2900",
                        "on-secondary-fixed-variant": "#7f2b07",
                        "on-error": "#ffffff",
                        "inverse-primary": "#b2c5fd",
                        "on-tertiary-container": "#c68e5c",
                        "surface-container": "#e5eeff",
                        "surface-tint": "#4a5d8e",
                        "surface-container-highest": "#d3e4fe",
                        "surface-bright": "#f8f9ff",
                        "primary-fixed-dim": "#b2c5fd",
                        "surface-variant": "#d3e4fe",
                        "secondary-container": "#fc895f",
                        "primary-container": "#1b305e",
                        "tertiary": "#2f1700"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-max": "1280px",
                        "base": "8px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "margin-desktop": "40px"
                    },
                    "fontFamily": {
                        "headline-lg": ["Public Sans"],
                        "label-md": ["Public Sans"],
                        "label-sm": ["Public Sans"],
                        "display-lg": ["Public Sans"],
                        "headline-lg-mobile": ["Public Sans"],
                        "body-lg": ["Public Sans"],
                        "headline-md": ["Public Sans"],
                        "body-md": ["Public Sans"]
                    },
                    "fontSize": {
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: "Public Sans", sans-serif
        }

        .batik-overlay {
            background-image: url(https://lh3.googleusercontent.com/aida-public/AB6AXuATtzlcbxEzv-fFLtPT6Jg_fU2alTS6Ot4pD2f8XemiOaR0vR4nM5XgUniVp8b8G7Dj4E_qKrStyLIEKU-4A76DlvwDBakBlDOe3HBAkXFCEYQDxO5V9E8PSy4lLBI_oYxBSlLBUaq9cTIjVihgx5ytlC8p9d-lhMBryroVRjwSUgxRy52HRSLsjYp-cJMqxWMseGvaRzoIuqC5g96DNUaWYLWAkLZ0UsFNcH0gIxZSd4xa1bxTt3UkYSASGaGiyqyUJHDnTHn9ij0);
            opacity: 0.03
        }
    </style>
</head>

<body class="bg-background text-on-surface min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">
    <!-- TopNavBar Shell -->
    <header class="bg-surface border-b border-outline-variant fixed top-0 left-0 right-0 z-50 shadow-sm">
        <nav class="flex justify-between items-center w-full px-4 md:px-10 py-3 md:py-4 max-w-screen-xl mx-auto">
            <a href="{{ route('catalog.index') }}" class="flex items-center gap-2">
                <img src="{{ asset('img/disperdaglogo.png') }}" class="w-8 h-8 object-contain" alt="Logo Disperdag">
                <span class="font-bold text-primary text-sm md:text-base">Galeri UKM BDL</span>
            </a>
            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-6">
                <a class="text-sm text-on-surface-variant hover:text-secondary transition-colors font-semibold"
                    href="{{ route('catalog.index') }}">Beranda</a>
                <a class="text-sm text-on-surface-variant hover:text-secondary transition-colors font-semibold"
                    href="#">Layanan</a>
                <a class="text-sm text-primary font-bold border-b-2 border-primary pb-0.5" href="#">Bantuan</a>
            </div>
            {{-- Mobile hamburger --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden w-9 h-9 rounded-lg flex items-center justify-center text-primary hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined" x-text="mobileMenuOpen ? 'close' : 'menu'"
                    style="font-size:22px">menu</span>
            </button>
        </nav>
        {{-- Mobile dropdown menu --}}
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden bg-surface border-t border-outline-variant shadow-lg">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('catalog.index') }}" @click="mobileMenuOpen = false"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-primary" style="font-size:18px">storefront</span>
                    Beranda / Katalog
                </a>
                <a href="{{ route('register') }}" @click="mobileMenuOpen = false"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-primary" style="font-size:18px">person_add</span>
                    Daftar Akun
                </a>
                <a href="#" @click="mobileMenuOpen = false"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-primary" style="font-size:18px">help</span>
                    Bantuan
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content: Login Split Screen -->
    <main class="flex-grow flex pt-16">
        <div class="w-full grid grid-cols-1 md:grid-cols-2">
            <!-- Left Side: Login Form -->
            <section
                class="flex flex-col justify-center items-center px-margin-mobile md:px-20 py-12 bg-surface relative">
                <div class="batik-overlay absolute inset-0 pointer-events-none"></div>
                <div class="w-full max-w-md z-10">
                    {{ $slot }}
                </div>
            </section>

            <!-- Right Side: Visual Content -->
            <section class="hidden md:block relative overflow-hidden bg-primary">
                <img class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-60"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBN0HV6kh4-eWiAS9VDR5NjnWrr65CopIRgfqINqT2PvKrPNvusfngM2idGSkxPXzPo9PANS7zmbLzjkdLmE06R9qdwpmmE_Imv5mZeuWlMlGJPf1Nn_O7TW5JtkXnFDIR_VUip4OI74EUItj3sSFUazcJCubFpzXdvJtTX30D8VFOZ9F8wOaKp3a9js-B6pUGU_sAA37YP_Uv0vqcV9K6kPvbqgPLwVHr-T5Ikq2iFdTk8LyvT5cdEOx4JZ0IAC1OVa3A0lU_qRic" />
                <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/40 to-transparent"></div>
                <div class="absolute bottom-20 left-16 right-16 text-white">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-12 h-1 bg-secondary rounded-full"></span>
                        <span class="font-label-md text-label-md tracking-widest uppercase">Ekosistem Digital
                            UMKM</span>
                    </div>
                    <h2 class="font-display-lg text-display-lg mb-6 leading-tight">Membangun Masa Depan Ekonomi
                        Indonesia Melalui UMKM.</h2>
                    <p class="font-body-lg text-body-lg text-inverse-on-surface opacity-90 max-w-lg">
                        Dapatkan akses ke pendanaan, pelatihan sertifikasi, dan jaringan pasar global dalam satu
                        platform terintegrasi untuk pertumbuhan bisnis Anda.
                    </p>
                </div>
                <div class="absolute top-10 right-10">
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-xl border border-white/20 shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="bg-secondary p-3 rounded-full">
                                <span class="material-symbols-outlined text-on-primary">trending_up</span>
                            </div>
                            <div>
                                <p class="text-white font-bold text-headline-md">30+ UMKM</p>
                                <p class="text-white/80 font-label-sm text-label-sm">UMKM Terdaftar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer Shell -->
    <footer class="bg-surface-container border-t border-outline-variant">
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-gutter w-full px-margin-mobile md:px-margin-desktop py-12 max-w-container-max mx-auto">
            <div class="space-y-6">
                <span class="font-headline-md text-headline-md font-bold text-primary">Portal UMKM Indonesia</span>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-md">
                    Kementerian Koperasi dan UKM berkomitmen untuk mendigitalisasi dan memperkuat daya saing pelaku
                    usaha kecil dan menengah di seluruh nusantara.
                </p>
                <p class="font-label-sm text-label-sm text-on-surface-variant">
                    © 2024 Kementerian Koperasi dan UKM Republik Indonesia. Seluruh Hak Cipta Dilindungi.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-4">
                    <h4 class="font-label-md text-label-md text-on-surface font-bold">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary underline transition-all duration-200"
                                href="#">Tentang Kami</a></li>
                        <li><a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary underline transition-all duration-200"
                                href="#">Peta Situs</a></li>
                        <li><a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary underline transition-all duration-200"
                                href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h4 class="font-label-md text-label-md text-on-surface font-bold">Legalitas</h4>
                    <ul class="space-y-2">
                        <li><a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary underline transition-all duration-200"
                                href="#">Kebijakan Privasi</a></li>
                        <li><a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary underline transition-all duration-200"
                                href="#">Syarat &amp; Ketentuan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>