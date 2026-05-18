<x-storefront-layout>
    <x-slot name="title">{{ $product->name }} - {{ $store->name }}</x-slot>
    <x-slot name="meta">
        <meta property="og:title" content="{{ $product->name }} - {{ $store->name }}" />
        <meta property="og:description" content="{{ Str::limit($product->description, 150) }}" />
        <meta property="og:type" content="product" />
        @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
        <meta property="og:image" content="{{ asset('storage/products/' . $product->images[0]) }}" />
        @endif
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
            .swiper-button-next, .swiper-button-prev { color: black !important; }
            .swiper-pagination-bullet-active { background: black !important; }
        </style>
    @endpush

    <div class="bg-white min-h-screen">
        <nav class="border-b border-gray-100 py-4 px-6 max-w-7xl mx-auto">
            <a href="{{ route('store.public', $store->slug) }}" class="text-sm font-bold uppercase tracking-widest text-gray-500 hover:text-black transition">
                &larr; Kembali ke {{ $store->name }}
            </a>
        </nav>

        <main class="max-w-7xl mx-auto px-6 py-12">
            <div class="flex flex-col md:flex-row gap-12">
                
                <!-- Swiper Gallery -->
                <div class="w-full md:w-1/2">
                    <div class="swiper mySwiper aspect-[4/5] bg-gray-200 animate-pulse rounded-md">
                        <div class="swiper-wrapper">
                            @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                                @foreach($product->images as $img)
                                    <div class="swiper-slide h-full w-full bg-gray-200 flex items-center justify-center">
                                        <img src="{{ asset('storage/products/' . $img) }}" 
                                             onload="document.querySelector('.mySwiper').classList.remove('animate-pulse'); this.classList.remove('opacity-0');"
                                             class="w-full h-full object-cover opacity-0 transition-opacity duration-700">
                                    </div>
                                @endforeach
                            @else
                                <div class="swiper-slide flex items-center justify-center text-gray-400 font-bold uppercase tracking-widest" onload="document.querySelector('.mySwiper').classList.remove('animate-pulse');">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination"></div>
                        <!-- Add Navigation -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="w-full md:w-1/2 flex flex-col justify-center pb-24 md:pb-0">
                    <div class="mb-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-2 py-1">{{ $product->category }}</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter leading-tight mb-4">
                        {{ $product->name }}
                    </h1>
                    <p class="text-3xl font-bold text-gray-900 mb-8">
                        Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                    </p>
                    
                    <div class="prose max-w-none text-gray-600 mb-10 leading-relaxed text-lg">
                        <p>{{ $product->description }}</p>
                    </div>

                    <!-- Sticky CTA Area (Sticky on mobile) -->
                    <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 md:static md:p-0 md:bg-transparent md:border-none z-50 flex flex-col sm:flex-row gap-4">
                        @if(isset($product->links) && is_array($product->links))
                            @foreach($product->links as $link)
                                @if(!empty($link['url']) && !empty($link['platform']))
                                    <a href="{{ $link['url'] }}" target="_blank" onclick="trackClick('{{ $link['platform'] }}')" class="flex-1 text-center py-4 px-8 border-2 border-black font-black uppercase tracking-widest text-sm hover:bg-black hover:text-white transition-colors shadow-sm">
                                        Beli di {{ $link['platform'] }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Recommendations -->
            @if($recommendations->count() > 0)
                <div class="mt-32">
                    <h2 class="text-2xl font-black uppercase tracking-widest mb-10 border-b-2 border-black pb-4 inline-block">Koleksi Lainnya</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($recommendations as $rec)
                            <div class="group cursor-pointer" onclick="window.location='{{ route('store.product', ['slug' => $store->slug, 'id' => $rec->id]) }}'">
                                <div class="aspect-[4/5] bg-gray-200 animate-pulse overflow-hidden mb-4 relative">
                                    @if(isset($rec->images) && is_array($rec->images) && count($rec->images) > 0)
                                        <img src="{{ asset('storage/products/' . $rec->images[0]) }}" 
                                             onload="this.parentElement.classList.remove('animate-pulse'); this.classList.remove('opacity-0');"
                                             class="w-full h-full object-cover opacity-0 group-hover:scale-105 transition-all duration-700">
                                    @endif
                                </div>
                                <h3 class="font-bold text-sm uppercase tracking-tight">{{ $rec->name }}</h3>
                                <p class="text-gray-500 text-xs italic">{{ $rec->category }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            var swiper = new Swiper(".mySwiper", {
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });

            function trackClick(platform) {
                fetch('{{ route('store.track-click', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ platform: platform })
                }).catch(err => console.error('Error tracking click:', err));
            }
        </script>
    @endpush
</x-storefront-layout>
