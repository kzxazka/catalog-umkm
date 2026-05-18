<x-app-layout>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Pengaturan Profil</h1>
        <p class="font-body-md text-on-surface-variant">Perbarui informasi dasar toko, dokumen legalitas, dan detail lainnya.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-on-surface mb-6 border-b border-outline-variant pb-2">Informasi Dasar</h3>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2">Nama Toko</label>
                        <input type="text" value="Jagatboemi" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2">Deskripsi Toko</label>
                        <textarea rows="4" class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">Batik Gabovira secara resmi didirikan pada 25 Februari 2000...</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2">Kategori UMKM</label>
                        <select class="w-full px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            <option>Fashion</option>
                            <option>Kuliner</option>
                            <option>Kriya & Kerajinan</option>
                            <option>Jasa</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="button" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-bold text-sm hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Legalitas Form -->
            <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-on-surface mb-6 border-b border-outline-variant pb-2 flex items-center justify-between">
                    Legalitas Usaha
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Terverifikasi</span>
                </h3>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2">Nomor Induk Berusaha (NIB)</label>
                        <input type="text" value="9120381029381" readonly class="w-full px-4 py-2 bg-surface-container-high border border-outline-variant rounded-lg text-sm text-on-surface-variant cursor-not-allowed">
                        <p class="text-xs text-on-surface-variant mt-1">Hubungi Admin Dinas jika NIB berubah.</p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logo Upload -->
        <div class="space-y-6">
            <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-on-surface mb-4">Logo Toko</h3>
                
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full bg-surface-container border-4 border-outline-variant overflow-hidden mb-4 flex items-center justify-center relative group cursor-pointer">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant group-hover:hidden">storefront</span>
                        <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center flex-col text-white">
                            <span class="material-symbols-outlined">upload</span>
                            <span class="text-xs mt-1">Ubah</span>
                        </div>
                    </div>
                    <p class="text-xs text-center text-on-surface-variant mb-4">Format: JPG, PNG, WEBP.<br>Maks: 2MB.</p>
                    <button class="w-full px-4 py-2 border border-outline-variant text-on-surface font-bold text-sm rounded-lg hover:bg-surface-container transition-colors">
                        Upload Logo Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
