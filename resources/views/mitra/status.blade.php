<x-buyer-layout title="Status Kemitraan — Portal UMKM">
<div class="max-w-lg mx-auto px-4 py-6 pb-24">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('buyer.profile') }}"
           class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-primary leading-tight">Status Kemitraan</h1>
            <p class="text-xs text-on-surface-variant">Pantau progres pengajuan mitra Anda</p>
        </div>
    </div>

    @if(!$application)
    {{-- Belum pernah daftar --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-8 text-center">
        <span class="material-symbols-outlined text-outline-variant text-5xl mb-4 block">store</span>
        <h2 class="text-lg font-bold text-on-surface mb-2">Belum Ada Pengajuan</h2>
        <p class="text-sm text-on-surface-variant mb-6">Anda belum pernah mengajukan pendaftaran kemitraan UMKM.</p>
        <a href="{{ route('mitra.register') }}"
           class="inline-flex items-center gap-2 bg-secondary text-white px-6 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined" style="font-size:16px">add_business</span>
            Daftar Jadi Mitra
        </a>
    </div>

    @else
    {{-- Status card --}}
    @php
        $statusConfig = [
            'pending'  => ['color' => 'amber',  'icon' => 'hourglass_top', 'label' => 'Menunggu Verifikasi', 'bg' => 'bg-amber-50',  'border' => 'border-amber-200',  'text' => 'text-amber-800'],
            'approved' => ['color' => 'green',   'icon' => 'verified',      'label' => 'Disetujui',           'bg' => 'bg-green-50',  'border' => 'border-green-200',  'text' => 'text-green-800'],
            'rejected' => ['color' => 'red',     'icon' => 'cancel',        'label' => 'Perlu Ditinjau Ulang','bg' => 'bg-red-50',    'border' => 'border-red-200',    'text' => 'text-red-800'],
        ];
        $cfg = $statusConfig[$application->status] ?? $statusConfig['pending'];
    @endphp

    <div class="{{ $cfg['bg'] }} {{ $cfg['border'] }} border rounded-2xl p-6 mb-5 text-center">
        <span class="material-symbols-outlined {{ $cfg['text'] }} text-5xl mb-3 block">{{ $cfg['icon'] }}</span>
        <span class="inline-block px-4 py-1.5 rounded-full bg-white border {{ $cfg['border'] }} {{ $cfg['text'] }} text-xs font-bold mb-3">
            {{ $cfg['label'] }}
        </span>
        <h2 class="text-lg font-bold {{ $cfg['text'] }}">{{ $application->business_name }}</h2>
        <p class="text-xs {{ $cfg['text'] }} opacity-75 mt-1">{{ $application->business_category }}</p>
    </div>

    {{-- Timeline --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-6 mb-5">
        <h3 class="text-sm font-bold text-on-surface mb-4">Progres Pengajuan</h3>
        @php
            $steps = [
                ['done' => true,                                    'label' => 'Formulir dikirim',          'time' => $application->created_at?->format('d M Y, H:i')],
                ['done' => true,                                    'label' => 'Dokumen diterima admin',    'time' => $application->created_at?->addHours(1)->format('d M Y, H:i')],
                ['done' => in_array($application->status, ['approved','rejected']), 'label' => 'Proses verifikasi',  'time' => $application->reviewed_at?->format('d M Y, H:i')],
                ['done' => $application->status === 'approved',     'label' => 'Pengajuan disetujui',       'time' => $application->status === 'approved' ? $application->reviewed_at?->format('d M Y, H:i') : null],
            ];
        @endphp
        <div class="space-y-0">
            @foreach($steps as $i => $step)
            <div class="flex gap-4 {{ !$loop->last ? 'pb-4' : '' }}">
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full {{ $step['done'] ? 'bg-primary' : 'bg-surface-container border-2 border-outline-variant' }} flex items-center justify-center shrink-0">
                        @if($step['done'])
                            <span class="material-symbols-outlined text-white" style="font-size:14px">check</span>
                        @else
                            <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                        @endif
                    </div>
                    @if(!$loop->last)
                    <div class="w-0.5 flex-1 {{ $step['done'] ? 'bg-primary' : 'bg-outline-variant' }} mt-1"></div>
                    @endif
                </div>
                <div class="pt-0.5 pb-4">
                    <p class="text-sm font-{{ $step['done'] ? 'bold' : 'medium' }} {{ $step['done'] ? 'text-on-surface' : 'text-on-surface-variant' }}">
                        {{ $step['label'] }}
                    </p>
                    @if($step['time'])
                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $step['time'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Detail Pengajuan --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-6 mb-5">
        <h3 class="text-sm font-bold text-on-surface mb-4">Detail Pengajuan</h3>
        <div class="space-y-3 text-sm">
            @foreach([
                ['label' => 'Lokasi', 'value' => $application->city.', Kec. '.$application->district],
                ['label' => 'WhatsApp', 'value' => $application->whatsapp],
                ['label' => 'Tanggal Daftar', 'value' => $application->created_at?->format('d F Y')],
            ] as $row)
            <div class="flex justify-between items-start">
                <span class="text-on-surface-variant text-xs">{{ $row['label'] }}</span>
                <span class="text-on-surface font-semibold text-xs text-right max-w-[60%]">{{ $row['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Alasan penolakan --}}
    @if($application->status === 'rejected' && $application->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-5">
        <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-2">Catatan dari Dinas Perdagangan</p>
        <p class="text-sm text-red-800">{{ $application->rejection_reason }}</p>
        <a href="{{ route('mitra.register') }}"
           class="mt-4 inline-flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
            Ajukan Kembali
        </a>
    </div>
    @endif

    @endif

</div>
</x-buyer-layout>
