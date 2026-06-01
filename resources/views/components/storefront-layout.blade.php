<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'galeriukmbdl') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('img/disperdaglogo.png') }}" />
        
        @isset($meta)
            {{ $meta }}
        @endisset

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
        
        @stack('styles')
    </head>
    <body class="antialiased bg-white text-black flex flex-col min-h-screen">
        <div class="flex-grow">
            {{ $slot }}
        </div>
        
        <!-- Footer Resmi Platform -->
        <footer class="bg-black text-white py-12 mt-20 border-t-8 border-indigo-600">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-black text-2xl uppercase tracking-widest mb-4">Catalog UMKM</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Platform resmi katalog belanja fashion dan produk unggulan UMKM di bawah naungan Dinas Perdagangan Kota Bandar Lampung.
                    </p>
                    <div class="flex gap-4">
                        <span class="px-3 py-1 bg-white text-black text-xs font-bold uppercase tracking-wider">Terverifikasi</span>
                        <span class="px-3 py-1 bg-white text-black text-xs font-bold uppercase tracking-wider">Aman</span>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4 uppercase tracking-wider">Informasi Kontak Dinas</h4>
                    <ul class="text-sm text-gray-400 space-y-2">
                        <li>📍 Jl. Cut Mutia No.44, Teluk Betung, Bandar Lampung</li>
                        <li>📞 (0721) 1234567 (Call Center)</li>
                        <li>✉️ disdag@bandarlampungkota.go.id</li>
                        <li class="mt-4 text-indigo-400 font-semibold cursor-pointer hover:text-white transition">Layanan Pengaduan Konsumen &rarr;</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4 uppercase tracking-wider">Link Cepat</h4>
                    <ul class="text-sm text-gray-400 space-y-2">
                        <li><a href="#" class="hover:text-white transition">Cara Daftar Menjadi Mitra UMKM</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat dan Ketentuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Pusat Bantuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-gray-800 text-sm text-gray-500 flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} Dinas Perdagangan Kota Bandar Lampung. All rights reserved.</p>
                <p class="mt-2 md:mt-0 uppercase tracking-widest font-bold text-xs">Bangga Buatan Indonesia</p>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
