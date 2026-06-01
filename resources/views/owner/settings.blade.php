<x-app-layout>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Pengaturan Profil</h1>
        <p class="font-body-md text-on-surface-variant">Perbarui informasi dasar toko, dokumen legalitas, dan detail lainnya.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl p-4" x-data x-init="setTimeout(() => $el.remove(), 4000)">
        <span class="material-symbols-outlined text-green-600" style="font-size:20px">check_circle</span>
        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl p-4">
        <span class="material-symbols-outlined text-red-600" style="font-size:20px">error</span>
        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-center gap-3 mb-2">
            <span class="material-symbols-outlined text-red-600" style="font-size:20px">error</span>
            <p class="text-sm font-bold text-red-800">Terdapat kesalahan:</p>
        </div>
        <ul class="list-disc list-inside text-xs text-red-700 space-y-1 pl-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('owner.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Info Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-on-surface mb-6 border-b border-outline-variant pb-2">Informasi Dasar</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Nama Toko</label>
                            <input type="text" name="name" value="{{ old('name', $store->name ?? '') }}"
                                class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Deskripsi Toko</label>
                            <textarea name="description" rows="4"
                                class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">{{ old('description', $store->description ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Kategori UMKM</label>
                            <select name="category"
                                class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                                <option value="Fashion" {{ old('category', $store->category ?? '') === 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                <option value="Food & Beverage" {{ old('category', $store->category ?? '') === 'Food & Beverage' ? 'selected' : '' }}>Food & Beverage</option>
                                <option value="Healthy Product" {{ old('category', $store->category ?? '') === 'Healthy Product' ? 'selected' : '' }}>Healthy Product</option>
                                <option value="Other" {{ old('category', $store->category ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit"
                                class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Legalitas Form -->
                <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-on-surface mb-6 border-b border-outline-variant pb-2 flex items-center justify-between">
                        Legalitas Usaha
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Terverifikasi</span>
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">Nomor Induk Berusaha (NIB)</label>
                            <input type="text" value="{{ $store->nib ?? 'Tidak tersedia' }}" readonly
                                class="w-full px-4 py-2 bg-surface-container-high border border-outline-variant rounded-lg text-sm text-on-surface-variant cursor-not-allowed">
                            <p class="text-xs text-on-surface-variant mt-1">Hubungi Admin Dinas jika NIB berubah.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo Upload -->
            <div class="space-y-6">
                <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-on-surface mb-4">Logo Toko</h3>

                    <div class="flex flex-col items-center">
                        <input type="file" name="logo" id="logo-input" class="hidden" accept="image/*" onchange="previewLogo(event)">
                        
                        <div class="w-32 h-32 rounded-full bg-surface-container border-4 border-outline-variant overflow-hidden mb-4 flex items-center justify-center relative group cursor-pointer"
                            onclick="document.getElementById('logo-input').click()">
                            
                            @if($store->logo ?? false)
                                <img id="logo-preview" src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover">
                            @else
                                <span id="logo-fallback-icon" class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:hidden">storefront</span>
                                <img id="logo-preview" class="w-full h-full object-cover hidden">
                            @endif

                            <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center flex-col text-white">
                                <span class="material-symbols-outlined">upload</span>
                                <span class="text-xs mt-1">Ubah</span>
                            </div>
                        </div>
                        <p class="text-xs text-center text-on-surface-variant mb-4">Format: JPG, PNG, WEBP.<br>Maks: 2MB.</p>
                        
                        <button type="button" onclick="document.getElementById('logo-input').click()"
                            class="w-full px-4 py-2 border border-outline-variant text-on-surface font-bold text-sm rounded-lg hover:bg-surface-container transition-colors">
                            Upload Logo Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('logo-preview');
                    const fallback = document.getElementById('logo-fallback-icon');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    
                    if (fallback) {
                        fallback.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>