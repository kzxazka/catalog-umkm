<x-storefront-layout>
    <x-slot name="title">{{ $store->name }} - Koleksi Produk UMKM Autentik</x-slot>
    <x-slot name="meta">
        <meta property="og:title" content="{{ $store->name }} - Official Store" />
        <meta property="og:description" content="{{ Str::limit($store->description, 150) }}" />
        <meta property="og:type" content="website" />
    </x-slot>

    <div class="bg-white min-h-screen">
        <header class="pt-20 pb-10 px-6 max-w-7xl mx-auto">
            <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-6">
                {{ $store->name }}.
            </h1>
            <div class="max-w-3xl">
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed italic border-l-4 border-black pl-6">
                    {{ $store->description }}
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <span class="bg-black text-white px-4 py-1 text-xs font-bold uppercase tracking-widest">Official Store</span>
                    <span class="border border-black px-4 py-1 text-xs font-bold uppercase tracking-widest">UMKM Verified</span>
                    @if(isset($store->social_links['instagram']))
                        <a href="{{ $store->social_links['instagram'] }}" target="_blank" class="border border-black px-4 py-1 text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition">Instagram</a>
                    @endif
                    @if(isset($store->social_links['whatsapp']))
                        <a href="https://wa.me/{{ $store->social_links['whatsapp'] }}" target="_blank" class="border border-black px-4 py-1 text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition">WhatsApp</a>
                    @endif
                </div>
            </div>
        </header>

        <nav class="sticky top-0 bg-white/95 backdrop-blur-md z-50 border-y border-gray-100 mb-10">
            <div class="max-w-7xl mx-auto px-6 py-4 flex gap-8 overflow-x-auto no-scrollbar">
                <a href="?category=Semua" class="font-bold uppercase text-sm whitespace-nowrap {{ request('category') == 'Semua' || !request('category') ? 'border-b-2 border-black pb-1' : 'text-gray-400 hover:text-black transition' }}">Semua</a>
                @foreach($categories as $cat)
                    <a href="?category={{ urlencode($cat) }}" class="font-bold uppercase text-sm whitespace-nowrap {{ request('category') == $cat ? 'border-b-2 border-black pb-1' : 'text-gray-400 hover:text-black transition' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 pb-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                @forelse($products as $product)
                    <div class="group cursor-pointer" onclick="window.location='{{ route('store.product', ['slug' => $store->slug, 'id' => $product->id]) }}'">
                        <div class="aspect-[4/5] bg-gray-200 animate-pulse overflow-hidden mb-4 relative">
                            @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                                <img src="{{ asset('storage/products/' . $product->images[0]) }}" 
                                     alt="{{ $product->name }}" 
                                     onload="this.parentElement.classList.remove('animate-pulse'); this.classList.remove('opacity-0');"
                                     class="w-full h-full object-cover opacity-0 group-hover:scale-105 transition-all duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold uppercase tracking-widest text-sm" onload="this.parentElement.classList.remove('animate-pulse');">No Image</div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest shadow-sm">
                                Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg uppercase tracking-tight">{{ $product->name }}</h3>
                                <p class="text-gray-500 text-sm italic mt-1">{{ $product->category }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-2">
                            @if(isset($product->links) && is_array($product->links))
                                @foreach($product->links as $link)
                                    @if(!empty($link['url']) && !empty($link['platform']))
                                        <!-- Prevent default agar event klik card (pindah ke detail) tidak tereksekusi saat klik link CTA -->
                                        <a href="{{ $link['url'] }}" target="_blank" onclick="event.stopPropagation();" class="text-[10px] font-black uppercase tracking-widest px-4 py-2 border-2 border-black hover:bg-black hover:text-white transition-colors">
                                            Beli di {{ $link['platform'] }}
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <h2 class="text-3xl font-black uppercase tracking-widest text-gray-300">Tidak ada produk ditemukan.</h2>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</x-storefront-layout>
