<x-buyer-layout title="Pesan Saya — Portal UMKM">
<div class="max-w-2xl mx-auto px-4 py-6 pb-24">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('catalog.index') }}"
           class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-primary leading-tight">Pesan Saya</h1>
            <p class="text-xs text-on-surface-variant">Hubungi pemilik toko dan tanyakan detail produk</p>
        </div>
    </div>

    {{-- Conversations List --}}
    <div class="space-y-3">
        @if(empty($conversations))
            <div class="bg-white border border-outline-variant rounded-2xl p-8 text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl text-outline-variant mb-3">forum</span>
                <h3 class="text-base font-bold text-on-surface mb-1">Belum Ada Chat</h3>
                <p class="text-xs max-w-sm mx-auto mb-6">Anda belum memulai percakapan dengan toko manapun. Cari produk menarik dan tanyakan langsung ke penjual!</p>
                <a href="{{ route('catalog.index') }}"
                   class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:opacity-90 active:scale-95 transition-all">
                    <span class="material-symbols-outlined" style="font-size:16px">storefront</span>
                    <span>Jelajahi Produk</span>
                </a>
            </div>
        @else
            @foreach($conversations as $conv)
                <a href="{{ route('chat.buyer', $conv['store']->slug) }}"
                   class="block bg-white border border-outline-variant hover:border-primary rounded-2xl p-4 transition-all hover:shadow-md">
                    <div class="flex items-center gap-3">
                        {{-- Store Avatar Icon --}}
                        <div class="w-12 h-12 bg-primary-container text-white rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined" style="font-size:24px">storefront</span>
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-bold text-on-surface truncate flex items-center gap-1.5">
                                    <span>{{ $conv['store']->name }}</span>
                                    <span class="material-symbols-outlined text-primary" style="font-size:14px" title="Mitra Terverifikasi">verified</span>
                                </h3>
                                <span class="text-[10px] text-on-surface-variant whitespace-nowrap">
                                    {{ $conv['last_message']->created_at?->diffForHumans() }}
                                </span>
                            </div>
                            
                            <p class="text-xs text-on-surface-variant truncate mt-1">
                                @if($conv['last_message']->sender_role === 'buyer')
                                    <strong class="text-primary font-semibold">Anda:</strong>
                                @endif
                                {{ $conv['last_message']->message }}
                            </p>
                        </div>

                        {{-- Unread Badge --}}
                        @if($conv['unread_count'] > 0)
                            <div class="w-5 h-5 bg-secondary rounded-full flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-bold text-white">{{ $conv['unread_count'] }}</span>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        @endif
    </div>

</div>
</x-buyer-layout>
