    <div class="max-w-screen-xl mx-auto px-4 md:px-6 py-4 md:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-xs text-on-surface-variant mb-4 flex-wrap">
            <a href="{{ route('catalog.index') }}" class="hover:text-primary font-semibold transition-colors">Katalog</a>
            <span class="material-symbols-outlined" style="font-size:13px">chevron_right</span>
            @if($product->category)
            <a href="{{ route('catalog.index', ['category' => $product->category]) }}" class="hover:text-primary transition-colors">{{ $product->category }}</a>
            <span class="material-symbols-outlined" style="font-size:13px">chevron_right</span>
            @endif
            <span class="text-on-surface font-semibold truncate max-w-[160px]">{{ $product->name }}</span>
        </nav>

        @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl p-3">
            <span class="material-symbols-outlined text-green-600" style="font-size:18px">check_circle</span>
            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        {{-- ═══════════ LAYOUT DESKTOP: 2 kolom ═══════════ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

            {{-- GALLERY --}}
            <div x-data="{ active: 0 }" class="bg-white rounded-2xl border border-outline-variant overflow-hidden">
                @if(!empty($product->images) && count($product->images) > 0)
                @php $imgs = array_map(fn($i) => asset('storage/products/'.$i), $product->images); @endphp
                <div class="aspect-square md:aspect-4/3 overflow-hidden bg-surface-container">
                    <img :src="{{ json_encode($imgs) }}[active]"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover transition-opacity duration-300" />
                </div>
                @if(count($product->images) > 1)
                <div class="flex gap-2 p-3 overflow-x-auto">
                    @foreach($product->images as $i => $img)
                    <button @click="active = {{ $i }}"
                            class="w-14 h-14 md:w-16 md:h-16 rounded-xl overflow-hidden border-2 shrink-0 transition-all"
                            :class="active === {{ $i }} ? 'border-primary' : 'border-transparent hover:border-outline-variant'">
                        <img src="{{ asset('storage/products/'.$img) }}" alt="" class="w-full h-full object-cover" />
                    </button>
                    @endforeach
                </div>
                @endif
                @else
                <div class="aspect-square flex flex-col items-center justify-center text-outline-variant bg-surface-container">
                    <span class="material-symbols-outlined" style="font-size:48px">image</span>
                    <p class="text-xs mt-2">Belum ada foto</p>
                </div>
                @endif
            </div>

            {{-- PRODUCT INFO --}}
            <div class="flex flex-col gap-4">
                <div class="bg-white rounded-2xl border border-outline-variant p-5">
                    @if($store)
                    <a href="{{ route('store.public', $store->slug) }}"
                       class="inline-flex items-center gap-1.5 bg-surface-container-low border border-outline-variant px-3 py-1.5 rounded-full text-xs font-bold text-primary hover:bg-surface-container transition-colors mb-3">
                        <span class="material-symbols-outlined" style="font-size:13px">storefront</span>
                        {{ $store->name }}
                    </a>
                    @endif

                    <h1 class="text-xl md:text-2xl font-black text-on-surface leading-tight mb-2">{{ $product->name }}</h1>

                    @if($product->category)
                    <span class="inline-block bg-surface-container text-on-surface-variant text-xs font-bold px-3 py-1 rounded-full mb-3">{{ $product->category }}</span>
                    @endif

                    @if($store && ($store->city || $store->district))
                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mb-3">
                        <span class="material-symbols-outlined" style="font-size:14px">location_on</span>
                        {{ implode(', ', array_filter([$store->city, $store->district ? 'Kec. '.$store->district : null])) }}
                    </div>
                    @endif

                    @if($product->description)
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-4">{{ $product->description }}</p>
                    @endif

                    <div class="space-y-2.5">
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Hubungi & Tanyakan</p>
                        @if($store && !empty($store->social_links['whatsapp']))
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $store->social_links['whatsapp']) }}"
                           target="_blank"
                           onclick="trackClick('{{ $product->id }}','WhatsApp')"
                           class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-[#25d366] text-white font-bold text-sm hover:opacity-90 transition-opacity">
                            <span class="material-symbols-outlined" style="font-size:18px">chat</span>
                            <span class="flex-1">Tanya via WhatsApp</span>
                            <span class="material-symbols-outlined opacity-60" style="font-size:16px">open_in_new</span>
                        </a>
                        @endif
                        @if($store && !empty($store->social_links['instagram']))
                        <a href="{{ $store->social_links['instagram'] }}" target="_blank"
                           class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-white font-bold text-sm hover:opacity-90 transition-opacity"
                           style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)">
                            <span class="material-symbols-outlined" style="font-size:18px">photo_camera</span>
                            <span class="flex-1">Lihat di Instagram</span>
                            <span class="material-symbols-outlined opacity-60" style="font-size:16px">open_in_new</span>
                        </a>
                        @endif
                        @if(!empty($product->links))
                        @foreach($product->links as $link)
                        @if(!empty($link['url']) && !empty($link['platform']))
                        <a href="{{ $link['url'] }}" target="_blank"
                           onclick="trackClick('{{ $product->id }}','{{ $link['platform'] }}')"
                           class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-primary-container text-white font-bold text-sm hover:opacity-90 transition-opacity">
                            <span class="material-symbols-outlined" style="font-size:18px">shopping_bag</span>
                            <span class="flex-1">Lihat di {{ $link['platform'] }}</span>
                            <span class="material-symbols-outlined opacity-60" style="font-size:16px">open_in_new</span>
                        </a>
                        @endif
                        @endforeach
                        @endif

                    </div>
                </div>

                @if($store)
                <div class="bg-white rounded-2xl border border-outline-variant p-4">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-white" style="font-size:20px">storefront</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-sm text-on-surface">{{ $store->name }}</h3>
                            <p class="text-xs text-on-surface-variant line-clamp-2 mt-0.5">{{ $store->description }}</p>
                            <div class="flex items-center gap-1 text-green-700 text-xs font-bold mt-1">
                                <span class="material-symbols-outlined" style="font-size:12px">verified</span> Mitra Terverifikasi
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('store.public', $store->slug) }}"
                       class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-xl border-2 border-primary text-primary text-xs font-bold hover:bg-primary hover:text-white transition-all">
                        <span class="material-symbols-outlined" style="font-size:15px">storefront</span>
                        Kunjungi Toko Lengkap
                        <span class="material-symbols-outlined" style="font-size:15px">chevron_right</span>
                    </a>
                </div>
                @endif
            </div>
        </div>



        {{-- ═══════════ RELATED PRODUCTS ═══════════ --}}
        @if($related->isNotEmpty())
        <div>
            <h2 class="font-bold text-sm text-on-surface mb-3">Produk Lain dari Toko Ini</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($related as $rel)
                <a href="{{ route('catalog.product', $rel->id) }}"
                   class="bg-white rounded-xl border border-outline-variant overflow-hidden hover:shadow-sm hover:-translate-y-0.5 transition-all">
                    @if(!empty($rel->images))
                    <img src="{{ asset('storage/products/'.$rel->images[0]) }}" alt="{{ $rel->name }}"
                         class="w-full aspect-square object-cover" loading="lazy" />
                    @else
                    <div class="aspect-square bg-surface-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-outline-variant">image</span>
                    </div>
                    @endif
                    <div class="p-2.5">
                        <p class="text-xs font-bold text-on-surface truncate">{{ $rel->name }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $rel->category }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <footer class="bg-primary text-blue-200 text-center py-5 px-4 text-xs mt-6">
        <strong class="text-white">Portal UMKM Dinas Perdagangan</strong>
        <span class="mx-2">·</span>© {{ date('Y') }}
    </footer>

    <script>
    function trackClick(productId, platform) {
        fetch(`/track-click/${productId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ platform })
        }).catch(() => {});
    }
    </script>
