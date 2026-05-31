<x-buyer-layout title="Profil Saya — Portal UMKM">
<div class="max-w-2xl mx-auto px-4 py-6 pb-24">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('catalog.index') }}"
           class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size:20px">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-primary leading-tight">Profil Saya</h1>
            <p class="text-xs text-on-surface-variant">Kelola informasi akun dan keamanan</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    {{-- MITRA STATUS BANNER --}}
    @php $mitraApp = auth()->user()->mitraApplications()->orderBy('_id', -1)->first(); @endphp
    @if($mitraApp)
        @if($mitraApp->status === 'pending')
        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-600 mt-0.5">pending</span>
            <div>
                <p class="text-sm font-bold text-amber-800">Pengajuan Mitra Sedang Diproses</p>
                <p class="text-xs text-amber-700 mt-1">Tim Dinas Perdagangan sedang meninjau pengajuan Anda. Estimasi 3-5 hari kerja.</p>
                <a href="{{ route('mitra.status') }}" class="text-xs font-bold text-amber-800 underline mt-1 inline-block">Lihat Status →</a>
            </div>
        </div>
        @elseif($mitraApp->status === 'approved')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-green-600 mt-0.5">verified</span>
            <div>
                <p class="text-sm font-bold text-green-800">Anda adalah Mitra Terverifikasi 🎉</p>
                <p class="text-xs text-green-700 mt-1">Pengajuan kemitraan untuk <strong>{{ $mitraApp->business_name }}</strong> telah disetujui.</p>
            </div>
        </div>
        @endif
    @else
    <div class="mb-4 p-4 bg-surface-container-low border border-outline-variant rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined text-primary" style="font-size:20px">store</span>
        <div class="flex-1">
            <p class="text-sm font-bold text-on-surface">Ingin buka toko sendiri?</p>
            <p class="text-xs text-on-surface-variant">Daftar jadi mitra UMKM Dinas Perdagangan</p>
        </div>
        <a href="{{ route('mitra.register') }}"
           class="text-xs font-bold text-white bg-secondary px-3 py-1.5 rounded-lg whitespace-nowrap hover:opacity-90 transition-opacity">
            Daftar Mitra
        </a>
    </div>
    @endif

    {{-- PERTANYAAN SAYA CARD --}}
    <a href="{{ route('buyer.inquiries') }}"
       class="mb-4 p-4 bg-white border border-outline-variant rounded-2xl flex items-center justify-between hover:border-primary hover:shadow-sm transition-all group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined" style="font-size:22px">help_center</span>
            </div>
            <div>
                <p class="text-sm font-bold text-on-surface">Pertanyaan Saya</p>
                <p class="text-xs text-on-surface-variant">Lihat jawaban dan status pertanyaan produk Anda</p>
            </div>
        </div>
        <span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" style="font-size:20px">chevron_right</span>
    </a>

    {{-- AVATAR & NAMA --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-6 mb-4">
        <form method="POST" action="{{ route('buyer.profile.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- Avatar --}}
            <div class="flex flex-col items-center mb-6">
                <div class="relative group">
                    @if($user->avatar_path)
                        <img src="{{ asset('storage/'.$user->avatar_path) }}"
                             alt="Foto Profil"
                             class="w-24 h-24 rounded-full object-cover border-4 border-surface-container" />
                    @else
                        <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center border-4 border-surface-container">
                            <span class="text-3xl font-bold text-white">{{ $user->initials }}</span>
                        </div>
                    @endif
                    <label for="avatar"
                           class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <span class="material-symbols-outlined text-white" style="font-size:20px">photo_camera</span>
                    </label>
                    <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*"
                           onchange="previewAvatar(this)" />
                </div>
                <p class="text-xs text-on-surface-variant mt-2">Tap foto untuk ganti</p>
            </div>

            {{-- Fields --}}
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Nama Panggilan</label>
                        <input type="text" name="nickname" value="{{ old('nickname', $user->nickname) }}"
                               placeholder="Contoh: Budi"
                               class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1.5">Email</label>
                    <input type="email" value="{{ $user->email }}" readonly
                           class="w-full px-3 py-2.5 rounded-lg border border-outline-variant bg-surface-container-low text-sm text-on-surface-variant cursor-not-allowed" />
                    <p class="text-xs text-on-surface-variant mt-1">Email tidak dapat diubah</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1.5">
                        Nomor Telepon
                        <span class="ml-1 inline-flex items-center gap-0.5 text-on-surface-variant font-normal">
                            <span class="material-symbols-outlined" style="font-size:12px">lock</span>
                            <span style="font-size:10px">Terenkripsi</span>
                        </span>
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+62 812 3456 7890"
                           class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1.5">
                        Alamat Lengkap
                        <span class="ml-1 inline-flex items-center gap-0.5 text-on-surface-variant font-normal">
                            <span class="material-symbols-outlined" style="font-size:12px">lock</span>
                            <span style="font-size:10px">Terenkripsi</span>
                        </span>
                    </label>
                    <textarea name="address" rows="2"
                              placeholder="Jl. Merdeka No. 10, RT 02/RW 05"
                              class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all resize-none">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Kota/Kabupaten <span class="material-symbols-outlined text-on-surface-variant" style="font-size:12px">lock</span></label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                               placeholder="Contoh: Surabaya"
                               class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1.5">Kecamatan <span class="material-symbols-outlined text-on-surface-variant" style="font-size:12px">lock</span></label>
                        <input type="text" name="district" value="{{ old('district', $user->district) }}"
                               placeholder="Contoh: Wonokromo"
                               class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm transition-all" />
                    </div>
                </div>

                {{-- Encryption notice --}}
                <div class="flex items-start gap-2 p-3 bg-surface-container-low rounded-lg">
                    <span class="material-symbols-outlined text-primary mt-0.5" style="font-size:16px">security</span>
                    <p class="text-xs text-on-surface-variant">
                        Data nomor telepon, alamat, kota, dan kecamatan Anda <strong class="text-on-surface">dienkripsi secara otomatis</strong> sebelum disimpan ke database untuk menjaga privasi Anda.
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-primary text-white py-3 rounded-xl font-bold text-sm hover:opacity-90 active:scale-95 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- UBAH PASSWORD --}}
    <div class="bg-white border border-outline-variant rounded-2xl p-6">
        <h2 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size:18px">lock</span>
            Keamanan Akun
        </h2>
        <form method="POST" action="{{ route('buyer.profile.password') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface mb-1.5">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
                <x-input-error :messages="$errors->get('current_password')" class="mt-1 text-xs" />
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface mb-1.5">Kata Sandi Baru</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-3 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none text-sm" />
            </div>
            <button type="submit"
                    class="w-full border-2 border-primary text-primary py-3 rounded-xl font-bold text-sm hover:bg-primary hover:text-white transition-all active:scale-95">
                Perbarui Kata Sandi
            </button>
        </form>
    </div>

</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const avatarEl = document.querySelector('.w-24.h-24');
            if (avatarEl.tagName === 'IMG') {
                avatarEl.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-24 h-24 rounded-full object-cover border-4 border-surface-container';
                avatarEl.replaceWith(img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</x-buyer-layout>
