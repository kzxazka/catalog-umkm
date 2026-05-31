<x-app-layout>
    @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
            <div>
                <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Dashboard Pengelolaan UMKM</h1>
                <p class="font-body-md text-on-surface-variant">Pantau dan kelola verifikasi pendaftaran UMKM secara real-time.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-4 py-2.5 bg-surface-container border border-outline-variant rounded-lg font-label-md text-sm font-bold text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined">calendar_today</span>
                    <span>Bulan Ini</span>
                </button>
                <button class="flex items-center justify-center w-10 h-10 bg-primary text-on-primary rounded-lg hover:bg-primary-container hover:text-on-primary-container transition-all">
                    <span class="material-symbols-outlined">refresh</span>
                </button>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm flex items-start justify-between group hover:border-primary transition-all">
                <div>
                    <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Total UMKM Terdaftar</p>
                    <h3 class="text-4xl font-bold text-primary">12.482</h3>
                    <p class="text-[12px] text-on-surface-variant mt-2 flex items-center gap-1">
                        <span class="text-green-600 font-bold">+12%</span> dibanding bulan lalu
                    </p>
                </div>
                <div class="p-3 bg-primary-fixed rounded-lg text-primary">
                    <span class="material-symbols-outlined">groups</span>
                </div>
            </div>
            
            <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm flex items-start justify-between border-l-4 border-l-secondary transition-all">
                <div>
                    <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Menunggu Verifikasi</p>
                    <div class="flex items-center gap-3">
                        <h3 class="text-4xl font-bold text-on-surface">154</h3>
                        <span class="px-2 py-0.5 bg-error-container text-on-error-container text-[10px] font-bold rounded-full uppercase">Urgent</span>
                    </div>
                    <p class="text-[12px] text-on-surface-variant mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">history</span> Rata-rata 4.2 jam
                    </p>
                </div>
                <div class="p-3 bg-secondary-fixed rounded-lg text-secondary">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm flex items-start justify-between transition-all">
                <div>
                    <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Telah Terverifikasi</p>
                    <h3 class="text-4xl font-bold text-on-surface">11.908</h3>
                    <p class="text-[12px] text-on-surface-variant mt-2 flex items-center gap-1">
                        <span class="text-green-600 font-bold">95.4%</span> Tingkat kelulusan
                    </p>
                </div>
                <div class="p-3 bg-surface-container-high rounded-lg text-primary">
                    <span class="material-symbols-outlined">verified</span>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm flex items-start justify-between transition-all">
                <div>
                    <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Wilayah Aktif</p>
                    <h3 class="text-4xl font-bold text-on-surface">24</h3>
                    <p class="text-[12px] text-on-surface-variant mt-2">Kecamatan terjangkau</p>
                </div>
                <div class="p-3 bg-tertiary-fixed rounded-lg text-tertiary">
                    <span class="material-symbols-outlined">map</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Queue Table -->
            <section class="lg:col-span-8 bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center bg-white">
                    <h2 class="font-headline-md text-xl font-bold text-primary">Antrean Verifikasi Terbaru</h2>
                    <button class="text-primary text-sm font-bold hover:underline flex items-center gap-1">
                        Lihat Semua <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4 text-sm font-bold text-on-surface-variant">Nama Usaha</th>
                                <th class="px-6 py-4 text-sm font-bold text-on-surface-variant">Pemilik</th>
                                <th class="px-6 py-4 text-sm font-bold text-on-surface-variant">Kategori</th>
                                <th class="px-6 py-4 text-sm font-bold text-on-surface-variant">Tgl Pengajuan</th>
                                <th class="px-6 py-4 text-sm font-bold text-on-surface-variant text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-surface-container flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined">restaurant</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface text-sm">Bakery Lezat Jaya</p>
                                            <p class="text-xs text-on-surface-variant">SKU-982103</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface">Bambang Susanto</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-surface-variant text-primary text-[11px] font-bold rounded-full">Kuliner</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface">14 Nov 2023</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        <button class="flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-800 hover:bg-green-200 rounded text-xs font-bold transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">check_circle</span> Approve
                                        </button>
                                        <button class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-800 hover:bg-red-200 rounded text-xs font-bold transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">cancel</span> Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Manual Registration Form -->
            <section class="lg:col-span-4 space-y-6">
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-primary mb-6">Registrasi UMKM Manual</h2>
                    <form class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-on-surface">Nama Pemilik</label>
                            <input class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm" placeholder="Contoh: Eka Saputra" type="text"/>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-on-surface">Nama Usaha</label>
                            <input class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm" placeholder="Contoh: Kedai Kopi Rakyat" type="text"/>
                        </div>
                        <button class="w-full mt-4 bg-primary text-on-primary py-3 rounded-lg text-sm font-bold hover:bg-primary-container hover:text-on-primary-container transition-all shadow-md" type="button">
                            Daftarkan UMKM Baru
                        </button>
                    </form>
                </div>
            </section>
        </div>
    @else
        <!-- UMKM Dashboard -->
        <nav class="flex text-xs font-bold text-on-surface-variant items-center gap-2 mb-4">
            <a class="hover:text-primary" href="#">Beranda</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-primary font-bold">Manajemen Katalog</span>
        </nav>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-primary mb-2">Halo, {{ Auth::user()->name }}</h2>
                <p class="text-on-surface-variant">Kelola katalog digital dan jangkauan media sosial Anda.</p>
            </div>
            <a href="{{ route('owner.products') }}" class="bg-secondary text-on-secondary px-6 py-3 rounded-lg text-sm font-bold flex items-center gap-2 hover:opacity-90 transition-opacity shadow-sm">
                <span class="material-symbols-outlined">add</span>
                Tambah Produk Baru
            </a>
        </div>

        @if(!$store)
            <div class="bg-surface-container-high border-l-4 border-primary text-primary p-6 mb-6 rounded-r-xl" role="alert">
                <p class="font-bold text-lg mb-1">Perhatian</p>
                <p>Anda belum memiliki profil toko yang lengkap. Silakan hubungi Admin Dinas Perdagangan untuk proses verifikasi.</p>
            </div>
        @else
            <!-- Dashboard Overview -->
            <section class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-primary">Performa Katalog & Insight</h3>
                    <a href="{{ route('dashboard.export') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-md shadow transition flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-[18px]">download</span> Export CSV
                    </a>
                </div>
                
                @php
                    $totalProducts = count($products);
                    $totalClicksAll = 0;
                    foreach($products as $product) {
                        $totalClicksAll += array_sum($product->clicks ?? []);
                    }
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 rounded-lg bg-surface-container-low border border-outline-variant/30 relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 opacity-5 rotate-12">
                            <span class="material-symbols-outlined text-6xl">inventory_2</span>
                        </div>
                        <p class="text-[12px] text-on-surface-variant font-bold">Total Produk Aktif</p>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-bold text-primary tracking-tight">{{ $totalProducts }}</span>
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-2">Katalog yang ditampilkan ke publik</p>
                    </div>

                    <div class="p-4 rounded-lg bg-surface-container-low border border-outline-variant/30 relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 opacity-5 rotate-12">
                            <span class="material-symbols-outlined text-6xl">ads_click</span>
                        </div>
                        <p class="text-[12px] text-on-surface-variant font-bold">Klik Tautan Eksternal</p>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-bold text-primary tracking-tight">{{ $totalClicksAll }}</span>
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-2">Klik ke WA, IG, & Marketplace</p>
                    </div>

                    <div class="p-4 rounded-lg bg-surface-container-low border border-outline-variant/30 relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 opacity-5 rotate-12">
                            <span class="material-symbols-outlined text-6xl">visibility</span>
                        </div>
                        <p class="text-[12px] text-on-surface-variant font-bold">Total Kunjungan Toko</p>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-bold text-primary tracking-tight">-</span>
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-2">Segera hadir</p>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Insight List -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-primary">Insight Produk Detail</h3>
                        <a href="{{ route('owner.products') }}" class="text-primary text-sm font-bold flex items-center gap-1 hover:underline">
                            Kelola Produk <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    </div>
                    
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-surface-container-low border-b border-outline-variant">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">WhatsApp</th>
                                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Marketplace</th>
                                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant">
                                    @forelse($products as $product)
                                        @php
                                            $clicks = $product->clicks ?? [];
                                            $waClicks = $clicks['WhatsApp'] ?? 0;
                                            $otherMarketplaceClicks = array_sum(array_diff_key($clicks, array_flip(['WhatsApp'])));
                                            $totalClicks = array_sum($clicks);
                                        @endphp
                                        <tr class="hover:bg-surface-container-low transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/products/' . $product->images[0]) }}" alt="">
                                                        </div>
                                                    @else
                                                        <div class="flex-shrink-0 h-10 w-10 bg-surface-variant rounded flex items-center justify-center">
                                                            <span class="material-symbols-outlined text-on-surface-variant">image</span>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-on-surface">
                                                            {{ $product->name }}
                                                        </div>
                                                        <div class="text-xs text-on-surface-variant mt-1">
                                                            {{ $product->category }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    {{ $waClicks }} klik
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                    {{ $otherMarketplaceClicks }} klik
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-primary font-black">
                                                {{ $totalClicks }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">
                                                Belum ada produk yang ditambahkan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar: External Links Management & QR -->
                <div class="space-y-6">
                    <section class="bg-surface-container-lowest p-6 rounded-xl border-2 border-primary shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-primary uppercase tracking-wider">Tautan Cepat</h3>
                            <span class="px-2 py-0.5 bg-primary text-on-primary text-[10px] rounded-full font-bold">Aktif</span>
                        </div>
                        <p class="text-[11px] text-on-surface-variant mb-4">Tautan ini dikonfigurasi di pengaturan toko Anda.</p>
                        
                        <div class="space-y-3">
                            @if($store->whatsapp)
                            <div class="flex items-center gap-3 p-3 rounded-lg border border-outline-variant">
                                <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined">chat</span>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-on-surface">WhatsApp</p>
                                    <p class="text-xs text-on-surface-variant truncate">{{ $store->whatsapp }}</p>
                                </div>
                            </div>
                            @endif

                            @if($store->instagram)
                            <div class="flex items-center gap-3 p-3 rounded-lg border border-outline-variant">
                                <div class="w-10 h-10 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined">photo_camera</span>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-on-surface">Instagram</p>
                                    <p class="text-xs text-on-surface-variant truncate">{{ $store->instagram }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </section>

                    <!-- Showcase QR CTA -->
                    <div class="bg-primary-container text-on-primary-container p-6 rounded-xl relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-secondary opacity-10 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform duration-700"></div>
                        <div class="relative z-10">
                            <span class="material-symbols-outlined text-4xl mb-4 text-secondary-container">qr_code_2</span>
                            <h4 class="text-xl font-bold mb-2">Bagikan Katalog</h4>
                            <p class="text-on-primary-container/80 text-sm mb-6 leading-relaxed">Gunakan tautan etalase publik untuk membagikan produk Anda ke pelanggan.</p>
                            <a href="{{ route('store.public', $store->slug) }}" target="_blank" class="bg-secondary text-on-secondary w-full py-3 rounded-lg text-sm font-bold hover:brightness-110 transition-all flex items-center justify-center gap-2">
                                Buka Etalase
                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</x-app-layout>
