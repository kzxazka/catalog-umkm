<x-app-layout>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Tautan Eksternal</h1>
        <p class="font-body-md text-on-surface-variant">Hubungkan toko Anda dengan marketplace dan sosial media untuk memudahkan pelanggan.</p>
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

    <!-- Links Form -->
    <div class="max-w-3xl bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm p-6">
        <form action="{{ route('owner.links.update') }}" method="POST">
            @csrf
            <!-- WhatsApp -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#25D366]">chat</span> Nomor WhatsApp
                </label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-outline-variant bg-surface-container-low text-on-surface-variant font-mono text-sm">+62</span>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $store->whatsapp ?? '') }}" placeholder="8xxxxxxxxxxx" class="flex-1 px-4 py-2 bg-surface-container border border-outline-variant rounded-r-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Gunakan awalan kode negara tanpa 0 atau +</p>
            </div>

            <!-- Instagram -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#E4405F]">photo_camera</span> Instagram
                </label>
                <input type="text" name="instagram" value="{{ old('instagram', $store->instagram ?? '') }}" placeholder="username" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                <p class="text-xs text-on-surface-variant mt-1">Masukkan username Instagram toko Anda</p>
            </div>

            <!-- Marketplace Links -->
            <div class="border-t border-outline-variant pt-6 mb-6">
                <h3 class="text-lg font-bold text-on-surface mb-4">Marketplace</h3>
                
                <div class="space-y-4">
                    <!-- Shopee -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#EE4D2D]">shopping_bag</span> Link Shopee
                        </label>
                        <input type="url" name="shopee" value="{{ old('shopee', $store->social_links['shopee'] ?? '') }}" placeholder="https://shopee.co.id/toko-anda" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                    
                    <!-- Tokopedia -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#42B549]">store</span> Link Tokopedia
                        </label>
                        <input type="url" name="tokopedia" value="{{ old('tokopedia', $store->social_links['tokopedia'] ?? '') }}" placeholder="https://tokopedia.com/toko-anda" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-outline-variant">
                <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                    Simpan Tautan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
