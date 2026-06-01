<x-app-layout>
    <div class="min-h-screen max-w-3xl mx-auto">
        <!-- Back Navigation & Title -->
        <div class="mb-8">
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-widest text-on-surface-variant hover:text-primary transition mb-3">
                <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Kembali ke Daftar Event
            </a>
            <h1 class="font-headline-lg text-3xl font-bold text-primary">Tambah Event Baru</h1>
            <p class="font-body-md text-on-surface-variant mt-1">Publikasikan pameran UMKM lokal, bimbingan teknis, atau bazar produk terverifikasi.</p>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-50 text-red-800 border border-red-200 rounded-xl mb-6 shadow-sm">
                <p class="font-bold text-sm mb-1">Terjadi kesalahan pengisian form:</p>
                <ul class="list-disc pl-5 text-xs space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white border border-outline-variant rounded-2xl shadow-sm overflow-hidden p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Judul Event -->
                <div>
                    <label for="title" class="block text-xs font-black uppercase tracking-wider text-on-surface-variant mb-2">Judul Event <span class="text-error">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Lampung Clothing & Craft Expo 2026"
                           class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all">
                </div>

                <!-- Deskripsi Event -->
                <div>
                    <label for="description" class="block text-xs font-black uppercase tracking-wider text-on-surface-variant mb-2">Keterangan / Deskripsi Event <span class="text-error">*</span></label>
                    <textarea id="description" name="description" rows="6" required placeholder="Tuliskan detail jadwal, pemateri, target pengunjung, dan benefit event secara lengkap..."
                              class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm resize-none transition-all font-sans">{{ old('description') }}</textarea>
                </div>

                <!-- Info Pelaksanaan (Tanggal & Lokasi) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="event_date" class="block text-xs font-black uppercase tracking-wider text-on-surface-variant mb-2">Tanggal Pelaksanaan <span class="text-error">*</span></label>
                        <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" required
                               class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all">
                    </div>
                    <div>
                        <label for="location" class="block text-xs font-black uppercase tracking-wider text-on-surface-variant mb-2">Lokasi Event <span class="text-error">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="Contoh: Hall A PKOR Way Halim"
                               class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all">
                    </div>
                </div>

                <!-- Upload Banner Event -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-on-surface-variant mb-2">Banner / Foto Event <span class="text-error">*</span></label>
                    
                    <div x-data="{ fileName: '', previewUrl: '' }" class="relative border-2 border-dashed border-outline-variant hover:border-primary rounded-2xl p-6 transition-colors flex flex-col items-center justify-center text-center bg-surface-container-low/40">
                        <input type="file" name="image" id="image" required accept="image/*"
                               @change="
                                   const file = $event.target.files[0];
                                   if (file) {
                                       fileName = file.name;
                                       previewUrl = URL.createObjectURL(file);
                                   }
                               "
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <template x-if="previewUrl">
                            <div class="space-y-3 z-20">
                                <img :src="previewUrl" class="w-full max-h-48 object-cover rounded-xl border border-outline-variant/60 shadow-sm">
                                <p class="text-xs font-bold text-primary truncate max-w-xs" x-text="fileName"></p>
                                <span class="text-[10px] text-on-surface-variant bg-surface-container px-2 py-0.5 rounded">Ketuk kembali untuk mengganti</span>
                            </div>
                        </template>

                        <template x-if="!previewUrl">
                            <div class="py-4">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant/60 mb-2">add_photo_alternate</span>
                                <p class="text-xs font-bold text-on-surface mb-0.5">Unggah Foto / Banner Event</p>
                                <p class="text-[10px] text-on-surface-variant/80">Rasio 16:9 disukai, format PNG/JPG maks 3MB</p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label for="status" class="block text-xs font-black uppercase tracking-wider text-on-surface-variant mb-2">Status Publikasi</label>
                    <select id="status" name="status" class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all cursor-pointer">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif (Tampilkan Langsung di Halaman Depan)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Simpan, Sembunyikan Dulu)</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-outline-variant/60 flex justify-end gap-3">
                    <a href="{{ route('admin.events.index') }}" class="px-5 py-3 rounded-xl border border-outline-variant text-on-surface-variant text-xs font-bold hover:bg-surface-container-low transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                        Publikasikan Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
