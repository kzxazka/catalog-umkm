<x-app-layout>
<div class="max-w-3xl mx-auto px-4 py-6 pb-24">
    <div class="flex items-center gap-3 mb-6">
        <div>
            <h1 class="text-xl font-bold text-primary">Pertanyaan Produk</h1>
            <p class="text-xs text-on-surface-variant">Pertanyaan masuk dari buyer tentang produk Anda</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    @if($inquiries->isEmpty())
    <div class="bg-white border border-outline-variant rounded-2xl p-12 text-center">
        <span class="material-symbols-outlined text-outline-variant text-5xl mb-4 block">help</span>
        <h3 class="text-lg font-bold text-on-surface mb-2">Belum Ada Pertanyaan</h3>
        <p class="text-sm text-on-surface-variant">Pertanyaan dari buyer tentang produk Anda akan muncul di sini.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($inquiries as $inquiry)
        @php $product = $inquiry->product; $buyer = $inquiry->buyer; @endphp
        <div class="bg-white border border-outline-variant rounded-2xl overflow-hidden">
            {{-- Header --}}
            <div class="px-5 py-4 border-b border-outline-variant flex items-center gap-3">
                @if($product)
                <a href="{{ route('catalog.product', $product->id) }}"
                   class="flex items-center gap-3 flex-1 min-w-0 hover:opacity-80 transition-opacity">
                    @if(!empty($product->images))
                    <img src="{{ asset('storage/products/'.$product->images[0]) }}"
                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0"
                         alt="{{ $product->name }}" />
                    @else
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px">image</span>
                    </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-primary truncate">{{ $product->name }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $product->category }}</p>
                    </div>
                </a>
                @endif
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($inquiry->reply)
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Terjawab</span>
                    @else
                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">Belum dijawab</span>
                    @endif
                </div>
            </div>

            <div class="px-5 py-4">
                {{-- Pertanyaan --}}
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center font-bold text-primary text-xs flex-shrink-0 mt-0.5">
                        {{ $buyer?->initials ?? '?' }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs font-bold text-on-surface">{{ $buyer?->name ?? 'Buyer' }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $inquiry->created_at?->diffForHumans() }}</p>
                        </div>
                        <p class="text-sm text-on-surface bg-surface-container-low rounded-xl p-3">{{ $inquiry->question }}</p>
                    </div>
                </div>

                {{-- Balasan yang sudah ada --}}
                @if($inquiry->reply)
                <div class="flex items-start gap-3 mb-4 pl-11">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-white" style="font-size:16px">storefront</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs font-bold text-primary">{{ $store->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $inquiry->replied_at?->diffForHumans() }}</p>
                        </div>
                        <p class="text-sm text-on-surface bg-surface-container-low rounded-xl p-3">{{ $inquiry->reply }}</p>
                    </div>
                </div>
                @endif

                {{-- Form Balas --}}
                @if(!$inquiry->reply)
                <form method="POST" action="{{ route('owner.inquiry.reply', $inquiry->id) }}" class="pl-11">
                    @csrf
                    <textarea name="reply" rows="3" required
                              placeholder="Tulis balasan Anda..."
                              class="w-full px-3 py-2.5 border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm resize-none transition-all font-sans mb-2"></textarea>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_public" value="1"
                                   class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary" />
                            <span class="text-xs text-on-surface-variant">Tampilkan sebagai Q&A publik di halaman produk</span>
                        </label>
                        <button type="submit"
                                class="ml-auto bg-primary text-white px-4 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity flex-shrink-0">
                            Kirim Balasan
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $inquiries->links() }}</div>
    @endif
</div>
</x-app-layout>
