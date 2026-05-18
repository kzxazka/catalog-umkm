<x-app-layout>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
        <div>
            <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Laporan Wilayah</h1>
            <p class="font-body-md text-on-surface-variant">Analisis sebaran UMKM dan performa ekonomi berdasarkan kecamatan di Bandar Lampung.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2.5 bg-surface-container border border-outline-variant rounded-lg font-label-md text-sm font-bold text-on-surface hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined">calendar_month</span>
                <span>Kuartal III 2023</span>
            </button>
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-md text-sm font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined">print</span>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm border-l-4 border-l-primary">
            <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Kecamatan Terpadat</p>
            <h3 class="text-2xl font-bold text-on-surface mt-2">Tanjung Karang Pusat</h3>
            <p class="text-sm text-on-surface-variant mt-2 flex items-center gap-1">
                <span class="text-primary font-bold">2.450 UMKM</span> (20.5% dari total)
            </p>
        </div>
        <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm border-l-4 border-l-secondary">
            <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Pertumbuhan Tertinggi</p>
            <h3 class="text-2xl font-bold text-on-surface mt-2">Kemiling</h3>
            <p class="text-sm text-on-surface-variant mt-2 flex items-center gap-1">
                <span class="text-green-600 font-bold">+15.2%</span> pada kuartal ini
            </p>
        </div>
        <div class="bg-surface border border-outline-variant p-6 rounded-xl shadow-sm border-l-4 border-l-tertiary">
            <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Sektor Dominan</p>
            <h3 class="text-2xl font-bold text-on-surface mt-2">Kuliner & F&B</h3>
            <p class="text-sm text-on-surface-variant mt-2 flex items-center gap-1">
                Tersebar di <span class="font-bold">18 Kecamatan</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Distribution Table -->
        <section class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h2 class="font-headline-md text-lg font-bold text-primary">Sebaran per Kecamatan</h2>
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
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 font-bold text-sm text-on-surface">Tanjung Karang Pusat</td>
                            <td class="px-6 py-4 text-right font-mono text-sm">2,450</td>
                            <td class="px-6 py-4 text-green-600 flex items-center gap-1 text-xs font-bold">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span> +5.2%
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 font-bold text-sm text-on-surface">Kedaton</td>
                            <td class="px-6 py-4 text-right font-mono text-sm">1,820</td>
                            <td class="px-6 py-4 text-green-600 flex items-center gap-1 text-xs font-bold">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span> +3.8%
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 font-bold text-sm text-on-surface">Kemiling</td>
                            <td class="px-6 py-4 text-right font-mono text-sm">1,540</td>
                            <td class="px-6 py-4 text-green-600 flex items-center gap-1 text-xs font-bold">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span> +15.2%
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 font-bold text-sm text-on-surface">Rajabasa</td>
                            <td class="px-6 py-4 text-right font-mono text-sm">1,210</td>
                            <td class="px-6 py-4 text-error flex items-center gap-1 text-xs font-bold">
                                <span class="material-symbols-outlined text-[16px]">trending_down</span> -1.5%
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4 font-bold text-sm text-on-surface">Sukabumi</td>
                            <td class="px-6 py-4 text-right font-mono text-sm">980</td>
                            <td class="px-6 py-4 text-green-600 flex items-center gap-1 text-xs font-bold">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span> +2.1%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 bg-surface-container-lowest border-t border-outline-variant text-center">
                <a href="#" class="text-primary text-sm font-bold hover:underline">Lihat Semua 20 Kecamatan</a>
            </div>
        </section>

        <!-- Insights -->
        <section class="space-y-6">
            <div class="bg-primary text-on-primary p-6 rounded-xl shadow-md relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                    <span class="material-symbols-outlined text-9xl">tips_and_updates</span>
                </div>
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined">lightbulb</span> Insight AI
                    </h3>
                    <p class="text-sm opacity-90 leading-relaxed mb-4">
                        Terdapat potensi pengembangan UMKM sektor <strong>Fashion</strong> di kecamatan <strong>Rajabasa</strong> mengingat tingginya populasi mahasiswa namun rasio toko pakaian masih rendah (1:450).
                    </p>
                    <button class="px-4 py-2 bg-on-primary text-primary text-xs font-bold rounded hover:bg-primary-container transition-colors">
                        Lihat Analisis Lengkap
                    </button>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="font-bold text-on-surface mb-4">Program Pendampingan Aktif</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
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
</x-app-layout>
