<x-app-layout>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Tautan Eksternal</h1>
        <p class="font-body-md text-on-surface-variant">Hubungkan toko Anda dengan marketplace dan sosial media untuk memudahkan pelanggan.</p>
    </div>

    <!-- Links Form -->
    <div class="max-w-3xl bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm p-6">
        <form>
            <!-- WhatsApp -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#25D366]">chat</span> Nomor WhatsApp
                </label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-outline-variant bg-surface-container-low text-on-surface-variant font-mono text-sm">+62</span>
                    <input type="text" placeholder="8xxxxxxxxxxx" class="flex-1 px-4 py-2 bg-surface-container border border-outline-variant rounded-r-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Gunakan awalan kode negara tanpa 0 atau +</p>
            </div>

            <!-- Instagram -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#E4405F]">photo_camera</span> Instagram
                </label>
                <input type="url" placeholder="https://instagram.com/username" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
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
                        <input type="url" placeholder="https://shopee.co.id/toko-anda" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                    
                    <!-- Tokopedia -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#42B549]">store</span> Link Tokopedia
                        </label>
                        <input type="url" placeholder="https://tokopedia.com/toko-anda" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-outline-variant">
                <button type="button" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                    Simpan Tautan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
