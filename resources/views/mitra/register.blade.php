<x-buyer-layout title="Daftar Mitra UMKM — Portal UMKM">
<div class="max-w-2xl mx-auto px-4 py-6 pb-24">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('catalog.index') }}"
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
                ['1', 'Isi formulir pendaftaran dengan informasi usaha Anda di Bandar Lampung', 'edit_document'],
                ['2', 'Tim Dinas Perdagangan akan meninjau lokasi & profil usaha Anda', 'manage_search'],
                ['3', 'Setelah disetujui, akun toko mandiri Anda akan dibuat otomatis', 'storefront'],
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

    {{-- INFO BANNER --}}
    <div class="mb-5 flex items-start gap-3 bg-primary-container/20 border border-primary/20 rounded-2xl p-4">
        <span class="material-symbols-outlined text-primary text-2xl shrink-0">info</span>
        <div>
            <h4 class="text-sm font-bold text-primary mb-1">Persyaratan Mitra UMKM</h4>
            <p class="text-xs text-on-surface-variant leading-relaxed">
                Berdomisili di Kota Bandar Lampung
            </p>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-6">
        <h2 class="text-base font-bold text-on-surface mb-5">Formulir Pendaftaran Mitra</h2>

        <form method="POST" action="{{ route('mitra.store') }}" class="space-y-5">
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
                            @foreach(['Fashion','Food & Beverage','Healthy Product','Other'] as $cat)
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
                            <input type="text" name="city" value="Bandar Lampung" readonly required
                                   class="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-high text-on-surface-variant cursor-not-allowed outline-none text-sm font-bold" />
                            <x-input-error :messages="$errors->get('city')" class="mt-1 text-xs" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1.5">Kecamatan <span class="text-error">*</span></label>
                            <input type="text" name="district" value="{{ old('district') }}" required
                                   placeholder="Contoh: Kedaton"
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
