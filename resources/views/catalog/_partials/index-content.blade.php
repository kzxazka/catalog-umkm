{{-- ═══════════ HERO ═══════════ --}}
<section class="bg-gradient-to-br from-primary via-primary-container to-primary text-white py-8 md:py-14 px-4 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5" style="background-image:url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E\")"></div>
    <div class="max-w-screen-xl mx-auto relative">
        <div class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 text-white text-[11px] font-bold px-3 py-1 rounded-full mb-3">
            <span class="material-symbols-outlined" style="font-size:13px">verified</span>
            UMKM Terverifikasi Resmi
        </div>
        <h1 class="text-2xl md:text-4xl font-black mb-2 leading-tight">Etalase Digital<br class="sm:hidden"/> UMKM Lokal</h1>
        <p class="text-blue-200 text-sm md:text-base max-w-lg">Produk autentik dari pengrajin dan pelaku usaha lokal terverifikasi Dinas Perdagangan</p>
    </div>
</section>

{{-- ═══════════ FILTER TABS ═══════════ --}}
<div class="bg-white border-b border-outline-variant sticky top-14 md:top-16 z-30">
    <div class="max-w-screen-xl mx-auto px-4 md:px-6">
        <div class="flex gap-1 overflow-x-auto py-3 scrollbar-hide" id="category-scroll">
            <a href="{{ route('catalog.index', array_merge(request()->query(), ['category' => 'Semua'])) }}"
               class="shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all border
                      {{ $selectedCategory === 'Semua' ? 'bg-primary text-white border-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('catalog.index', array_merge(request()->query(), ['category' => $cat])) }}"
               class="shrink-0 px-4 py-2 rounded-full text-xs font-bold transition-all border
                      {{ $selectedCategory === $cat ? 'bg-primary text-white border-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 md:px-6 py-4 md:py-6">

    {{-- ═══════════ LOCATION FILTER ═══════════ --}}
    @if($cities->isNotEmpty())
    <div class="flex items-center gap-3 mb-4 flex-wrap">
        <div class="flex items-center gap-1.5 text-xs font-bold text-on-surface-variant">
            <span class="material-symbols-outlined" style="font-size:15px">location_on</span>
            Lokasi:
        </div>
        <form method="GET" action="{{ route('catalog.index') }}" class="flex items-center gap-2">
            @if($selectedCategory !== 'Semua')
                <input type="hidden" name="category" value="{{ $selectedCategory }}" />
            @endif
            <select name="city" onchange="this.form.submit()"
                    class="text-xs font-semibold px-3 py-2 border border-outline-variant rounded-full bg-white focus:border-primary focus:ring-1 focus:ring-primary outline-none cursor-pointer transition-all">
                <option value="">Semua Kota</option>
                @foreach($cities as $city)
                <option value="{{ $city }}" {{ $selectedCity === $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
            @if($selectedCity)
            <a href="{{ route('catalog.index', ['category' => $selectedCategory !== 'Semua' ? $selectedCategory : null]) }}"
               class="text-xs text-secondary font-bold hover:underline flex items-center gap-0.5">
                <span class="material-symbols-outlined" style="font-size:14px">close</span> Reset
            </a>
            @endif
        </form>
    </div>
    @endif

    {{-- ═══════════ MITRA OFFER (hanya buyer non-mitra) ═══════════ --}}
    @auth
    @if(auth()->user()->role === 'buyer' && !auth()->user()->hasPendingMitraApplication() && !auth()->user()->isApprovedMitra())
    <div class="bg-gradient-to-r from-primary to-primary-container rounded-2xl p-5 mb-5 flex items-start gap-4 relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 opacity-10 text-[80px] select-none">🏪</div>
        <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-white" style="font-size:22px">add_business</span>
        </div>
        <div class="flex-1">
            <h3 class="text-white font-black text-sm md:text-base mb-1">Punya Usaha? Buka Toko di Sini!</h3>
            <p class="text-blue-200 text-xs md:text-sm mb-3 leading-relaxed">Bergabung sebagai Mitra UMKM Dinas Perdagangan — gratis & terverifikasi resmi.</p>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('mitra.register') }}" class="bg-secondary text-white text-xs font-bold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
                    Daftar Mitra Sekarang
                </a>
                <a href="{{ route('mitra.status') }}" class="border border-white/30 text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-white/10 transition-colors">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
    @endif
    @endauth

    {{-- ═══════════ GATE BANNER (guest) ═══════════ --}}
    @guest
    <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-amber-200 rounded-2xl p-4 mb-5 flex items-center gap-3 flex-wrap">
        <span class="material-symbols-outlined text-secondary" style="font-size:26px">lock_open</span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-on-surface">Login untuk akses detail produk & kontak UMKM</p>
            <p class="text-xs text-on-surface-variant">Daftar gratis → klik produk → hubungi langsung</p>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('register') }}" class="bg-secondary text-white text-xs font-bold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">Daftar</a>
            <a href="{{ route('login') }}" class="border border-primary text-primary text-xs font-bold px-4 py-2 rounded-xl hover:bg-primary hover:text-white transition-all">Masuk</a>
        </div>
    </div>
    @endguest

    {{-- ═══════════ EVENT BANNER / SECTIONS ═══════════ --}}
    @if(isset($events) && $events->isNotEmpty())
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-black uppercase tracking-wider text-on-surface flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-primary rounded-full animate-pulse"></span>
                Event & Agenda Dinas Perdagangan
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($events as $event)
            <a href="{{ route('events.show', $event->id) }}" 
               class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col sm:flex-row h-full group">
                
                {{-- Event Image/Banner --}}
                <div class="w-full sm:w-40 aspect-[16/9] sm:aspect-square bg-surface-container shrink-0 overflow-hidden relative border-b sm:border-b-0 sm:border-r border-outline-variant/40">
                    @if($event->image)
                        <img src="{{ asset('storage/events/' . $event->image) }}" 
                             alt="{{ $event->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant/40">
                            <span class="material-symbols-outlined text-3xl">campaign</span>
                        </div>
                    @endif
                </div>

                {{-- Event Info --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="inline-flex items-center gap-0.5 text-[9px] font-black text-primary bg-primary/5 px-2 py-0.5 rounded-full border border-primary/10 uppercase tracking-widest">
                                <span class="material-symbols-outlined" style="font-size:11px">calendar_today</span>
                                {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M Y') }}
                            </span>
                            <span class="inline-flex items-center gap-0.5 text-[9px] font-black text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full uppercase tracking-widest">
                                <span class="material-symbols-outlined" style="font-size:11px">location_on</span>
                                {{ Str::limit($event->location, 18) }}
                            </span>
                        </div>
                        <h3 class="font-bold text-xs sm:text-sm uppercase text-on-surface tracking-tight group-hover:text-primary transition-colors leading-snug line-clamp-2 mb-2">
                            {{ $event->title }}
                        </h3>
                        <p class="text-[10px] sm:text-xs text-on-surface-variant/80 line-clamp-2 leading-relaxed">
                            {{ strip_tags($event->description) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-outline-variant/40">
                        <span class="text-[9px] font-black text-primary uppercase tracking-wider flex items-center gap-0.5">
                            Detail Informasi <span class="material-symbols-outlined" style="font-size:12px">arrow_right_alt</span>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══════════ HEADER COUNTS ═══════════ --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-bold text-on-surface">
            @if(isset($isFavoriteFilter) && $isFavoriteFilter)
                Produk Favorit Saya
            @else
                {{ $selectedCategory !== 'Semua' ? $selectedCategory : 'Semua Produk' }}
            @endif
            @if($selectedCity) <span class="text-on-surface-variant font-normal">· {{ $selectedCity }}</span> @endif
        </h2>
        <span class="text-xs text-on-surface-variant">{{ $products->total() }} produk</span>
    </div>

    {{-- ═══════════ PRODUCT GRID ═══════════ --}}
    @if($products->isNotEmpty())
    @php $userFavs = auth()->check() ? (auth()->user()->favorited_products ?? []) : []; @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
        @foreach($products as $product)
        @php
            $store = $product->store;
            $isFav = in_array((string) $product->id, array_map('strval', $userFavs));
        @endphp
        <div class="relative bg-white rounded-2xl border border-outline-variant overflow-hidden group hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
             x-data="{ fav: {{ $isFav ? 'true' : 'false' }} }">

            {{-- Favorite Button (buyer yang sudah login) --}}
            @auth
            @if(auth()->user()->role === 'buyer')
            <button
                type="button"
                @click.prevent="
                    fetch('{{ route('catalog.favorite.toggle', $product->id) }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
                    }).then(r => r.json()).then(d => { fav = d.favorited; });
                "
                :title="fav ? 'Hapus dari Favorit' : 'Tambah ke Favorit'"
                class="absolute top-2 right-2 z-10 w-8 h-8 rounded-full flex items-center justify-center transition-all shadow-sm"
                :class="fav ? 'bg-error text-white' : 'bg-white/80 text-on-surface-variant hover:bg-white hover:text-error'">
                <span class="material-symbols-outlined transition-all"
                      :style="fav ? 'font-size:17px; font-variation-settings: FILL 1, wght 600, GRAD 0, opsz 24' : 'font-size:17px'"
                      style="font-size:17px">favorite</span>
            </button>
            @endif
            @endauth

            <a href="{{ route('catalog.product', $product->id) }}" class="block" x-data="{ imgLoaded: false }">
                <div class="relative aspect-square bg-surface-container overflow-hidden">
                    @if(!empty($product->images))
                        {{-- Shimmer skeleton overlay --}}
                        <div x-show="!imgLoaded" class="absolute inset-0 shimmer-bg"></div>
                        <img src="{{ asset('storage/products/'.$product->images[0]) }}"
                             alt="{{ $product->name }}"
                             loading="lazy"
                             @load="imgLoaded = true"
                             class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500"
                             :class="imgLoaded ? 'opacity-100' : 'opacity-0'"
                             onerror="imgLoaded = true; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-surface-container-high\'><span class=\'material-symbols-outlined text-outline-variant\' style=\'font-size:28px\'>image</span></div>'" />
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-surface-container-high">
                        <span class="material-symbols-outlined text-outline-variant" style="font-size:28px">image</span>
                    </div>
                    @endif
                    <div class="absolute top-2 left-2 bg-primary/90 backdrop-blur-sm text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-0.5">
                        <span class="material-symbols-outlined" style="font-size:9px">verified</span> Mitra
                    </div>
                    @guest
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-3">
                        <span class="text-white text-[11px] font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined" style="font-size:13px">lock</span> Login untuk detail
                        </span>
                    </div>
                    @endguest
                </div>
                <div class="p-3">
                    @if($store)
                    <p class="text-[10px] font-bold text-secondary uppercase tracking-wider mb-1 truncate">{{ $store->name }}</p>
                    @endif
                    <h3 class="text-xs font-bold text-on-surface leading-tight line-clamp-2 mb-1">{{ $product->name }}</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-on-surface-variant">{{ $product->category }}</span>
                        @if($store && $store->city)
                        <span class="text-[9px] text-on-surface-variant flex items-center gap-0.5">
                            <span class="material-symbols-outlined" style="font-size:10px">location_on</span>{{ $store->city }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-center gap-1.5 flex-wrap">
        {{ $products->appends(request()->query())->onEachSide(1)->links('pagination::simple-bootstrap-4') }}
    </div>

    @else
    <div class="py-16 text-center">
        <span class="material-symbols-outlined text-outline-variant block mb-3" style="font-size:48px">inventory_2</span>
        <h3 class="text-base font-bold text-on-surface mb-1">Belum Ada Produk</h3>
        <p class="text-sm text-on-surface-variant">Coba kategori atau kota lain.</p>
    </div>
    @endif

</div>

<footer class="bg-primary text-blue-200 text-center py-5 px-4 text-xs mt-6">
    <strong class="text-white">Portal UMKM Dinas Perdagangan</strong>
    <span class="mx-2">·</span>© {{ date('Y') }} · Mendorong UMKM Lokal Go Digital
</footer>
