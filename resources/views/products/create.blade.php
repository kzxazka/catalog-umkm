<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Nama Produk -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Produk</label>
                        <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white">
                    </div>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                        <input type="text" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white">
                    </div>

                    <!-- Harga -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga</label>
                        <input type="number" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white">
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white"></textarea>
                    </div>

                    <!-- Upload Foto -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Produk (Bisa lebih dari 1)</label>
                        <input type="file" name="photos[]" multiple accept="image/*" class="mt-1 block w-full dark:text-gray-300">
                        <p class="text-xs text-gray-500 mt-1">Otomatis di-resize dan dikompresi menjadi WebP agar super ringan.</p>
                    </div>

                    <!-- Manajemen Link Dinamis -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tautan Eksternal (Shopee, Tokopedia, WA)</label>
                        <div id="links-container">
                            <div class="flex gap-2 mb-2 link-row">
                                <input type="text" name="links[0][platform]" placeholder="Platform (Msl: Shopee)" class="block w-1/3 rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white">
                                <input type="url" name="links[0][url]" placeholder="URL" class="block w-2/3 rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white">
                                <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md remove-link">X</button>
                            </div>
                        </div>
                        <button type="button" id="add-link" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold transition hover:bg-blue-700">+ Tambah Link</button>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 transition text-white rounded-md font-semibold">Simpan Produk</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        let linkIndex = 1;
        document.getElementById('add-link').addEventListener('click', function() {
            const container = document.getElementById('links-container');
            const html = `
                <div class="flex gap-2 mb-2 link-row">
                    <input type="text" name="links[${linkIndex}][platform]" placeholder="Platform" class="block w-1/3 rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white">
                    <input type="url" name="links[${linkIndex}][url]" placeholder="URL" class="block w-2/3 rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-white">
                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md remove-link">X</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            linkIndex++;
        });

        document.getElementById('links-container').addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-link')) {
                e.target.closest('.link-row').remove();
            }
        });
    </script>
</x-app-layout>
