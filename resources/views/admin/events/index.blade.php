<x-app-layout>
    <div class="min-h-screen">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
            <div>
                <h1 class="font-headline-lg text-3xl font-bold text-primary mb-2">Kelola Event Dinas</h1>
                <p class="font-body-md text-on-surface-variant">Publikasikan informasi event, pameran, panggung hiburan, atau bimbingan teknis UMKM.</p>
            </div>
            <div>
                <a href="{{ route('admin.events.create') }}" class="bg-primary text-white px-5 py-3 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined" style="font-size:16px">add</span>
                    Tambah Event Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl mb-6 shadow-sm">
                <span class="material-symbols-outlined text-xl">check_circle</span>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Main Events Table Section -->
        <section class="bg-surface border border-outline-variant rounded-2xl overflow-hidden shadow-sm flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant text-[11px] font-black text-on-surface-variant uppercase tracking-wider">
                            <th class="px-6 py-4">Foto Banner</th>
                            <th class="px-6 py-4">Judul Event</th>
                            <th class="px-6 py-4">Tanggal Event</th>
                            <th class="px-6 py-4">Lokasi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/60">
                        @forelse($events as $event)
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="px-6 py-4 shrink-0">
                                    <div class="w-20 aspect-[16/9] bg-surface-container rounded-lg overflow-hidden border border-outline-variant/60">
                                        @if($event->image)
                                            <img src="{{ asset('storage/events/' . $event->image) }}" class="w-full h-full object-cover" alt="Banner Event">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant/40">
                                                <span class="material-symbols-outlined text-lg">campaign</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-sm text-on-surface leading-tight">{{ $event->title }}</p>
                                    <p class="text-[11px] text-on-surface-variant/80 mt-1 line-clamp-1 max-w-sm">{{ strip_tags($event->description) }}</p>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-on-surface">
                                    {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-on-surface-variant">
                                    {{ $event->location }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($event->status === 'active')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-100">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider bg-yellow-50 text-yellow-700 px-2.5 py-1 rounded-full border border-yellow-100">
                                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('events.show', $event->id) }}" target="_blank" class="w-8 h-8 rounded-full border border-outline-variant hover:bg-surface-container flex items-center justify-center text-on-surface-variant transition-colors" title="Lihat Halaman Publik">
                                            <span class="material-symbols-outlined" style="font-size:16px">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event->id) }}" class="w-8 h-8 rounded-full border border-outline-variant hover:bg-surface-container flex items-center justify-center text-primary transition-colors" title="Ubah">
                                            <span class="material-symbols-outlined" style="font-size:16px">edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-full border border-outline-variant hover:bg-red-50 flex items-center justify-center text-error transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">calendar_today</span>
                                    <p class="font-bold text-sm">Belum Ada Event Terdaftar</p>
                                    <p class="text-xs opacity-75">Klik "Tambah Event Baru" untuk mempublikasikan event perdana Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($events->hasPages())
                <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low/40">
                    {{ $events->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
