<x-app-layout>
    <div x-data='{ 
        showKecamatanModal: false, 
        showAiModal: false, 
        searchKec: "",
        kecamatans: @json($kecamatanData),
        filteredKecamatans() {
            if (!this.searchKec.trim()) return this.kecamatans;
            return this.kecamatans.filter(k => k.name.toLowerCase().includes(this.searchKec.toLowerCase()));
        }
    }' class="min-h-screen">
        
        <style>
            @media print {
                /* Sembunyikan sidebar navigasi, header bar, tombol, alert, footer */
                aside, nav, header, button, .no-print, [role="navigation"], .print-hidden {
                    display: none !important;
                }
                body, main, .main-content {
                    background: white !important;
                    color: black !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    box-shadow: none !important;
                    border: none !important;
                }
                .print-full {
                    width: 100% !important;
                    max-width: 100% !important;
                    flex: 1 1 100% !important;
                }
                .print-header-dinas {
                    display: flex !important;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    border-bottom: 3px double black;
                    padding-bottom: 16px;
                    margin-bottom: 24px;
                }
                .print-card {
                    border: 1px solid #e2e8f0 !important;
                    border-radius: 12px !important;
                    box-shadow: none !important;
                    page-break-inside: avoid !important;
                }
            }
        </style>

        <!-- Official Dinas Kop untuk Print PDF -->
        <div class="hidden print-header-dinas print:flex items-center gap-4 border-b-4 border-black pb-4 mb-6">
            <img src="{{ asset('img/disperdaglogo.png') }}" class="w-20 h-20 object-contain" alt="Logo Dinas">
            <div class="text-center flex-grow">
                <h2 class="text-xl font-bold uppercase">Pemerintah Kota Bandar Lampung</h2>
                <h1 class="text-2xl font-black uppercase tracking-wider">Dinas Perdagangan Kota Bandar Lampung</h1>
                <p class="text-xs text-gray-600 mt-1">Jl. Antara No.42, Klp. Tiga, Kec. Tj. Karang Pusat, Kota Bandar Lampung, Lampung 35119 | Telp: (0721) 456-7890</p>
                <p class="text-[10px] text-gray-500">Surel: diskominfo@bandarlampungkota.go.id, perdaganganbl@gmail.com | IG: @dinasperdagangan_bandarlampung</p>
            </div>
        </div>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8 print-hidden">
            <div>
                <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Laporan Wilayah</h1>
                <p class="font-body-md text-on-surface-variant">Analisis sebaran UMKM dan performa ekonomi berdasarkan kecamatan di Bandar Lampung.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-4 py-2.5 bg-surface-container border border-outline-variant rounded-lg font-label-md text-sm font-bold text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <span>Tahun Anggaran {{ date('Y') }}</span>
                </button>
                <button @click="window.print()" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-label-md text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm">
                    <span class="material-symbols-outlined">print</span>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        <!-- Title for Printed Page Only -->
        <div class="hidden print:block text-center mb-8">
            <h2 class="text-xl font-bold uppercase tracking-wider">Laporan Perkembangan & Analisis Sebaran Wilayah UMKM</h2>
            <p class="text-sm text-gray-600 mt-1">Periode Laporan: Tahunan {{ date('Y') }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 print-full">
            <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm border-l-4 border-l-primary print-card">
                <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Kecamatan Terpadat</p>
                <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $mostPopulatedKecamatan }}</h3>
                <p class="text-sm text-on-surface-variant mt-2 flex items-center gap-1">
                    <span class="text-primary font-bold">{{ $kecamatanData[0]['count'] ?? 0 }} UMKM</span>
                    @if($totalStores > 0)
                        ({{ $mostPopulatedPercentage }}% dari total)
                    @endif
                </p>
            </div>
            <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm border-l-4 border-l-emerald-500 print-card">
                <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Pertumbuhan Tertinggi</p>
                <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $growthKecamatan }}</h3>
                <p class="text-sm text-on-surface-variant mt-2 flex items-center gap-1">
                    <span class="text-green-600 font-bold">{{ $growthTrend }}</span> pada kuartal ini
                </p>
            </div>
            <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm border-l-4 border-l-indigo-600 print-card">
                <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Sektor Dominan</p>
                <h3 class="text-2xl font-bold text-on-surface mt-2">{{ $dominantCategoryName }}</h3>
                <p class="text-sm text-on-surface-variant mt-2 flex items-center gap-1">
                    Tersebar di <span class="font-bold">20 Kecamatan</span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 print-full">
            <!-- Distribution Table -->
            <section class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm print-card">
                <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                    <h2 class="font-headline-md text-lg font-bold text-primary">Sebaran 5 Kecamatan Teratas</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kecamatan</th>
                                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Total UMKM</th>
                                <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tren</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach(array_slice($kecamatanData, 0, 5) as $kec)
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="px-6 py-4 font-bold text-sm text-on-surface">{{ $kec['name'] }}</td>
                                <td class="px-6 py-4 text-right font-mono text-sm">{{ number_format($kec['count']) }}</td>
                                <td class="px-6 py-4 flex items-center gap-1 text-xs font-bold {{ $kec['count'] > 0 ? 'text-green-600' : 'text-on-surface-variant' }}">
                                    <span class="material-symbols-outlined text-[16px]">{{ $kec['count'] > 0 ? 'trending_up' : 'trending_flat' }}</span> {{ $kec['trend'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3 bg-surface-container-lowest border-t border-outline-variant text-center print-hidden">
                    <button @click="showKecamatanModal = true" class="text-primary text-sm font-bold hover:underline bg-transparent border-none cursor-pointer">
                        Lihat Semua 20 Kecamatan
                    </button>
                </div>
            </section>

            <!-- Insights / AI analysis -->
            <section class="space-y-6">
                <div class="bg-indigo-900 text-white p-6 rounded-xl shadow-md relative overflow-hidden print-card">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                        <span class="material-symbols-outlined text-9xl">tips_and_updates</span>
                    </div>
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-300">lightbulb</span> 
                            <span>Insight AI Dinas Perdagangan</span>
                        </h3>
                        <p class="text-sm opacity-90 leading-relaxed mb-4">
                            {!! $aiInsights['summary'] !!}
                        </p>
                        <button @click="showAiModal = true" class="px-4 py-2 bg-white text-indigo-950 text-xs font-bold rounded-lg hover:bg-indigo-50 transition-colors shadow-sm print-hidden">
                            Lihat Analisis Lengkap
                        </button>
                    </div>
                </div>

                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm print-card">
                    <h3 class="font-bold text-on-surface mb-4">Program Pendampingan Aktif</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b pb-2">
                            <div>
                                <p class="text-sm font-bold text-on-surface">Digitalisasi Pasar Tradisional</p>
                                <p class="text-xs text-on-surface-variant">Kec. Tanjung Karang Pusat</p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded uppercase">Berjalan</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-on-surface">Pelatihan Ekspor Kriya</p>
                                <p class="text-xs text-on-surface-variant">Kec. Kemiling & Langkapura</p>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded uppercase">Persiapan</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Copy of Full Kecamatan list for Print View only -->
        <div class="hidden print:block mt-8 page-break-before">
            <h3 class="text-lg font-bold border-b pb-2 mb-4">Daftar Lengkap Sebaran UMKM per Kecamatan</h3>
            <div class="grid grid-cols-2 gap-4">
                <template x-for="kec in kecamatans" :key="kec.name">
                    <div class="flex justify-between items-center border-b py-1">
                        <span class="text-sm font-medium" x-text="kec.name"></span>
                        <span class="text-sm font-bold text-gray-800" x-text="`${kec.count.toLocaleString('id-ID')} Mitra`"></span>
                    </div>
                </template>
            </div>
            
            <!-- Executive Signature Copy -->
            <div class="mt-16 flex justify-end">
                <div class="text-center w-64">
                    <p class="text-xs text-gray-600">Bandar Lampung, {{ date('d F Y') }}</p>
                    <p class="text-xs font-bold mt-1">Kepala Dinas Perdagangan</p>
                    <div class="h-20"></div>
                    <p class="text-xs font-bold underline">H. Wilson Faisol, S.E., M.Si.</p>
                    <p class="text-[10px] text-gray-500">NIP. 19741014 199903 1 002</p>
                </div>
            </div>
        </div>

        <!-- Modal: Semua 20 Kecamatan -->
        <template x-if="showKecamatanModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                <div class="bg-surface border border-outline-variant w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden" @click.away="showKecamatanModal = false">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-lowest flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">location_city</span>
                                <span>Sebaran Mitra UMKM di 20 Kecamatan</span>
                            </h3>
                            <p class="text-xs text-on-surface-variant mt-0.5">Total Mitra Terdaftar: {{ number_format($totalStores) }} UMKM Aktif</p>
                        </div>
                        <button @click="showKecamatanModal = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-surface-variant text-on-surface transition-all">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <!-- Search Input inside modal -->
                    <div class="p-4 border-b border-outline-variant bg-surface-container-lowest">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                            <input type="text" x-model="searchKec" placeholder="Cari nama kecamatan..." class="w-full pl-10 pr-4 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-sm focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        </div>
                    </div>

                    <!-- Scrollable Content -->
                    <div class="flex-grow p-6 overflow-y-auto bg-surface-container-lowest">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="kec in filteredKecamatans()" :key="kec.name">
                                <div class="p-3 border border-outline-variant rounded-xl bg-surface hover:bg-surface-container-low transition-colors flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-sm text-on-surface" x-text="kec.name"></p>
                                        <p class="text-xs text-on-surface-variant mt-0.5" x-text="`${kec.count.toLocaleString('id-ID')} Mitra Terdaftar`"></p>
                                    </div>
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg flex items-center gap-0.5 shadow-sm"
                                          :class="kec.up ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'">
                                        <span class="material-symbols-outlined text-[14px]" x-text="kec.up ? 'trending_up' : 'trending_down'"></span>
                                        <span x-text="kec.trend"></span>
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex justify-end">
                        <button @click="showKecamatanModal = false" class="px-4 py-2 bg-primary text-on-primary font-bold text-sm rounded-lg hover:bg-primary-dark shadow-sm transition-all">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal: Insight AI Analisis Lengkap -->
        <template x-if="showAiModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                <div class="bg-surface border border-outline-variant w-full max-w-3xl rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden" @click.away="showAiModal = false">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-outline-variant bg-indigo-950 text-white flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-300 text-2xl animate-pulse">tips_and_updates</span>
                            <div>
                                <h3 class="font-bold text-lg">Dinas AI: Analisis Pasar Lengkap</h3>
                                <p class="text-xs text-indigo-200 mt-0.5">Analisis Prediktif & Rekomendasi Kebijakan galeriukmbdl</p>
                            </div>
                        </div>
                        <button @click="showAiModal = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-indigo-900 text-white transition-all">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <!-- Scrollable Content -->
                    <div class="flex-grow p-6 overflow-y-auto bg-surface-container-lowest space-y-6 text-sm">
                        <!-- Card 1 -->
                        <div class="bg-surface border border-outline-variant p-5 rounded-xl shadow-sm">
                            <h4 class="font-bold text-indigo-950 uppercase tracking-wider mb-3 flex items-center gap-1.5 border-b pb-2">
                                <span class="material-symbols-outlined text-indigo-600 text-sm font-black">bar_chart</span> 
                                <span>{{ $aiInsights['wilayah_unggulan']['title'] }}</span>
                            </h4>
                            <div class="space-y-3 leading-relaxed">
                                <ul class="list-disc pl-5 space-y-2">
                                    @foreach($aiInsights['wilayah_unggulan']['points'] as $point)
                                    <li>{!! $point !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-surface border border-outline-variant p-5 rounded-xl shadow-sm">
                            <h4 class="font-bold text-indigo-950 uppercase tracking-wider mb-3 flex items-center gap-1.5 border-b pb-2">
                                <span class="material-symbols-outlined text-indigo-600 text-sm font-black">trending_up</span> 
                                <span>{{ $aiInsights['proyeksi']['title'] }}</span>
                            </h4>
                            <div class="space-y-3 leading-relaxed">
                                <div class="grid grid-cols-3 gap-4 text-center my-4">
                                    @foreach($aiInsights['proyeksi']['metrics'] as $metric)
                                    <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-lg">
                                        <p class="text-2xl font-black text-indigo-700">{{ $metric['value'] }}</p>
                                        <p class="text-[10px] font-bold text-indigo-950 uppercase tracking-wider mt-1">{{ $metric['label'] }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-surface border border-outline-variant p-5 rounded-xl shadow-sm">
                            <h4 class="font-bold text-indigo-950 uppercase tracking-wider mb-3 flex items-center gap-1.5 border-b pb-2">
                                <span class="material-symbols-outlined text-indigo-600 text-sm font-black">gavel</span> 
                                <span>{{ $aiInsights['rekomendasi']['title'] }}</span>
                            </h4>
                            <div class="space-y-3 leading-relaxed">
                                <ol class="list-decimal pl-5 space-y-2">
                                    @foreach($aiInsights['rekomendasi']['points'] as $point)
                                    <li>{!! $point !!}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex justify-end">
                        <button @click="showAiModal = false" class="px-4 py-2 bg-indigo-950 text-white font-bold text-sm rounded-lg hover:bg-indigo-900 shadow-sm transition-all">
                            Tutup Analisis
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
