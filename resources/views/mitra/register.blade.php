<x-buyer-layout title="Daftar Mitra UMKM — Portal UMKM">
<div class="max-w-2xl mx-auto px-4 py-6 pb-24">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('buyer.profile') }}"
           class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-content hover:bg-surface-container-high transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-primary leading-tight">Daftar Jadi Mitra UMKM</h1>
            <p class="text-xs text-on-surface-variant">Bergabung dengan jaringan UMKM terverifikasi</p>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-red-600">error</span>
        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Jika sudah ada pengajuan aktif --}}
    @if($existing && $existing->status === 'pending')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
        <span class="material-symbols-outlined text-amber-500 text-5xl mb-3 block">hourglass_top</span>
        <h2 class="text-lg font-bold text-amber-800 mb-2">Pengajuan Sedang Diproses</h2>
        <p class="text-sm text-amber-700 mb-4">Pengajuan atas nama <strong>{{ $existing->business_name }}</strong> sedang ditinjau oleh Tim Dinas Perdagangan. Harap menunggu 3–5 hari kerja.</p>
        <a href="{{ route('mitra.status') }}"
           class="inline-flex items-center gap-2 bg-amber-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined" style="font-size:16px">info</span>
            Pantau Status
        </a>
    </div>

    @elseif($existing && $existing->status === 'approved')
    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
        <span class="material-symbols-outlined text-green-500 text-5xl mb-3 block">verified</span>
        <h2 class="text-lg font-bold text-green-800 mb-2">Anda Sudah Menjadi Mitra!</h2>
        <p class="text-sm text-green-700">Pengajuan untuk <strong>{{ $existing->business_name }}</strong> telah disetujui. Akun toko Anda sedang dipersiapkan oleh admin.</p>
    </div>

    @else
    {{-- HOW IT WORKS --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-5 mb-5">
        <h2 class="text-sm font-bold text-primary mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size:18px">info</span>
            Cara Kerja Program Kemitraan
        </h2>
        <div class="space-y-3">
            @foreach([
                ['1', 'Isi formulir pendaftaran dengan data usaha dan dokumen lengkap', 'edit_document'],
                ['2', 'Tim Dinas Perdagangan meninjau dalam 3–5 hari kerja', 'manage_search'],
                ['3', 'Jika disetujui, akun toko akan dibuat dan Anda bisa mengelola katalog', 'storefront'],
            ] as [$no, $text, $icon])
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center shrink-0">
                    <span class="text-white text-xs font-bold">{{ $no }}</span>
                </div>
                <p class="text-sm text-on-surface-variant pt-0.5">{{ $text }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-6">
        <h2 class="text-base font-bold text-on-surface mb-5">Formulir Pendaftaran Mitra</h2>

        <form method="POST" action="{{ route('mitra.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Data Usaha --}}
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-3">Informasi Usaha</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Nama Usaha <span class="text-error">*</span></label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" required
                               placeholder="Contoh: Batik Nusantara Sejati"
                               class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
                        <x-input-error :messages="$errors->get('business_name')" class="mt-1 text-xs" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Kategori Usaha <span class="text-error">*</span></label>
                        <select name="business_category" required
                                class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(['Fashion & Pakaian','Kuliner & Makanan','Kerajinan & Handmade','Aksesoris & Perhiasan','Kecantikan & Kosmetik','Pertanian & Herbal','Elektronik & Gadget','Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('business_category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('business_category')" class="mt-1 text-xs" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Deskripsi Singkat Usaha <span class="text-error">*</span></label>
                        <textarea name="description" rows="3" required
                                  placeholder="Ceritakan tentang usaha Anda, produk unggulan, dan keunikannya..."
                                  class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm resize-none">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-xs" />
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-3">Lokasi Usaha</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Alamat Lengkap Usaha <span class="text-error">*</span></label>
                        <textarea name="business_address" rows="2" required
                                  placeholder="Jl. Pahlawan No. 5, Kelurahan Sukamaju"
                                  class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm resize-none">{{ old('business_address') }}</textarea>
                        <x-input-error :messages="$errors->get('business_address')" class="mt-1 text-xs" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1.5">Kota/Kabupaten <span class="text-error">*</span></label>
                            <input type="text" name="city" value="{{ old('city') }}" required
                                   placeholder="Contoh: Surabaya"
                                   class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
                            <x-input-error :messages="$errors->get('city')" class="mt-1 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1.5">Kecamatan <span class="text-error">*</span></label>
                            <input type="text" name="district" value="{{ old('district') }}" required
                                   placeholder="Contoh: Wonokromo"
                                   class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
                            <x-input-error :messages="$errors->get('district')" class="mt-1 text-xs" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kontak --}}
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-3">Kontak Usaha</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">WhatsApp Usaha <span class="text-error">*</span></label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" required
                               placeholder="628123456789 (tanpa +)"
                               class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
                        <x-input-error :messages="$errors->get('whatsapp')" class="mt-1 text-xs" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Instagram (opsional)</label>
                        <input type="text" name="instagram" value="{{ old('instagram') }}"
                               placeholder="https://instagram.com/nama_usaha"
                               class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
                    </div>
                </div>
            </div>

            {{-- Dokumen --}}
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-3">Dokumen Persyaratan</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Foto KTP Pemilik <span class="text-error">*</span></label>
                        <label class="flex items-center gap-3 p-3 border-2 border-dashed border-outline-variant rounded-lg cursor-pointer hover:border-primary transition-colors">
                            <span class="material-symbols-outlined text-primary">upload_file</span>
                            <div>
                                <p class="text-sm font-semibold text-on-surface" id="ktp-label">Pilih file KTP</p>
                                <p class="text-xs text-on-surface-variant">JPG, PNG, atau PDF — Maks. 5MB</p>
                            </div>
                            <input type="file" name="ktp_file" required accept=".jpg,.jpeg,.png,.pdf"
                                   class="hidden" onchange="updateLabel(this, 'ktp-label')" />
                        </label>
                        <x-input-error :messages="$errors->get('ktp_file')" class="mt-1 text-xs" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">NIB / Sertifikat Usaha <span class="text-error">*</span></label>
                        <label class="flex items-center gap-3 p-3 border-2 border-dashed border-outline-variant rounded-lg cursor-pointer hover:border-primary transition-colors">
                            <span class="material-symbols-outlined text-primary">upload_file</span>
                            <div>
                                <p class="text-sm font-semibold text-on-surface" id="nib-label">Pilih file NIB</p>
                                <p class="text-xs text-on-surface-variant">JPG, PNG, atau PDF — Maks. 5MB</p>
                            </div>
                            <input type="file" name="nib_file" required accept=".jpg,.jpeg,.png,.pdf"
                                   class="hidden" onchange="updateLabel(this, 'nib-label')" />
                        </label>
                        <x-input-error :messages="$errors->get('nib_file')" class="mt-1 text-xs" />
                    </div>
                </div>
                <div class="flex items-start gap-2 p-3 bg-surface-container-low rounded-lg mt-3">
                    <span class="material-symbols-outlined text-primary mt-0.5" style="font-size:14px">security</span>
                    <p class="text-xs text-on-surface-variant">Dokumen Anda disimpan secara <strong>rahasia</strong> di server aman dan hanya dapat diakses oleh petugas Dinas Perdagangan yang berwenang.</p>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-secondary text-white py-4 rounded-xl font-bold text-sm hover:opacity-90 active:scale-95 transition-all shadow-md">
                Kirim Pengajuan Kemitraan
            </button>
        </form>
    </div>
    @endif

</div>

<script>
function updateLabel(input, labelId) {
    const label = document.getElementById(labelId);
    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        label.classList.add('text-primary');
    }
}
</script>
</x-buyer-layout>
