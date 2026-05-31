<x-app-layout>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-black text-on-surface">Kelola Mitra UMKM</h1>
            <p class="text-sm text-on-surface-variant mt-0.5">Daftar pengajuan kemitraan yang masuk</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-full">
                <span class="material-symbols-outlined" style="font-size:14px">pending</span>
                {{ $applications->where('status', 'pending')->count() }} Menunggu
            </span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl p-3.5" x-data x-init="setTimeout(() => $el.remove(), 4000)">
        <span class="material-symbols-outlined text-green-600" style="font-size:18px">check_circle</span>
        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl p-3.5">
        <span class="material-symbols-outlined text-red-600" style="font-size:18px">error</span>
        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Filter Tabs --}}
    @php
        $statusFilters = [
            ['key' => '',         'label' => 'Semua',    'icon' => 'list'],
            ['key' => 'pending',  'label' => 'Pending',  'icon' => 'pending'],
            ['key' => 'approved', 'label' => 'Disetujui','icon' => 'verified'],
            ['key' => 'rejected', 'label' => 'Ditolak',  'icon' => 'cancel'],
        ];
        $currentFilter = request('status', '');
    @endphp
    <div class="flex gap-2 mb-5 overflow-x-auto pb-1">
        @foreach($statusFilters as $sf)
        <a href="{{ route('admin.mitra', $sf['key'] ? ['status' => $sf['key']] : []) }}"
           class="shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold border transition-all
                  {{ $currentFilter === $sf['key'] ? 'bg-primary text-white border-primary' : 'bg-white border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary' }}">
            <span class="material-symbols-outlined" style="font-size:14px">{{ $sf['icon'] }}</span>
            {{ $sf['label'] }}
        </a>
        @endforeach
    </div>

    {{-- Table / Cards --}}
    @if($applications->isNotEmpty())

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-2xl border border-outline-variant overflow-hidden shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="text-left text-xs font-bold text-on-surface-variant px-5 py-3.5">Pemohon</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant px-4 py-3.5">Usaha</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant px-4 py-3.5">Lokasi</th>
                    <th class="text-center text-xs font-bold text-on-surface-variant px-4 py-3.5">Status</th>
                    <th class="text-left text-xs font-bold text-on-surface-variant px-4 py-3.5">Tanggal</th>
                    <th class="text-right text-xs font-bold text-on-surface-variant px-5 py-3.5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/40">
                @foreach($applications as $app)
                @php
                    $user = \App\Models\User::find($app->user_id);
                    $statusConfig = match($app->status) {
                        'approved' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'verified', 'label' => 'Disetujui'],
                        'rejected' => ['bg' => 'bg-red-50',   'text' => 'text-red-700',   'border' => 'border-red-200',   'icon' => 'cancel',   'label' => 'Ditolak'],
                        default    => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'pending',  'label' => 'Menunggu'],
                    };
                @endphp
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-white text-sm font-black shrink-0">
                                {{ strtoupper(substr($user?->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-on-surface truncate">{{ $user?->name ?? 'User Dihapus' }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $user?->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-sm font-semibold text-on-surface">{{ $app->business_name }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $app->business_category }}</p>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-xs text-on-surface-variant">{{ $app->city }}</p>
                        @if($app->district)<p class="text-xs text-on-surface-variant">Kec. {{ $app->district }}</p>@endif
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border text-[11px] font-bold px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined" style="font-size:11px">{{ $statusConfig['icon'] }}</span>
                            {{ $statusConfig['label'] }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-xs text-on-surface-variant">{{ $app->created_at?->format('d M Y') }}</p>
                        <p class="text-[10px] text-on-surface-variant/60">{{ $app->created_at?->diffForHumans() }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Detail Button --}}
                            <button
                                onclick="showDetail({{ json_encode(['name' => $user?->name, 'email' => $user?->email, 'business' => $app->business_name, 'category' => $app->business_category, 'address' => $app->business_address, 'city' => $app->city, 'district' => $app->district, 'desc' => $app->description, 'wa' => $app->whatsapp, 'ig' => $app->instagram, 'ktp' => $app->ktp_path, 'nib' => $app->nib_path, 'status' => $app->status, 'reason' => $app->rejection_reason]) }})"
                                class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all"
                                title="Lihat Detail">
                                <span class="material-symbols-outlined" style="font-size:16px">visibility</span>
                            </button>

                            @if($app->status === 'pending')
                            {{-- Approve --}}
                            <form method="POST" action="{{ route('admin.mitra.approve', $app->id) }}" onsubmit="return confirm('Setujui pengajuan dari {{ $user?->name }}?')">
                                @csrf
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-700 hover:bg-green-600 hover:text-white transition-all"
                                        title="Setujui">
                                    <span class="material-symbols-outlined" style="font-size:16px">check_circle</span>
                                </button>
                            </form>
                            {{-- Reject --}}
                            <button onclick="showRejectModal('{{ $app->id }}', '{{ addslashes($user?->name) }}')"
                                    class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition-all"
                                    title="Tolak">
                                <span class="material-symbols-outlined" style="font-size:16px">cancel</span>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-3">
        @foreach($applications as $app)
        @php
            $user = \App\Models\User::find($app->user_id);
            $statusConfig = match($app->status) {
                'approved' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'verified', 'label' => 'Disetujui'],
                'rejected' => ['bg' => 'bg-red-50',   'text' => 'text-red-700',   'border' => 'border-red-200',   'icon' => 'cancel',   'label' => 'Ditolak'],
                default    => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'pending',  'label' => 'Menunggu'],
            };
        @endphp
        <div class="bg-white rounded-2xl border border-outline-variant p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-white font-black shrink-0">
                        {{ strtoupper(substr($user?->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-sm text-on-surface">{{ $user?->name ?? 'User Dihapus' }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $user?->email }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} border text-[10px] font-bold px-2 py-1 rounded-full shrink-0">
                    <span class="material-symbols-outlined" style="font-size:10px">{{ $statusConfig['icon'] }}</span>
                    {{ $statusConfig['label'] }}
                </span>
            </div>
            <div class="bg-surface-container-low rounded-xl p-3 mb-3 space-y-1">
                <p class="text-xs font-bold text-on-surface">{{ $app->business_name }}</p>
                <p class="text-[11px] text-on-surface-variant">{{ $app->business_category }} · {{ $app->city }}</p>
                <p class="text-[11px] text-on-surface-variant">{{ $app->created_at?->format('d M Y') }}</p>
            </div>
            @if($app->status === 'pending')
            <div class="flex gap-2">
                <form method="POST" action="{{ route('admin.mitra.approve', $app->id) }}" class="flex-1" onsubmit="return confirm('Setujui pengajuan ini?')">
                    @csrf
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-green-600 text-white text-xs font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined" style="font-size:14px">check_circle</span>
                        Setujui
                    </button>
                </form>
                <button onclick="showRejectModal('{{ $app->id }}', '{{ addslashes($user?->name) }}')"
                        class="flex-1 py-2.5 rounded-xl border-2 border-error text-error text-xs font-bold hover:bg-error hover:text-white transition-all flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined" style="font-size:14px">cancel</span>
                    Tolak
                </button>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $applications->links('pagination::simple-bootstrap-4') }}
    </div>

    @else
    <div class="bg-white rounded-2xl border border-outline-variant p-16 text-center shadow-sm">
        <span class="material-symbols-outlined text-outline-variant block mb-3" style="font-size:52px">group_add</span>
        <h3 class="font-bold text-on-surface mb-1">Belum Ada Pengajuan</h3>
        <p class="text-sm text-on-surface-variant">Belum ada buyer yang mendaftar sebagai mitra.</p>
    </div>
    @endif

    {{-- MODAL: Reject --}}
    <div id="reject-modal" class="hidden fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeRejectModal()"></div>
        <div class="relative bg-white w-full sm:w-96 rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-error px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-white" style="font-size:20px">cancel</span>
                    <h3 class="text-white font-bold text-sm">Tolak Pengajuan</h3>
                </div>
                <button onclick="closeRejectModal()" class="text-white/70 hover:text-white">
                    <span class="material-symbols-outlined" style="font-size:20px">close</span>
                </button>
            </div>
            <div class="p-5">
                <p class="text-sm text-on-surface mb-4">Anda akan menolak pengajuan dari <strong id="reject-applicant-name">-</strong>. Berikan alasan penolakan:</p>
                <form id="reject-form" method="POST">
                    @csrf
                    <textarea name="reason" rows="3" required
                              placeholder="Contoh: Dokumen NIB tidak valid, mohon upload ulang yang terbaru..."
                              class="w-full px-3 py-2.5 border border-outline-variant rounded-xl focus:border-error focus:ring-1 focus:ring-error outline-none text-sm resize-none font-sans mb-4"></textarea>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeRejectModal()"
                                class="flex-1 py-2.5 rounded-xl border border-outline-variant text-on-surface font-bold text-sm">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-xl bg-error text-white font-bold text-sm hover:opacity-90 transition-opacity">
                            Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Detail --}}
    <div id="detail-modal" class="hidden fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('detail-modal').classList.add('hidden')"></div>
        <div class="relative bg-white w-full sm:w-[500px] max-h-[85vh] rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-primary px-5 py-4 flex items-center justify-between">
                <h3 class="text-white font-bold text-sm">Detail Pengajuan Mitra</h3>
                <button onclick="document.getElementById('detail-modal').classList.add('hidden')" class="text-blue-200 hover:text-white">
                    <span class="material-symbols-outlined" style="font-size:20px">close</span>
                </button>
            </div>
            <div class="overflow-y-auto p-5 space-y-3" id="detail-content">
                {{-- Diisi via JS --}}
            </div>
        </div>
    </div>

    <script>
    function showRejectModal(id, name) {
        document.getElementById('reject-form').action = `/admin/mitra/${id}/reject`;
        document.getElementById('reject-applicant-name').textContent = name;
        document.getElementById('reject-modal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('reject-modal').classList.add('hidden');
    }
    function showDetail(data) {
        const statusMap = { pending: '⏳ Menunggu', approved: '✅ Disetujui', rejected: '❌ Ditolak' };
        const rows = [
            ['Nama Pemohon', data.name],
            ['Email', data.email],
            ['Nama Usaha', data.business],
            ['Kategori', data.category],
            ['Alamat', data.address],
            ['Kota / Kecamatan', [data.city, data.district ? 'Kec. '+data.district : null].filter(Boolean).join(', ')],
            ['Deskripsi Usaha', data.desc],
            ['WhatsApp', data.wa],
            ['Instagram', data.ig || '-'],
            ['Status', statusMap[data.status] || data.status],
        ];
        if (data.reason) rows.push(['Alasan Penolakan', data.reason]);

        let html = rows.map(([k, v]) => `
            <div class="flex gap-3">
                <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider w-32 shrink-0 pt-0.5">${k}</p>
                <p class="text-sm text-on-surface flex-1">${v || '-'}</p>
            </div>
        `).join('<div class="h-px bg-outline-variant/30"></div>');

        if (data.ktp) html += `<div class="pt-2"><p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Dokumen KTP</p><p class="text-xs text-on-surface-variant italic">File tersimpan di server (private)</p></div>`;
        if (data.nib) html += `<div><p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Dokumen NIB</p><p class="text-xs text-on-surface-variant italic">File tersimpan di server (private)</p></div>`;

        document.getElementById('detail-content').innerHTML = html;
        document.getElementById('detail-modal').classList.remove('hidden');
    }
    </script>
</x-app-layout>
