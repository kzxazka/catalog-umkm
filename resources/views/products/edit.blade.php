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
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Edit Produk</h1>
        <p class="font-body-md text-on-surface-variant">
            Perbarui informasi produk: <span class="font-bold text-primary">{{ $product->name }}</span>
        </p>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-outlined text-red-600" style="font-size:20px">error</span>
            <p class="text-sm font-bold text-red-800">Terdapat kesalahan penginputan:</p>
        </div>
        <ul class="list-disc list-inside text-xs text-red-700 space-y-1 pl-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Side: Product Info Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-on-surface mb-6 border-b border-outline-variant pb-2">Detail Produk</h3>
                    
                    <div class="space-y-5">
                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required placeholder="Contoh: Sepatu Sneakers Running Premium"
                                class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        </div>

                        <!-- Kategori Produk (Dropdown berdasarkan Kategori Toko) -->
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Kategori Produk</label>
                            <select name="category" required
                                class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                <option value="" disabled>Pilih Kategori Produk...</option>
                                @foreach($productCategories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $product->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>



                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Deskripsi Lengkap Produk</label>
                            <textarea name="description" rows="5" required placeholder="Tuliskan spesifikasi, keunggulan, ukuran, bahan, dll secara lengkap..."
                                class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Photos & External Links -->
            <div class="space-y-6">
                
                <!-- Photos Upload Container -->
                <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant">Foto Produk</h3>
                    
                    <div class="space-y-4">
                        <!-- Current Photos -->
                        @if(is_array($product->images) && count($product->images) > 0)
                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant mb-2">Foto Saat Ini ({{ count($product->images) }} Foto)</label>
                            <div class="grid grid-cols-3 gap-2 p-2 bg-surface-container-low border border-outline-variant rounded-xl">
                                @foreach($product->images as $img)
                                    <div class="relative aspect-square border border-outline-variant rounded-lg overflow-hidden bg-surface-container">
                                        <img src="{{ asset('storage/products/' . $img) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">
                                Unggah File Foto Baru <span class="text-xs text-on-surface-variant font-normal">(Opsional - mengganti foto saat ini)</span>
                            </label>
                            
                            <!-- Custom File Upload Container -->
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-outline-variant rounded-xl cursor-pointer bg-surface-container-low hover:bg-surface-container transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-4 pb-5">
                                        <span class="material-symbols-outlined text-3xl text-primary mb-1">cloud_upload</span>
                                        <p class="mb-0.5 text-xs text-on-surface font-semibold">Pilih minimal 3 gambar</p>
                                        <p class="text-[10px] text-on-surface-variant">PNG, JPG, JPEG atau WEBP (Maks. 3MB)</p>
                                    </div>
                                    <input type="file" name="photos[]" multiple accept="image/*" class="hidden" onchange="previewPhotos(event)">
                                </label>
                            </div>
                            
                            @error('photos')
                                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                            <p class="text-[11px] text-on-surface-variant mt-2 leading-relaxed">
                                Minimal harus memiliki 3 foto produk agar dapat aktif sebagai slide di katalog produk.
                            </p>
                        </div>

                        <!-- Live Photo Previews -->
                        <div id="photos-preview-container" class="grid grid-cols-3 gap-2 mt-4 hidden">
                            <!-- Preview images will be injected here -->
                        </div>
                    </div>
                </div>

                <!-- External Links (WA, Marketplace, dll) -->
                <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-on-surface mb-4 pb-2 border-b border-outline-variant">Tautan Eksternal</h3>
                    
                    <div>
                        <p class="text-xs text-on-surface-variant mb-4 leading-relaxed">
                            Hubungkan pembeli langsung ke WhatsApp, Tokopedia, Shopee, atau marketplace lainnya milik toko Anda.
                        </p>
                        
                        <div id="links-container" class="space-y-3">
                            @if(is_array($product->links) && count($product->links) > 0)
                                @foreach($product->links as $index => $link)
                                    <div class="flex gap-2 link-row items-center">
                                        <input type="text" name="links[{{ $index }}][platform]" value="{{ $link['platform'] ?? '' }}" placeholder="Msl: Shopee" required
                                            class="w-1/3 px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-xs focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                        <input type="url" name="links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="URL Tautan" required
                                            class="w-2/3 px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-xs focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                        <button type="button" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg remove-link transition-colors flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex gap-2 link-row items-center">
                                    <input type="text" name="links[0][platform]" placeholder="Msl: WhatsApp" required
                                        class="w-1/3 px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-xs focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                    <input type="url" name="links[0][url]" placeholder="URL Tautan" required
                                        class="w-2/3 px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-xs focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                    <button type="button" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg remove-link transition-colors flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" id="add-link"
                            class="mt-4 w-full flex items-center justify-center gap-1 py-2 border border-dashed border-primary text-primary hover:bg-primary/5 rounded-lg text-xs font-bold transition-all">
                            <span class="material-symbols-outlined text-sm">add</span>
                            <span>Tambah Tautan Baru</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex justify-end gap-3 border-t border-outline-variant pt-6">
            <a href="{{ route('products.index') }}"
                class="px-6 py-2.5 border border-outline text-primary hover:bg-primary/5 rounded-lg font-bold text-sm transition-colors text-center">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container rounded-lg font-bold text-sm transition-all shadow-sm">
                Update Produk
            </button>
        </div>
    </form>

    <script>
        // Preview selected images dynamically
        function previewPhotos(event) {
            const container = document.getElementById('photos-preview-container');
            container.innerHTML = '';
            
            const files = event.target.files;
            if (files && files.length > 0) {
                container.classList.remove('hidden');
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-square border border-outline-variant rounded-lg overflow-hidden bg-surface-container';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                container.classList.add('hidden');
            }
        }

        // Dynamic links manager
        let linkIndex = {{ is_array($product->links) ? count($product->links) : 1 }};
        document.getElementById('add-link').addEventListener('click', function() {
            const container = document.getElementById('links-container');
            const html = `
                <div class="flex gap-2 link-row items-center">
                    <input type="text" name="links[${linkIndex}][platform]" placeholder="Msl: Shopee" required
                        class="w-1/3 px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-xs focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    <input type="url" name="links[${linkIndex}][url]" placeholder="URL Tautan" required
                        class="w-2/3 px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-xs focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    <button type="button" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg remove-link transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            linkIndex++;
        });

        document.getElementById('links-container').addEventListener('click', function(e) {
            const button = e.target.closest('.remove-link');
            if (button) {
                button.closest('.link-row').remove();
            }
        });
    </script>
</x-app-layout>
