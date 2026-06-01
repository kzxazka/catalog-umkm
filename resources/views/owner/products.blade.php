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
    <div x-data="productManager()">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
            <div>
                <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Daftar Produk</h1>
                <p class="font-body-md text-on-surface-variant">Kelola katalog produk dan tautan eksternal toko Anda (Kategori Toko: <span class="font-bold text-primary text-sm uppercase tracking-wider bg-primary/10 px-2 py-0.5 rounded">{{ $storeCategory }}</span>).
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="openModal('add')"
                    class="flex items-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-md text-sm font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined">add</span>
                    <span>Tambah Produk</span>
                </button>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="flex flex-col sm:flex-row justify-between gap-4 mb-6">
            <div class="relative w-full sm:w-96">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" placeholder="Cari nama produk..."
                    class="w-full pl-10 pr-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
            </div>
            <div class="flex gap-2">
                <select
                    class="px-4 py-2 bg-surface border border-outline-variant rounded-lg text-sm font-bold text-on-surface hover:bg-surface-variant transition-colors focus:ring-primary focus:border-primary">
                    <option>Semua Kategori</option>
                    @foreach($productCategories as $cat)
                        <option>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- Empty State / Add New Trigger -->
            <div @click="openModal('add')"
                class="bg-surface-container-lowest border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center p-8 hover:bg-surface-container-low transition-colors cursor-pointer min-h-[300px]">
                <div
                    class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-2xl">add</span>
                </div>
                <p class="font-bold text-on-surface mb-1">Produk Baru</p>
                <p class="text-xs text-on-surface-variant text-center">Tambahkan produk baru ke etalase Anda</p>
            </div>
        </div>

        <!-- Modal Form (Tambah / Edit) -->
        <template x-teleport="body">
            <div x-show="isModalOpen" style="display: none;"
                class="fixed inset-0 z-[100] flex items-center justify-center">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>

                <!-- Modal Content -->
                <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-surface rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col overflow-hidden">

                    <!-- Modal Header -->
                    <div
                        class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                        <h2 class="text-xl font-bold text-primary"
                            x-text="modalMode === 'add' ? 'Tambah Produk Baru' : 'Edit Produk'"></h2>
                        <button @click="closeModal()"
                            class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container text-on-surface-variant transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto space-y-5">
                        <!-- Foto Produk -->
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Foto Produk</label>
                            <div
                                class="w-full h-40 border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center text-on-surface-variant hover:bg-surface-container-low transition-colors cursor-pointer bg-surface-container-lowest relative">
                                <span class="material-symbols-outlined text-3xl mb-2">add_a_photo</span>
                                <span class="text-sm font-bold">Unggah Gambar</span>
                                <span class="text-[10px] mt-1">Maks. 2MB (JPG/PNG/WEBP)</span>
                                <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>

                        <!-- Informasi Dasar -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-on-surface mb-1">Nama Produk <span
                                        class="text-error">*</span></label>
                                <input type="text" x-model="form.name" placeholder="Misal: Kemeja Tenun Etnik"
                                    class="w-full px-4 py-2.5 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-on-surface mb-1">Kategori <span
                                        class="text-error">*</span></label>
                                <select x-model="form.category"
                                    class="w-full px-4 py-2.5 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($productCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-on-surface mb-1">Deskripsi Singkat</label>
                                <textarea x-model="form.desc" rows="3" placeholder="Tuliskan keunggulan produk ini..."
                                    class="w-full px-4 py-2.5 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all"></textarea>
                            </div>
                        </div>

                        <!-- Tautan Eksternal -->
                        <div class="pt-4 border-t border-outline-variant">
                            <h3 class="text-sm font-bold text-on-surface mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">link</span> Tautan Pembelian (Isi
                                yang tersedia saja)
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded bg-[#25D366]/10 text-[#25D366] flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px]">chat</span>
                                    </div>
                                    <input type="url" x-model="form.wa" placeholder="Link WhatsApp (wa.me/...)"
                                        class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 transition-all">
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded bg-[#EE4D2D]/10 text-[#EE4D2D] flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                                    </div>
                                    <input type="url" x-model="form.shopee" placeholder="Link Shopee"
                                        class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 transition-all">
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded bg-[#42B549]/10 text-[#42B549] flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px]">store</span>
                                    </div>
                                    <input type="url" x-model="form.tokopedia" placeholder="Link Tokopedia"
                                        class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 transition-all">
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded bg-black/10 text-black flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px]">play_circle</span>
                                    </div>
                                    <input type="url" x-model="form.tiktok" placeholder="Link TikTok Shop"
                                        class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div
                        class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex justify-end gap-3">
                        <button type="button" @click="closeModal()"
                            class="px-4 py-2.5 border border-outline-variant text-on-surface rounded-lg text-sm font-bold hover:bg-surface-container transition-colors">
                            Batal
                        </button>
                        <button type="button" @click="saveProduct()"
                            class="px-6 py-2.5 bg-primary text-on-primary rounded-lg text-sm font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function productManager() {
            return {
                isModalOpen: false,
                modalMode: 'add', // 'add' or 'edit'
                form: {
                    id: null,
                    name: '',
                    category: '',
                    desc: '',
                    wa: '',
                    shopee: '',
                    tokopedia: '',
                    tiktok: ''
                },

                openModal(mode, data = null) {
                    this.modalMode = mode;
                    if (mode === 'edit' && data) {
                        this.form = { ...data };
                    } else {
                        // Reset form
                        this.form = {
                            id: null,
                            name: '',
                            category: '',
                            desc: '',
                            wa: '',
                            shopee: '',
                            tokopedia: '',
                            tiktok: ''
                        };
                    }
                    this.isModalOpen = true;
                    // Prevent background scrolling
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.isModalOpen = false;
                    document.body.style.overflow = 'auto';
                },

                saveProduct() {
                    // Logic AJAX untuk menyimpan produk nanti taruh di sini
                    console.log('Menyimpan data: ', this.form);
                    alert((this.modalMode === 'add' ? 'Produk berhasil ditambah!' : 'Produk berhasil diubah!') + '\n(Simulasi frontend sukses)');
                    this.closeModal();
                }
            }
        }
    </script>
</x-app-layout>