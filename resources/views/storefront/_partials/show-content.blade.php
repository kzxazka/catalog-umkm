    {{-- ═══════════ STORE HERO / HEADER ═══════════ --}}
    <div class="relative" x-data="{ following: {{ $isFollowing ? 'true' : 'false' }} }">
        @if($store->header_image)
        <div class="h-36 md:h-52 overflow-hidden">
            <img src="{{ asset('storage/'.$store->header_image) }}" alt="{{ $store->name }}" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/30 to-transparent"></div>
        </div>
        @else
        <div class="h-28 md:h-44 bg-gradient-to-br from-primary via-primary-container to-primary relative">
            <div class="absolute inset-0 opacity-10" style="background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff'%3E%3Cpath d='M20 20v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4z'/%3E%3C/g%3E%3C/svg%3E\")"></div>
        </div>
        @endif

        @auth
        <a href="{{ route('catalog.index') }}"
           class="absolute top-3 left-3 md:top-4 md:left-4 w-9 h-9 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/50 transition-all">
            <span class="material-symbols-outlined" style="font-size:20px">arrow_back</span>
        </a>
        @endauth

        <div class="max-w-screen-xl mx-auto px-4 md:px-6">
            <div class="relative -mt-14 md:-mt-16 mb-0">
                <div class="bg-white rounded-2xl border border-outline-variant shadow-sm p-4 md:p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-white text-2xl font-black shrink-0 border-4 border-white shadow-md">
                            {{ strtoupper(substr($store->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <div>
                                    <h1 class="text-lg md:text-xl font-black text-on-surface leading-tight">{{ $store->name }}</h1>
                                    <div class="flex items-center gap-1.5 text-green-700 text-xs font-bold mt-1">
                                        <span class="material-symbols-outlined" style="font-size:13px">verified</span>
                                        Mitra Terverifikasi
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    @if(!empty($store->social_links['whatsapp']))
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $store->social_links['whatsapp']) }}"
                                       target="_blank"
                                       class="w-9 h-9 rounded-xl bg-[#25d366] flex items-center justify-center text-white hover:opacity-90 transition-opacity">
                                        <span class="material-symbols-outlined" style="font-size:18px">chat</span>
                                    </a>
                                    @endif
                                    @if(!empty($store->social_links['instagram']))
                                    <a href="{{ $store->social_links['instagram'] }}" target="_blank"
                                       class="w-9 h-9 rounded-xl flex items-center justify-center text-white hover:opacity-90 transition-opacity"
                                       style="background:linear-gradient(135deg,#f09433,#dc2743,#bc1888)">
                                        <span class="material-symbols-outlined" style="font-size:18px">photo_camera</span>
                                    </a>
                                    @endif
                                </div>
                            </div>

                            @if($store->description)
                            <p class="text-xs md:text-sm text-on-surface-variant mt-2 leading-relaxed line-clamp-2 md:line-clamp-none">
                                {{ $store->description }}
                            </p>
                            @endif

                            @if($store->city || $store->address)
                            <div class="flex items-center gap-1 text-xs text-on-surface-variant mt-2">
                                <span class="material-symbols-outlined" style="font-size:13px">location_on</span>
                                {{ implode(', ', array_filter([$store->address, $store->city, $store->district ? 'Kec. '.$store->district : null])) }}
                            </div>
                            @endif

                            <div class="flex gap-2 mt-3 flex-wrap items-center">
                                <span class="inline-flex items-center gap-1 bg-surface-container-low text-on-surface text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="material-symbols-outlined" style="font-size:13px">inventory_2</span>
                                    {{ $totalProducts }} Produk
                                </span>
                                <span class="inline-flex items-center gap-1 bg-surface-container-low text-on-surface text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="material-symbols-outlined" style="font-size:13px">touch_app</span>
                                    {{ number_format($totalClicks) }} Interaksi
                                </span>

                                {{-- Follow button — hanya buyer --}}
                                @auth
                                @if(auth()->user()->role === 'buyer')
                                <button
                                    type="button"
                                    @click="
                                        fetch('{{ route('store.follow.toggle', $store->slug) }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
                                        }).then(r => r.json()).then(d => { following = d.following; });
                                    "
                                    :class="following ? 'bg-primary text-white' : 'bg-white border border-primary text-primary hover:bg-primary hover:text-white'"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full transition-all">
                                    <span class="material-symbols-outlined" style="font-size:14px"
                                          x-text="following ? 'person_check' : 'person_add'">person_add</span>
                                    <span x-text="following ? 'Mengikuti' : 'Ikuti Toko'">{{ $isFollowing ? 'Mengikuti' : 'Ikuti Toko' }}</span>
                                </button>
                                @endif
                                @endauth
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ TABS NAVIGATION ═══════════ --}}
    <div class="bg-white border-b border-outline-variant sticky top-14 md:top-16 z-30 mt-3">
        <div class="max-w-screen-xl mx-auto px-4 md:px-6">
            <div class="flex gap-0">
                @foreach([
                    ['key' => 'toko',     'icon' => 'storefront',  'label' => 'Toko'],
                    ['key' => 'produk',   'icon' => 'inventory_2', 'label' => 'Produk'],
                    ['key' => 'kategori', 'icon' => 'category',    'label' => 'Kategori'],
                ] as $t)
                <a href="{{ route('store.public', $store->slug) }}?tab={{ $t['key'] }}"
                   class="flex-1 flex items-center justify-center gap-1.5 py-3 text-xs font-bold border-b-2 transition-all
                          {{ $tab === $t['key'] ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">
                    <span class="material-symbols-outlined" style="font-size:17px">{{ $t['icon'] }}</span>
                    <span class="hidden sm:inline">{{ $t['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════ TAB CONTENT ═══════════ --}}
    <div class="max-w-screen-xl mx-auto px-4 md:px-6 py-5">

        {{-- ─── TAB: TOKO ─── --}}
        @if($tab === 'toko')

        <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="bg-white rounded-2xl border border-outline-variant p-3 md:p-4 text-center">
                <p class="text-xl md:text-2xl font-black text-primary">{{ $totalProducts }}</p>
                <p class="text-[11px] text-on-surface-variant font-semibold mt-0.5">Total Produk</p>
            </div>
            <div class="bg-white rounded-2xl border border-outline-variant p-3 md:p-4 text-center">
                <p class="text-xl md:text-2xl font-black text-primary">{{ $categories->count() }}</p>
                <p class="text-[11px] text-on-surface-variant font-semibold mt-0.5">Kategori</p>
            </div>
            <div class="bg-white rounded-2xl border border-outline-variant p-3 md:p-4 text-center">
                <p class="text-xl md:text-2xl font-black text-secondary">{{ number_format($totalClicks) }}</p>
                <p class="text-[11px] text-on-surface-variant font-semibold mt-0.5">Interaksi</p>
            </div>
        </div>

        @if($topProducts->isNotEmpty())
        <div class="mb-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-sm text-on-surface flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary" style="font-size:18px">local_fire_department</span>
                    Produk Terlaris
                </h2>
                <a href="?tab=produk" class="text-xs font-bold text-primary hover:underline">Lihat Semua →</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($topProducts as $p)
                <a href="{{ route('store.product', ['slug' => $store->slug, 'id' => $p->id]) }}"
                   class="bg-white rounded-2xl border border-outline-variant overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all group"
                   x-data="{ imgLoaded: false }">
                    <div class="relative aspect-square overflow-hidden bg-surface-container">
                        @if(!empty($p->images))
                        <div x-show="!imgLoaded" class="absolute inset-0 shimmer-bg z-10"></div>
                        <img src="{{ asset('storage/products/'.$p->images[0]) }}" alt="{{ $p->name }}"
                             @load="imgLoaded = true"
                             class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500"
                             :class="imgLoaded ? 'opacity-100' : 'opacity-0'" loading="lazy"
                             onerror="imgLoaded = true" />
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-outline-variant" style="font-size:28px">image</span>
                        </div>
                        @endif
                        @php $total = is_array($p->clicks) ? array_sum($p->clicks) : 0; @endphp
                        @if($total > 0)
                        <div class="absolute top-2 right-2 bg-secondary text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                            🔥 {{ $total }}x
                        </div>
                        @endif
                    </div>
                    <div class="p-2.5">
                        <h3 class="text-xs font-bold text-on-surface line-clamp-2">{{ $p->name }}</h3>
                        <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $p->category }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-sm text-on-surface">Semua Produk</h2>
                <a href="?tab=produk" class="text-xs font-bold text-primary hover:underline">Lihat Semua →</a>
            </div>
            @if($allProducts->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($allProducts->take(8) as $p)
                <a href="{{ route('store.product', ['slug' => $store->slug, 'id' => $p->id]) }}"
                   class="bg-white rounded-2xl border border-outline-variant overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all group"
                   x-data="{ imgLoaded: false }">
                    <div class="relative aspect-square overflow-hidden bg-surface-container">
                        @if(!empty($p->images))
                        <div x-show="!imgLoaded" class="absolute inset-0 shimmer-bg z-10"></div>
                        <img src="{{ asset('storage/products/'.$p->images[0]) }}" alt="{{ $p->name }}"
                             @load="imgLoaded = true"
                             class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500"
                             :class="imgLoaded ? 'opacity-100' : 'opacity-0'" loading="lazy"
                             onerror="imgLoaded = true" />
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-outline-variant" style="font-size:28px">image</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-2.5">
                        <h3 class="text-xs font-bold text-on-surface line-clamp-2">{{ $p->name }}</h3>
                        <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $p->category }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            @if($allProducts->count() > 8)
            <div class="text-center mt-4">
                <a href="?tab=produk" class="inline-flex items-center gap-1.5 bg-surface-container text-primary text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-primary hover:text-white transition-all">
                    Lihat {{ $allProducts->count() - 8 }} Produk Lainnya
                    <span class="material-symbols-outlined" style="font-size:16px">arrow_forward</span>
                </a>
            </div>
            @endif
            @else
            <div class="py-12 text-center">
                <span class="material-symbols-outlined text-outline-variant block mb-2" style="font-size:40px">inventory_2</span>
                <p class="text-sm text-on-surface-variant">Belum ada produk.</p>
            </div>
            @endif
        </div>

        {{-- ─── TAB: PRODUK ─── --}}
        @elseif($tab === 'produk')

        @if($categories->isNotEmpty())
        <div class="flex gap-2 overflow-x-auto pb-1 mb-4">
            <a href="?tab=produk"
               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-bold border transition-all
                      {{ !$selectedCategory ? 'bg-primary text-white border-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="?tab=produk&category={{ urlencode($cat) }}"
               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-bold border transition-all
                      {{ $selectedCategory === $cat ? 'bg-primary text-white border-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
        @endif

        <div class="flex items-center justify-between mb-3">
            <p class="text-xs text-on-surface-variant">{{ $products->total() }} produk {{ $selectedCategory ? '· '.$selectedCategory : '' }}</p>
        </div>

        @if($products->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($products as $product)
            <a href="{{ route('store.product', ['slug' => $store->slug, 'id' => $product->id]) }}"
               class="bg-white rounded-2xl border border-outline-variant overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all group"
               x-data="{ imgLoaded: false }">
                <div class="relative aspect-square overflow-hidden bg-surface-container">
                    @if(!empty($product->images))
                    <div x-show="!imgLoaded" class="absolute inset-0 shimmer-bg z-10"></div>
                    <img src="{{ asset('storage/products/'.$product->images[0]) }}" alt="{{ $product->name }}"
                         @load="imgLoaded = true"
                         class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500"
                         :class="imgLoaded ? 'opacity-100' : 'opacity-0'" loading="lazy"
                         onerror="imgLoaded = true" />
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-outline-variant" style="font-size:28px">image</span>
                    </div>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="text-xs font-bold text-on-surface line-clamp-2 mb-1">{{ $product->name }}</h3>
                    <span class="text-[10px] text-on-surface-variant">{{ $product->category }}</span>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-6 flex justify-center gap-1.5 flex-wrap">
            {{ $products->appends(['tab' => 'produk', 'category' => $selectedCategory])->links('pagination::simple-bootstrap-4') }}
        </div>
        @else
        <div class="py-16 text-center">
            <span class="material-symbols-outlined text-outline-variant block mb-2" style="font-size:40px">inventory_2</span>
            <p class="text-on-surface-variant text-sm">Tidak ada produk ditemukan.</p>
        </div>
        @endif

        {{-- ─── TAB: KATEGORI ─── --}}
        @elseif($tab === 'kategori')

        @if($categories->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($categories as $cat)
            @php
                $catCount = $allProducts->where('category', $cat)->count();
                $catImg   = $allProducts->where('category', $cat)->first()?->images[0] ?? null;
            @endphp
            <a href="?tab=produk&category={{ urlencode($cat) }}"
               class="bg-white rounded-2xl border border-outline-variant overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all group relative">
                <div class="aspect-video overflow-hidden bg-surface-container">
                    @if($catImg)
                    <img src="{{ asset('storage/products/'.$catImg) }}" alt="{{ $cat }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-70" loading="lazy" />
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-surface-container to-surface-container-highest flex items-center justify-center">
                        <span class="material-symbols-outlined text-outline" style="font-size:32px">category</span>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                        <div>
                            <p class="text-white font-black text-sm leading-tight">{{ $cat }}</p>
                            <p class="text-white/70 text-[11px]">{{ $catCount }} produk</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="py-16 text-center">
            <span class="material-symbols-outlined text-outline-variant block mb-2" style="font-size:40px">category</span>
            <p class="text-on-surface-variant text-sm">Belum ada kategori produk.</p>
        </div>
        @endif

        @endif

    </div>

    <footer class="bg-primary text-blue-200 text-center py-5 px-4 text-xs mt-6">
        <strong class="text-white">{{ $store->name }}</strong>
        <span class="mx-2">·</span>Mitra UMKM Dinas Perdagangan<span class="mx-2">·</span>© {{ date('Y') }}
    </footer>
