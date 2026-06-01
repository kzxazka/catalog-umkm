<x-app-layout>
    <div x-data="verificationPanel()" x-init="init()" class="min-h-screen">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
            <div>
                <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Verifikasi UMKM</h1>
                <p class="font-body-md text-on-surface-variant">Tinjau dan verifikasi pendaftaran mitra UMKM baru Kota Bandar Lampung.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" x-model.debounce.300ms="searchQuery" placeholder="Cari Nama Usaha / Pemilik / Kecamatan..." class="pl-10 pr-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary w-72 transition-all">
                </div>
            </div>
        </div>

        <!-- Notification Banner if pending exists -->
        <template x-if="counts.pending > 0">
            <div class="flex items-center gap-4 p-4 bg-primary-container text-on-primary-container rounded-xl mb-6 shadow-sm border border-primary-container/20">
                <span class="material-symbols-outlined text-2xl animate-pulse">pending_actions</span>
                <div>
                    <p class="font-bold text-sm">Terdapat <span x-text="counts.pending"></span> pengajuan menunggu verifikasi</p>
                    <p class="text-xs opacity-80">Segera tinjau berkas pengajuan UMKM untuk menjaga kualitas layanan mitra galeriukmbdl.</p>
                </div>
            </div>
        </template>

        <!-- Main Table Section -->
        <section class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col">
            <!-- Tabs Navigation -->
            <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-lowest flex gap-6 overflow-x-auto no-scrollbar">
                <button @click="setTab('pending')" 
                        :class="activeTab === 'pending' ? 'text-primary border-primary border-b-2 font-bold' : 'text-on-surface-variant font-bold hover:text-primary transition-colors'" 
                        class="pb-2 px-1 text-sm whitespace-nowrap flex items-center gap-2">
                    <span>Menunggu Verifikasi</span>
                    <span class="px-2 py-0.5 rounded-full text-xs" :class="activeTab === 'pending' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant'" x-text="counts.pending">0</span>
                </button>
                <button @click="setTab('revision')" 
                        :class="activeTab === 'revision' ? 'text-primary border-primary border-b-2 font-bold' : 'text-on-surface-variant font-bold hover:text-primary transition-colors'" 
                        class="pb-2 px-1 text-sm whitespace-nowrap flex items-center gap-2">
                    <span>Perlu Revisi</span>
                    <span class="px-2 py-0.5 rounded-full text-xs" :class="activeTab === 'revision' ? 'bg-amber-600 text-white' : 'bg-surface-container-high text-on-surface-variant'" x-text="counts.revision">0</span>
                </button>
                <button @click="setTab('rejected')" 
                        :class="activeTab === 'rejected' ? 'text-primary border-primary border-b-2 font-bold' : 'text-on-surface-variant font-bold hover:text-primary transition-colors'" 
                        class="pb-2 px-1 text-sm whitespace-nowrap flex items-center gap-2">
                    <span>Ditolak</span>
                    <span class="px-2 py-0.5 rounded-full text-xs" :class="activeTab === 'rejected' ? 'bg-red-600 text-white' : 'bg-surface-container-high text-on-surface-variant'" x-text="counts.rejected">0</span>
                </button>
            </div>
            
            <!-- Table Wrapper -->
            <div class="overflow-x-auto min-h-[300px] relative">
                <!-- Loading State -->
                <div x-show="loading" class="absolute inset-0 bg-white/70 backdrop-blur-sm z-10 flex items-center justify-center transition-all">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin h-10 w-10 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-semibold text-primary">Memuat Data Pengajuan...</span>
                    </div>
                </div>

                <table class="w-full text-left">
                    <thead class="bg-surface-container-low border-b border-outline-variant">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pengaju</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Detail Usaha</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Dokumen</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Waktu Masuk</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <!-- Empty State -->
                        <template x-if="applications.length === 0 && !loading">
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <span class="material-symbols-outlined text-6xl text-on-surface-variant/40 mb-4">folder_open</span>
                                        <h3 class="font-bold text-lg text-on-surface mb-1">Tidak Ada Pengajuan</h3>
                                        <p class="text-sm text-on-surface-variant">Tidak ada berkas pengajuan mitra yang cocok dengan filter atau kueri pencarian saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Dynamic Rows -->
                        <template x-for="app in applications" :key="app._id">
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-black uppercase text-sm" x-text="getInitials(app.user ? app.user.name : 'UMKM')">
                                            U
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface text-sm" x-text="app.user ? app.user.name : 'Unknown User'"></p>
                                            <p class="text-[11px] text-on-surface-variant font-mono mt-0.5" x-text="`ID: ${app._id.substring(18)}`"></p>
                                            <p class="text-[11px] text-on-surface-variant" x-text="`NIK: ${app.user && app.user.nik ? app.user.nik : 'Sertifikasi Valid'}`"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-on-surface text-sm" x-text="app.business_name"></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-100 text-[10px] font-bold rounded uppercase" x-text="app.business_category"></span>
                                        <span class="text-xs text-on-surface-variant flex items-center gap-0.5">
                                            <span class="material-symbols-outlined text-[12px]">location_on</span>
                                            <span x-text="app.district"></span>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1 text-xs">
                                        <a :href="`/admin/mitra/${app._id}/document/ktp`" target="_blank" class="flex items-center gap-1.5 text-primary hover:underline font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">badge</span>
                                            <span>KTP Pemilik.pdf</span>
                                        </a>
                                        <a :href="`/admin/mitra/${app._id}/document/nib`" target="_blank" class="flex items-center gap-1.5 text-primary hover:underline font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">description</span>
                                            <span>NIB Usaha.pdf</span>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-on-surface" x-text="formatDate(app.created_at)"></p>
                                    <p class="text-xs text-on-surface-variant mt-0.5" x-text="relativeTime(app.created_at)"></p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        <button @click="openDetails(app)" class="flex items-center justify-center w-8 h-8 rounded-full border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors" title="Lihat Detail Berkas">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>
                                        
                                        <!-- Actions only in pending -->
                                        <template x-if="app.status === 'pending'">
                                            <div class="flex items-center gap-1.5">
                                                <button @click="promptAction(app, 'approve')" class="flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white hover:bg-green-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                                    <span>Setujui</span>
                                                </button>
                                                <button @click="promptAction(app, 'revision')" class="flex items-center gap-1 px-3 py-1.5 bg-amber-500 text-white hover:bg-amber-600 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                    <span class="material-symbols-outlined text-[16px]">rate_review</span>
                                                    <span>Revisi</span>
                                                </button>
                                                <button @click="promptAction(app, 'reject')" class="flex items-center gap-1 px-3 py-1.5 bg-red-600 text-white hover:bg-red-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                                                    <span>Tolak</span>
                                                </button>
                                            </div>
                                        </template>

                                        <!-- Status indicator for already reviewed -->
                                        <template x-if="app.status !== 'pending'">
                                            <div class="flex flex-col items-end gap-1">
                                                <span class="px-3 py-1 text-xs font-bold uppercase rounded-full" 
                                                      :class="app.status === 'approved' ? 'bg-green-100 text-green-800' : (app.status === 'revision' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')" 
                                                      x-text="app.status === 'approved' ? 'Disetujui' : (app.status === 'revision' ? 'Perlu Revisi' : 'Ditolak')"></span>
                                                <template x-if="app.rejection_reason">
                                                    <p class="text-[10px] text-on-surface-variant max-w-[200px] truncate" :title="app.rejection_reason" x-text="`Ket: ${app.rejection_reason}`"></p>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="px-6 py-4 bg-surface-container-lowest border-t border-outline-variant flex items-center justify-between">
                <p class="text-xs text-on-surface-variant" x-text="`Menampilkan ${pagination.from || 0}-${pagination.to || 0} dari ${pagination.total || 0} pengajuan`"></p>
                
                <div class="flex items-center gap-1">
                    <button @click="prevPage()" :disabled="page <= 1" class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-on-surface hover:bg-surface-container transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>
                    <div class="flex items-center gap-1">
                        <template x-for="p in getPageRange()" :key="p">
                            <button @click="setPage(p)" 
                                    :class="p === page ? 'bg-primary text-on-primary font-bold' : 'hover:bg-surface-container text-on-surface font-bold transition-colors'" 
                                    class="w-8 h-8 flex items-center justify-center rounded text-sm" 
                                    x-text="p"></button>
                        </template>
                    </div>
                    <button @click="nextPage()" :disabled="page >= pagination.last_page" class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-on-surface hover:bg-surface-container transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Detail Modal (Lihat Detail Berkas) -->
        <template x-if="showModal && selectedApp">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                <div class="bg-surface border border-outline-variant w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden" @click.away="closeDetails()">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-lowest flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">storefront</span>
                                <span x-text="`Detail Berkas: ${selectedApp.business_name}`"></span>
                            </h3>
                            <p class="text-xs text-on-surface-variant mt-0.5" x-text="`Pengaju: ${selectedApp.user ? selectedApp.user.name : 'Unknown User'} | ID: ${selectedApp._id}`"></p>
                        </div>
                        <button @click="closeDetails()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-surface-variant text-on-surface transition-all">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-grow p-6 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-6 bg-surface-container-lowest">
                        <!-- Left Side: Profile & Details -->
                        <div class="space-y-6">
                            <div class="bg-surface border border-outline-variant p-5 rounded-xl shadow-sm">
                                <h4 class="font-bold text-sm text-primary uppercase tracking-wider mb-4 border-b pb-2 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-xs">info</span> Profil Pengaju & Usaha
                                </h4>
                                <div class="space-y-3.5 text-sm">
                                    <div>
                                        <label class="text-[10px] uppercase font-bold text-on-surface-variant">Nama Pemilik</label>
                                        <p class="font-bold text-on-surface" x-text="selectedApp.user ? selectedApp.user.name : '-'"></p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[10px] uppercase font-bold text-on-surface-variant">NIK</label>
                                            <p class="font-semibold text-on-surface" x-text="selectedApp.user && selectedApp.user.nik ? selectedApp.user.nik : '-'"></p>
                                        </div>
                                        <div>
                                            <label class="text-[10px] uppercase font-bold text-on-surface-variant">Email</label>
                                            <p class="font-semibold text-on-surface break-words" x-text="selectedApp.user ? selectedApp.user.email : '-'"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase font-bold text-on-surface-variant">Kategori Usaha</label>
                                        <p class="font-bold text-primary" x-text="selectedApp.business_category"></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase font-bold text-on-surface-variant">Deskripsi Singkat</label>
                                        <p class="text-on-surface leading-relaxed" x-text="selectedApp.description"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-surface border border-outline-variant p-5 rounded-xl shadow-sm">
                                <h4 class="font-bold text-sm text-primary uppercase tracking-wider mb-4 border-b pb-2 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-xs">contacts</span> Kontak & Lokasi
                                </h4>
                                <div class="space-y-3 text-sm">
                                    <div>
                                        <label class="text-[10px] uppercase font-bold text-on-surface-variant">Alamat Lengkap</label>
                                        <p class="text-on-surface" x-text="selectedApp.business_address"></p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[10px] uppercase font-bold text-on-surface-variant">Kota</label>
                                            <p class="font-semibold text-on-surface" x-text="selectedApp.city"></p>
                                        </div>
                                        <div>
                                            <label class="text-[10px] uppercase font-bold text-on-surface-variant">Kecamatan</label>
                                            <p class="font-semibold text-on-surface" x-text="selectedApp.district"></p>
                                        </div>
                                    </div>
                                    <div class="flex gap-4 items-center pt-2">
                                        <template x-if="selectedApp.whatsapp">
                                            <a :href="`https://wa.me/${selectedApp.whatsapp.replace(/\D/g, '')}`" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg border border-green-100 font-bold text-xs">
                                                <span class="material-symbols-outlined text-sm">call</span> WhatsApp
                                            </a>
                                        </template>
                                        <template x-if="selectedApp.instagram">
                                            <a :href="`https://instagram.com/${selectedApp.instagram}`" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 bg-pink-50 text-pink-700 rounded-lg border border-pink-100 font-bold text-xs">
                                                <span class="material-symbols-outlined text-sm">photo_camera</span> Instagram
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Documents & Actions -->
                        <div class="flex flex-col gap-6">
                            <div class="bg-surface border border-outline-variant p-5 rounded-xl shadow-sm flex-grow">
                                <h4 class="font-bold text-sm text-primary uppercase tracking-wider mb-4 border-b pb-2 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-xs">attachment</span> Berkas Unggahan
                                </h4>
                                <div class="space-y-4">
                                    <div class="p-4 bg-surface-container rounded-lg border border-outline-variant flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-3xl text-primary">badge</span>
                                            <div>
                                                <h5 class="font-bold text-xs text-on-surface">KTP Pemilik Usaha</h5>
                                                <p class="text-[10px] text-on-surface-variant">Format KTP Terverifikasi</p>
                                            </div>
                                        </div>
                                        <a :href="`/admin/mitra/${selectedApp._id}/document/ktp`" target="_blank" class="px-3 py-1 bg-primary text-on-primary hover:bg-primary-dark font-bold text-xs rounded-md flex items-center gap-1 shadow-sm transition-all">
                                            <span class="material-symbols-outlined text-[14px]">open_in_new</span> Buka Berkas
                                        </a>
                                    </div>

                                    <div class="p-4 bg-surface-container rounded-lg border border-outline-variant flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-3xl text-primary">description</span>
                                            <div>
                                                <h5 class="font-bold text-xs text-on-surface">NIB (Nomor Induk Berusaha)</h5>
                                                <p class="text-[10px] text-on-surface-variant">NIB / SIUP / IUMK resmi</p>
                                            </div>
                                        </div>
                                        <a :href="`/admin/mitra/${selectedApp._id}/document/nib`" target="_blank" class="px-3 py-1 bg-primary text-on-primary hover:bg-primary-dark font-bold text-xs rounded-md flex items-center gap-1 shadow-sm transition-all">
                                            <span class="material-symbols-outlined text-[14px]">open_in_new</span> Buka Berkas
                                        </a>
                                    </div>
                                    
                                    <!-- Status Log -->
                                    <div class="p-4 rounded-lg border text-sm" :class="selectedApp.status === 'approved' ? 'bg-green-50 border-green-200 text-green-900' : (selectedApp.status === 'revision' ? 'bg-amber-50 border-amber-200 text-amber-950' : (selectedApp.status === 'rejected' ? 'bg-red-50 border-red-200 text-red-950' : 'bg-surface-container text-on-surface'))">
                                        <div class="flex items-center gap-2 mb-1.5 font-bold">
                                            <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                            <span>Status Pengajuan saat ini:</span>
                                            <span class="uppercase tracking-wider px-2 py-0.5 rounded text-[10px] font-black" :class="selectedApp.status === 'approved' ? 'bg-green-600 text-white' : (selectedApp.status === 'revision' ? 'bg-amber-600 text-white' : (selectedApp.status === 'rejected' ? 'bg-red-600 text-white' : 'bg-primary text-on-primary'))" x-text="selectedApp.status"></span>
                                        </div>
                                        <template x-if="selectedApp.rejection_reason">
                                            <div>
                                                <p class="font-semibold text-xs mt-2 text-on-surface-variant">Catatan Admin:</p>
                                                <p class="text-xs mt-0.5 leading-relaxed bg-white/60 p-2 rounded border border-black/5" x-text="selectedApp.rejection_reason"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex justify-end gap-3">
                        <button @click="closeDetails()" class="px-4 py-2 border border-outline-variant hover:bg-surface-variant text-on-surface text-sm font-bold rounded-lg transition-all">
                            Tutup
                        </button>
                        
                        <template x-if="selectedApp.status === 'pending'">
                            <div class="flex gap-2">
                                <button @click="promptAction(selectedApp, 'revision')" class="flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">rate_review</span>
                                    <span>Minta Revisi</span>
                                </button>
                                <button @click="promptAction(selectedApp, 'reject')" class="flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                                    <span>Tolak Pengajuan</span>
                                </button>
                                <button @click="promptAction(selectedApp, 'approve')" class="flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                    <span>Setujui & Buat Toko</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <!-- Dynamic Action Modal (Confirmation, Reason prompt for revision/rejection) -->
        <template x-if="showActionModal && selectedApp">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                <div class="bg-surface border border-outline-variant w-full max-w-md rounded-2xl shadow-2xl p-6" @click.away="showActionModal = false">
                    <h3 class="font-bold text-lg text-on-surface mb-2 flex items-center gap-2">
                        <template x-if="actionType === 'approve'">
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        </template>
                        <template x-if="actionType === 'revision'">
                            <span class="material-symbols-outlined text-amber-500">rate_review</span>
                        </template>
                        <template x-if="actionType === 'reject'">
                            <span class="material-symbols-outlined text-red-600">cancel</span>
                        </template>
                        <span x-text="actionTitle"></span>
                    </h3>
                    <p class="text-sm text-on-surface-variant mb-4" x-text="actionDescription"></p>

                    <!-- Reason text area if not approval -->
                    <template x-if="actionType !== 'approve'">
                        <div class="mb-4">
                            <label class="block text-xs uppercase font-bold text-on-surface-variant mb-2">Alasan & Catatan Instruksi</label>
                            <textarea x-model="actionReason" placeholder="Tuliskan alasan / instruksi detail di sini..." rows="4" class="w-full p-3 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all"></textarea>
                        </div>
                    </template>

                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="showActionModal = false" :disabled="submitting" class="px-4 py-2 border border-outline-variant hover:bg-surface-variant text-on-surface text-sm font-semibold rounded-lg transition-all disabled:opacity-50">
                            Batal
                        </button>
                        <button @click="submitAction()" :disabled="submitting || (actionType !== 'approve' && !actionReason.trim())" 
                                :class="actionButtonClass"
                                class="px-4 py-2 text-white text-sm font-bold rounded-lg transition-all flex items-center gap-1.5 shadow-sm disabled:opacity-50">
                            <span x-show="submitting" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                            <span x-text="submitting ? 'Memproses...' : 'Ya, Kirim'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    @push('scripts')
    <script>
        function verificationPanel() {
            return {
                activeTab: 'pending',
                searchQuery: '',
                page: 1,
                applications: [],
                counts: {
                    pending: 0,
                    revision: 0,
                    rejected: 0
                },
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    total: 0,
                    from: 0,
                    to: 0
                },
                loading: false,
                selectedApp: null,
                showModal: false,
                
                // Action Modal
                showActionModal: false,
                actionType: '',
                actionReason: '',
                submitting: false,

                init() {
                    this.fetchApplications();
                    
                    // Watchers
                    this.$watch('searchQuery', () => {
                        this.page = 1;
                        this.fetchApplications();
                    });
                    this.$watch('activeTab', () => {
                        this.page = 1;
                        this.fetchApplications();
                    });
                },

                async fetchApplications() {
                    this.loading = true;
                    try {
                        let url = `/admin/api/applications?status=${this.activeTab}&page=${this.page}`;
                        if (this.searchQuery.trim() !== '') {
                            url += `&search=${encodeURIComponent(this.searchQuery)}`;
                        }
                        
                        const response = await fetch(url);
                        if (!response.ok) throw new Error("Failed fetching applications");
                        const data = await response.json();
                        
                        this.applications = data.data || [];
                        this.pagination = {
                            current_page: data.current_page,
                            last_page: data.last_page,
                            total: data.total,
                            from: data.from,
                            to: data.to
                        };
                        
                        if (data.counts) {
                            this.counts = data.counts;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                setTab(tab) {
                    this.activeTab = tab;
                },

                setPage(p) {
                    this.page = p;
                    this.fetchApplications();
                },

                prevPage() {
                    if (this.page > 1) {
                        this.page--;
                        this.fetchApplications();
                    }
                },

                nextPage() {
                    if (this.page < this.pagination.last_page) {
                        this.page++;
                        this.fetchApplications();
                    }
                },

                getPageRange() {
                    let start = Math.max(1, this.page - 2);
                    let end = Math.min(this.pagination.last_page, this.page + 2);
                    let pages = [];
                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    return pages;
                },

                getInitials(name) {
                    if (!name) return 'UM';
                    return name.split(' ').map(n => n[0]).slice(0, 2).join('');
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                },

                relativeTime(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    const now = new Date();
                    const diffMs = now - d;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMins / 60);
                    const diffDays = Math.floor(diffHours / 24);

                    if (diffMins < 60) {
                        return `${diffMins} menit yang lalu`;
                    } else if (diffHours < 24) {
                        return `${diffHours} jam yang lalu`;
                    } else {
                        return `${diffDays} hari yang lalu`;
                    }
                },

                openDetails(app) {
                    this.selectedApp = app;
                    this.showModal = true;
                },

                closeDetails() {
                    this.showModal = false;
                    this.selectedApp = null;
                },

                promptAction(app, type) {
                    this.selectedApp = app;
                    this.actionType = type;
                    this.actionReason = '';
                    this.showActionModal = true;
                },

                get actionTitle() {
                    if (this.actionType === 'approve') return 'Setujui Pengajuan UMKM';
                    if (this.actionType === 'revision') return 'Minta Revisi Dokumen';
                    if (this.actionType === 'reject') return 'Tolak Pengajuan UMKM';
                    return '';
                },

                get actionDescription() {
                    if (this.actionType === 'approve') {
                        return `Apakah Anda yakin ingin menyetujui pengajuan usaha dari "${this.selectedApp?.business_name}"? Akun pengaju akan otomatis diupgrade menjadi Owner UMKM dan sistem akan melahirkan Toko digital aktif.`;
                    }
                    if (this.actionType === 'revision') {
                        return `Apakah Anda ingin meminta revisi berkas untuk usaha "${this.selectedApp?.business_name}"? Tuliskan instruksi revisi yang jelas untuk membantu pemohon melengkapi berkasnya.`;
                    }
                    if (this.actionType === 'reject') {
                        return `Apakah Anda yakin ingin menolak pengajuan usaha dari "${this.selectedApp?.business_name}"? Cantumkan alasan penolakan yang sesuai regulasi Dinas Perdagangan.`;
                    }
                    return '';
                },

                get actionButtonClass() {
                    if (this.actionType === 'approve') return 'bg-green-600 hover:bg-green-700';
                    if (this.actionType === 'revision') return 'bg-amber-500 hover:bg-amber-600';
                    if (this.actionType === 'reject') return 'bg-red-600 hover:bg-red-700';
                    return 'bg-primary';
                },

                async submitAction() {
                    this.submitting = true;
                    try {
                        let url = '';
                        let payload = {};

                        if (this.actionType === 'approve') {
                            url = `/admin/mitra/${this.selectedApp._id}/approve`;
                        } else if (this.actionType === 'revision') {
                            url = `/admin/mitra/${this.selectedApp._id}/revision`;
                            payload.reason = this.actionReason;
                        } else if (this.actionType === 'reject') {
                            url = `/admin/mitra/${this.selectedApp._id}/reject`;
                            payload.reason = this.actionReason;
                        }

                        // Send POST Request using fetch (and include CSRF token)
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        if (response.ok) {
                            this.showActionModal = false;
                            this.showModal = false;
                            
                            // Re-fetch data to reflect status changes reactively
                            await this.fetchApplications();
                        } else {
                            const errData = await response.json();
                            alert(errData.message || 'Gagal memproses pengajuan. Silakan coba lagi.');
                        }
                    } catch (error) {
                        console.error('Error submitting action:', error);
                        alert('Terjadi kesalahan jaringan.');
                    } finally {
                        this.submitting = false;
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
