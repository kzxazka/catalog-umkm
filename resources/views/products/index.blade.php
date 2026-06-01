<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Produk Toko Anda') }}
            </h2>
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700 transition">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($products as $product)
                        <div class="border dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                            @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                                <img src="{{ asset('storage/products/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-800 flex items-center justify-center text-gray-500 text-sm">Tanpa Gambar</div>
                            @endif
                            
                            <div class="p-4">
                                <h3 class="font-bold text-lg dark:text-white truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $product->category ?? 'Tanpa Kategori' }}</p>

                                
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="flex-1 text-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-300 px-3 py-1.5 rounded text-sm font-medium transition">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 px-3 py-1.5 rounded text-sm font-medium transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada produk</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulailah dengan menambahkan produk pertama untuk toko Anda.</p>
                            <a href="{{ route('products.create') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                + Tambah Produk
                            </a>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
