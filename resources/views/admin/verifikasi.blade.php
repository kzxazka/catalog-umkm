<x-app-layout>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
        <div>
            <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Verifikasi UMKM</h1>
            <p class="font-body-md text-on-surface-variant">Tinjau dan setujui pengajuan pendaftaran UMKM baru di Kota Bandar Lampung.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" placeholder="Cari ID Pendaftaran..." class="pl-10 pr-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-1 focus:ring-primary w-64 transition-all">
            </div>
            <button class="flex items-center gap-2 px-4 py-2.5 bg-surface-container border border-outline-variant rounded-lg font-label-md text-sm font-bold text-on-surface hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined">filter_list</span>
                <span>Filter</span>
            </button>
        </div>
    </div>

    <!-- Alert / Stats -->
    <div class="flex items-center gap-4 p-4 bg-error-container text-on-error-container rounded-xl mb-6 shadow-sm border border-error-container/20">
        <span class="material-symbols-outlined text-2xl">warning</span>
        <div>
            <p class="font-bold text-sm">Terdapat 12 pengajuan mendesak</p>
            <p class="text-xs opacity-80">Pengajuan ini telah melewati batas waktu SLA verifikasi (2x24 jam).</p>
        </div>
    </div>

    <!-- Main Table Section -->
    <section class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col">
        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-lowest flex gap-6">
            <button class="text-primary font-bold border-b-2 border-primary pb-2 px-1 text-sm">Menunggu Verifikasi (154)</button>
            <button class="text-on-surface-variant font-bold hover:text-primary pb-2 px-1 text-sm transition-colors">Perlu Revisi (23)</button>
            <button class="text-on-surface-variant font-bold hover:text-primary pb-2 px-1 text-sm transition-colors">Ditolak (8)</button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low border-b border-outline-variant">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">ID & Pengaju</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Detail Usaha</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelengkapan Dokumen</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Waktu Masuk</th>
                        <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    <!-- Row 1 -->
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold">
                                    BS
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface text-sm">Bambang Susanto</p>
                                    <p class="text-[11px] text-on-surface-variant font-mono">REQ-20231114-001</p>
                                    <p class="text-xs text-on-surface-variant mt-1">NIK: 187103********</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-on-surface text-sm">Bakery Lezat Jaya</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-surface-variant text-primary text-[10px] font-bold rounded">Kuliner</span>
                                <span class="text-xs text-on-surface-variant">Tanjung Karang Pusat</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="material-symbols-outlined text-[14px] text-green-600">check_circle</span> KTP & NPWP
                                </div>
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="material-symbols-outlined text-[14px] text-green-600">check_circle</span> NIB / Izin Usaha
                                </div>
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="material-symbols-outlined text-[14px] text-green-600">check_circle</span> Foto Tempat Usaha
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-error">48 jam yang lalu</p>
                            <p class="text-xs text-on-surface-variant mt-1">14 Nov 2023, 10:24 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end items-center gap-2">
                                <button class="flex items-center justify-center w-8 h-8 rounded-full border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors" title="Lihat Detail Berkas">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                                <button class="flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-800 hover:bg-green-200 rounded text-xs font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Setujui
                                </button>
                                <button class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-800 hover:bg-red-200 rounded text-xs font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">close</span> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Row 2 -->
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">
                                    SA
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface text-sm">Siti Aminah</p>
                                    <p class="text-[11px] text-on-surface-variant font-mono">REQ-20231115-082</p>
                                    <p class="text-xs text-on-surface-variant mt-1">NIK: 187102********</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-on-surface text-sm">Kriya Rotan Nusantara</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-surface-variant text-primary text-[10px] font-bold rounded">Kerajinan</span>
                                <span class="text-xs text-on-surface-variant">Kedaton</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="material-symbols-outlined text-[14px] text-green-600">check_circle</span> KTP & NPWP
                                </div>
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="material-symbols-outlined text-[14px] text-orange-500">pending</span> NIB (Dalam Proses)
                                </div>
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="material-symbols-outlined text-[14px] text-green-600">check_circle</span> Foto Tempat Usaha
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-on-surface">5 jam yang lalu</p>
                            <p class="text-xs text-on-surface-variant mt-1">15 Nov 2023, 08:15 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end items-center gap-2">
                                <button class="flex items-center justify-center w-8 h-8 rounded-full border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors" title="Lihat Detail Berkas">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                                <button class="flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-800 hover:bg-green-200 rounded text-xs font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Setujui
                                </button>
                                <button class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-800 hover:bg-red-200 rounded text-xs font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">close</span> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-surface-container-lowest border-t border-outline-variant flex items-center justify-between">
            <p class="text-xs text-on-surface-variant">Menampilkan 1-10 dari 154 pengajuan</p>
            <div class="flex items-center gap-1">
                <button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors" disabled>
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-on-primary font-bold text-sm">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container text-on-surface font-bold text-sm transition-colors">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-surface-container text-on-surface font-bold text-sm transition-colors">3</button>
                <span class="text-on-surface-variant px-1">...</span>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-on-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </button>
            </div>
        </div>
    </section>
</x-app-layout>
