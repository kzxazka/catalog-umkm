@php
    $store = auth()->user()->store;
    $storeCategory = $store ? ($store->category ?? 'Fashion') : 'Fashion';

    // Mapping Kategori Toko -> List Kategori Produk
    $categoryMapping = [
        'Fashion' => [
            'Koleksi Pria',
            'Koleksi Wanita',
            'Koleksi Anak',
            'Alas Kaki & Sepatu',
            'Tas & Dompet',
            'Aksesoris',
            'Lainnya'
        ],
        'Food & Beverage' => [
            'Makanan Berat',
            'Makanan Ringan & Cemilan',
            'Minuman',
            'Bahan & Bumbu',
            'Kue & Roti',
            'Makanan Beku (Frozen)',
            'Lainnya'
        ],
        'Healthy Product' => [
            'Suplemen & Vitamin',
            'Herbal & Tradisional',
            'Perawatan Tubuh (Bodycare)',
            'Makanan Sehat & Diet',
            'Lainnya'
        ],
        'Other' => [
            'Kriya & Kerajinan',
            'Aksesoris & Perhiasan',
            'Jasa & Layanan',
            'Elektronik & Rumah Tangga',
            'Lainnya'
        ],
    ];

    $productCategories = $categoryMapping[$storeCategory] ?? ['Umum', 'Lainnya'];
@endphp

<x-app-layout>
    <div x-data="productManager({{ json_encode($products) }})" class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <div>
                <h1 class="font-headline-lg text-3xl font-bold text-primary mb-1">Daftar Produk</h1>
                <p class="font-body-md text-on-surface-variant">
                    Kelola katalog produk dan tautan eksternal toko Anda (Kategori Toko: 
                    <span class="font-bold text-primary text-xs uppercase tracking-wider bg-primary/10 px-2 py-0.5 rounded">{{ $storeCategory }}</span>).
                </p>
            </div>
            <div>
                <a href="{{ route('products.create') }}"
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container rounded-xl font-bold text-sm transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    <span>Tambah Produk</span>
                </a>
            </div>
        </div>

        {{-- Session Notifications --}}
        @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm" x-data x-init="setTimeout(() => $el.remove(), 5000)">
            <span class="material-symbols-outlined text-green-600" style="font-size:22px">check_circle</span>
            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
            <span class="material-symbols-outlined text-red-600" style="font-size:22px">error</span>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row justify-between gap-4 bg-surface border border-outline-variant p-4 rounded-xl shadow-sm">
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 18px;">search</span>
                <input type="text" x-model="searchQuery" placeholder="Cari nama produk..."
                    class="w-full pl-9 pr-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all text-on-surface">
            </div>
            <div class="flex gap-2">
                <select x-model="selectedCategory"
                    class="px-4 py-2 bg-surface border border-outline-variant rounded-lg text-sm font-bold text-on-surface hover:bg-surface-variant transition-colors focus:ring-primary focus:border-primary cursor-pointer">
                    <option value="Semua Kategori">Semua Kategori</option>
                    @foreach($productCategories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- Card: Add New Product -->
            <a href="{{ route('products.create') }}"
                class="bg-surface border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center p-8 hover:bg-surface-container-low hover:border-primary transition-all cursor-pointer min-h-[340px] group text-center">
                <div class="w-14 h-14 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[28px]">add</span>
                </div>
                <p class="font-bold text-on-surface mb-1 group-hover:text-primary transition-colors text-base">Tambah Produk</p>
                <p class="text-xs text-on-surface-variant leading-relaxed max-w-[200px]">Masukkan produk baru ke dalam katalog etalase toko Anda</p>
            </a>

            <!-- Dynamic Product Cards -->
            <template x-for="product in filteredProducts" :key="product._id">
                <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between min-h-[340px]">
                    <div>
                        <!-- Product Photo / Cover -->
                        <div class="relative w-full h-48 bg-surface-container-low overflow-hidden group">
                            <template x-if="product.images && product.images.length > 0">
                                <img :src="'/storage/products/' + product.images[0]" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </template>
                            <template x-if="!product.images || product.images.length === 0">
                                <div class="w-full h-full flex flex-col items-center justify-center text-on-surface-variant bg-surface-container-low gap-2">
                                    <span class="material-symbols-outlined text-[36px]">image_not_supported</span>
                                    <span class="text-xs font-semibold">Tanpa Gambar</span>
                                </div>
                            </template>
                            <span class="absolute top-3 right-3 px-2 py-0.5 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold rounded uppercase tracking-wider" x-text="product.category || 'Lainnya'"></span>
                        </div>

                        <!-- Product Content Details -->
                        <div class="p-4 space-y-2">
                            <h3 class="font-bold text-base text-on-surface line-clamp-1" :title="product.name" x-text="product.name"></h3>
                            <p class="text-xs text-on-surface-variant line-clamp-3 leading-relaxed" x-text="product.description || 'Tidak ada deskripsi.'"></p>
                            
                            <!-- Badges for active links -->
                            <div class="flex flex-wrap gap-1.5 pt-2">
                                <template x-for="link in (product.links || [])">
                                    <span class="px-2 py-0.5 bg-primary/5 text-primary border border-primary/10 text-[9px] font-bold rounded flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[10px]">link</span>
                                        <span x-text="link.platform"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions Footer -->
                    <div class="p-4 border-t border-outline-variant flex gap-2 bg-surface-container-lowest">
                        <a :href="'/products/' + product._id + '/edit'" 
                            class="flex-1 flex items-center justify-center gap-1 py-2 bg-surface border border-outline text-primary hover:bg-primary/5 rounded-lg text-xs font-bold transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit</span>
                        </a>
                        
                        <form :action="'/products/' + product._id" method="POST" class="flex-1" 
                            @submit.prevent="if (confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen?')) $el.submit()">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" 
                                class="w-full flex items-center justify-center gap-1 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition-colors">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </template>

        </div>

        <!-- No Products Found State -->
        <div x-show="filteredProducts.length === 0 && searchQuery !== ''" class="flex flex-col items-center justify-center py-12 text-center bg-surface border border-outline-variant rounded-xl p-8" style="display: none;">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-3">search_off</span>
            <h3 class="text-base font-bold text-on-surface">Tidak ada produk ditemukan</h3>
            <p class="text-xs text-on-surface-variant mt-1">Coba cari dengan kata kunci lain atau ubah filter kategori Anda.</p>
        </div>
    </div>

    <script>
        function productManager(productsJson) {
            return {
                products: productsJson || [],
                searchQuery: '',
                selectedCategory: 'Semua Kategori',
                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchesSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        const matchesCat = this.selectedCategory === 'Semua Kategori' || p.category === this.selectedCategory;
                        return matchesSearch && matchesCat;
                    });
                }
            }
        }
    </script>
</x-app-layout>