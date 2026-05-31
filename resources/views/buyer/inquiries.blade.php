<x-buyer-layout title="Pertanyaan Saya — Portal UMKM">
<div class="max-w-2xl mx-auto px-4 py-6 pb-24">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('buyer.profile') }}"
           class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-primary leading-tight">Pertanyaan Saya</h1>
            <p class="text-xs text-on-surface-variant font-medium">Riwayat pertanyaan produk Anda ke pelaku UMKM</p>
        </div>
    </div>

    {{-- List --}}
    <div class="space-y-4">
        @if($inquiries->isEmpty())
            <div class="bg-white border border-outline-variant rounded-2xl p-8 text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">help_center</span>
                <h3 class="text-base font-bold text-on-surface mb-1">Belum Ada Pertanyaan</h3>
                <p class="text-xs max-w-sm mx-auto mb-6">Anda belum pernah bertanya tentang produk apa pun. Jelajahi katalog produk kami untuk memulai!</p>
                <a href="{{ route('catalog.index') }}"
                   class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:opacity-90 active:scale-95 transition-all">
                    <span class="material-symbols-outlined" style="font-size:16px">search</span>
                    <span>Jelajahi Katalog</span>
                </a>
            </div>
        @else
            @foreach($inquiries as $inq)
                <div class="bg-white border border-outline-variant rounded-2xl p-4 md:p-5 transition-all hover:shadow-sm">
                    
                    {{-- Product Info --}}
                    @if($inq->product)
                    <div class="flex gap-3 pb-3 border-b border-dashed border-outline-variant">
                        <div class="w-12 h-12 rounded-lg bg-surface-container overflow-hidden shrink-0">
                            @if(!empty($inq->product->images))
                                <img src="{{ asset('storage/products/'.$inq->product->images[0]) }}"
                                     alt="{{ $inq->product->name }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-surface-container-high\'><span class=\'material-symbols-outlined text-outline-variant\' style=\'font-size:18px\'>image</span></div>'" />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-surface-container-high">
                                    <span class="material-symbols-outlined text-outline-variant" style="font-size:18px">image</span>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('catalog.product', $inq->product->id) }}" class="text-xs font-bold text-primary hover:underline line-clamp-1">
                                {{ $inq->product->name }}
                            </a>
                            <p class="text-[10px] text-on-surface-variant font-bold uppercase mt-0.5 flex items-center gap-1">
                                <span>{{ $inq->store->name ?? 'Toko Mitra' }}</span>
                                <span class="material-symbols-outlined text-primary" style="font-size:12px">verified</span>
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            @if($inq->reply)
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Dijawab
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Question --}}
                    <div class="mt-3">
                        <div class="flex items-start gap-2">
                            <span class="text-xs font-bold text-primary bg-primary/10 w-5 h-5 rounded-full flex items-center justify-center shrink-0">Q</span>
                            <div class="flex-1">
                                <p class="text-xs text-on-surface font-semibold leading-relaxed">{{ $inq->question }}</p>
                                <p class="text-[9px] text-on-surface-variant mt-1">{{ $inq->created_at?->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Answer / Reply --}}
                    @if($inq->reply)
                        <div class="mt-3 pt-3 border-t border-dashed border-outline-variant bg-surface-container-low/50 rounded-xl p-3">
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-bold text-white bg-secondary w-5 h-5 rounded-full flex items-center justify-center shrink-0">A</span>
                                <div class="flex-1">
                                    <p class="text-xs text-on-surface font-semibold leading-relaxed">{{ $inq->reply }}</p>
                                    <p class="text-[9px] text-on-surface-variant mt-1.5">
                                        Dijawab oleh penjual pada {{ \Carbon\Carbon::parse($inq->replied_at)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach

            <div class="mt-6">
                {{ $inquiries->links('pagination::simple-bootstrap-4') }}
            </div>
        @endif
    </div>

</div>
</x-buyer-layout>
