<x-app-layout>
    <div x-data="smeDatabase()" x-init="fetchStores()">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
            <div>
                <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">SME Database</h1>
                <p class="font-body-md text-on-surface-variant">Direktori lengkap seluruh UMKM yang telah terverifikasi dan aktif di sistem.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" x-model="searchQuery" @keyup.debounce.500ms="fetchStores(1)" placeholder="Cari nama toko, NIB..." class="pl-10 pr-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary w-64 transition-all">
                </div>
                <a href="{{ route('admin.sme_database.export') }}" class="flex items-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-md text-sm font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined">download</span>
                    <span>Export Data</span>
                </a>
            </div>
        </div>

        <!-- Filter Categories -->
        <div class="flex gap-2 overflow-x-auto pb-4 mb-4 scrollbar-hide">
            <template x-for="cat in categories" :key="cat">
                <button 
                    @click="setCategory(cat)" 
                    :class="activeCategory === cat ? 'bg-primary text-on-primary' : 'bg-surface border border-outline-variant text-on-surface hover:bg-surface-variant'" 
                    class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-colors"
                    x-text="cat"></button>
            </template>
        </div>

        <!-- Loading State -->
        <div x-show="isLoading" class="flex justify-center py-12">
            <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Main Table -->
        <div x-show="!isLoading" class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm" style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low border-b border-outline-variant">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Info UMKM</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status Legalitas</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <template x-if="stores.length === 0">
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">Tidak ada data UMKM yang ditemukan.</td>
                            </tr>
                        </template>
                        <template x-for="store in stores" :key="store.id">
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded bg-surface-container flex items-center justify-center text-primary border border-outline-variant">
                                            <span class="material-symbols-outlined text-2xl">storefront</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface" x-text="store.name"></p>
                                            <div class="flex items-center gap-1 mt-1 text-[10px] font-mono bg-surface-container-high px-1.5 py-0.5 rounded w-max text-on-surface-variant" x-text="'NIB: ' + (store.nib || 'Belum ada')">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-surface-variant text-primary text-xs font-bold rounded-full" x-text="store.category || 'Belum diatur'"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <template x-if="store.nib">
                                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-700" title="NIB Tersedia">
                                                <span class="material-symbols-outlined text-[14px]">verified</span>
                                            </span>
                                        </template>
                                        <template x-if="!store.nib">
                                            <span class="text-xs text-on-surface-variant">Belum Lengkap</span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a :href="'/store/' + store.slug" target="_blank" class="px-3 py-1.5 border border-outline-variant text-sm font-bold text-on-surface rounded hover:bg-surface-variant transition-colors">Detail</a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 bg-surface-container-lowest border-t border-outline-variant flex items-center justify-between">
                <p class="text-xs text-on-surface-variant" x-text="`Menampilkan ${pagination.from || 0}-${pagination.to || 0} dari ${pagination.total || 0} UMKM`"></p>
                <div class="flex items-center gap-1">
                    <!-- Prev Button -->
                    <button 
                        @click="if(pagination.current_page > 1) fetchStores(pagination.current_page - 1)"
                        :disabled="pagination.current_page <= 1"
                        :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-surface-container'"
                        class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-on-primary font-bold text-sm" x-text="pagination.current_page"></button>
                    
                    <!-- Next Button -->
                    <button 
                        @click="if(pagination.current_page < pagination.last_page) fetchStores(pagination.current_page + 1)"
                        :disabled="pagination.current_page >= pagination.last_page"
                        :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-surface-container'"
                        class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-on-surface hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function smeDatabase() {
            return {
                stores: [],
                searchQuery: '',
                activeCategory: 'Semua',
                categories: ['Semua', 'Fashion', 'Food & Beverage', 'Healthy Product', 'Other'],
                isLoading: true,
                pagination: {},
                
                setCategory(category) {
                    this.activeCategory = category;
                    this.fetchStores(1);
                },

                async fetchStores(page = 1) {
                    this.isLoading = true;
                    try {
                        let url = `/admin/api/stores?page=${page}`;
                        if (this.activeCategory !== 'Semua') {
                            url += `&category=${encodeURIComponent(this.activeCategory)}`;
                        }
                        if (this.searchQuery.trim() !== '') {
                            url += `&search=${encodeURIComponent(this.searchQuery)}`;
                        }

                        const response = await fetch(url);
                        const data = await response.json();
                        
                        this.stores = data.data; // Framework pagination object holds data in .data
                        this.pagination = {
                            current_page: data.current_page,
                            last_page: data.last_page,
                            total: data.total,
                            from: data.from,
                            to: data.to,
                        };
                    } catch (error) {
                        console.error('Error fetching stores:', error);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
