{{-- ═══ SIDEBAR ═══ --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
       class="h-screen w-64 fixed left-0 top-0 bg-surface-container-low border-r border-outline-variant flex flex-col py-6 px-4 overflow-y-auto z-50 transition-transform duration-300">

    {{-- Branding --}}
    @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
    <div class="mb-8 px-2 flex items-center gap-3">
        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-white" style="font-size:20px">account_balance</span>
        </div>
        <div>
            <h2 class="text-sm font-bold text-primary leading-tight">Admin Dinas</h2>
            <p class="text-[11px] text-on-surface-variant">Dinas Perdagangan</p>
        </div>
    </div>
    @else
    <div class="mb-8 px-2">
        <div class="flex items-center gap-3 p-3 bg-primary-container rounded-xl">
            <span class="material-symbols-outlined text-white" style="font-size:20px">store</span>
            <div class="min-w-0">
                <p class="text-sm font-bold text-white leading-tight truncate">
                    {{ auth()->user()->store ? auth()->user()->store->name : 'Toko Saya' }}
                </p>
                <p class="text-[11px] text-blue-200 mt-0.5">Dashboard UMKM</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Nav Links --}}
    <nav class="flex-1 space-y-0.5">

        {{-- ADMIN MENU --}}
        @if(in_array(auth()->user()->role, ['admin', 'superadmin']))

        <a href="{{ route('admin.verifikasi') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('admin.verifikasi') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">fact_check</span>
            <span>Verifikasi UMKM</span>
        </a>

        <a href="{{ route('admin.sme_database') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('admin.sme_database') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">storefront</span>
            <span>Database SME</span>
        </a>

        <a href="{{ route('admin.laporan') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('admin.laporan') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">analytics</span>
            <span>Laporan Wilayah</span>
        </a>

        <a href="{{ route('admin.mitra') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('admin.mitra') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">how_to_reg</span>
            <span>Kelola Mitra</span>
            @php
                try {
                    $mitraCount = \App\Models\MitraApplication::where('status','pending')->count();
                } catch(\Exception $e) { $mitraCount = 0; }
            @endphp
            @if($mitraCount > 0)
            <span class="ml-auto px-2 py-0.5 bg-secondary text-white text-[10px] font-bold rounded-full">{{ $mitraCount }}</span>
            @endif
        </a>

        {{-- OWNER MENU --}}
        @else

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">dashboard</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('owner.products') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('owner.products') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">inventory_2</span>
            <span>Daftar Produk</span>
        </a>

        <a href="{{ route('owner.links') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('owner.links') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">link</span>
            <span>Tautan Eksternal</span>
        </a>

        <a href="{{ route('owner.chat.inbox') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('owner.chat*') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">forum</span>
            <span>Live Chat</span>
            @php
                try {
                    $store = auth()->user()->store;
                    $unread = $store ? \App\Models\ChatMessage::where('store_id', $store->id)->where('sender_role','buyer')->where('is_read',false)->count() : 0;
                } catch(\Exception $e) { $unread = 0; }
            @endphp
            @if($unread > 0)
            <span class="ml-auto px-2 py-0.5 bg-secondary text-white text-[10px] font-bold rounded-full">{{ $unread }}</span>
            @endif
        </a>

        <a href="{{ route('owner.inquiries') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('owner.inquiries') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">help</span>
            <span>Pertanyaan Produk</span>
        </a>

        <a href="{{ route('owner.settings') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                  {{ request()->routeIs('owner.settings') ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
            <span class="material-symbols-outlined" style="font-size:20px">settings</span>
            <span>Pengaturan Toko</span>
        </a>

        @if(auth()->user()->store)
        <div class="pt-3 mt-2 border-t border-outline-variant/40">
            <a href="{{ route('store.public', auth()->user()->store->slug) }}" target="_blank"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-primary hover:bg-primary/5 transition-all border border-transparent hover:border-primary/20">
                <span class="material-symbols-outlined" style="font-size:20px">open_in_new</span>
                <span>Lihat Etalase Publik</span>
            </a>
        </div>
        @endif

        @endif
    </nav>

    {{-- Bottom: Bantuan + Logout --}}
    <div class="mt-auto pt-4 border-t border-outline-variant/50 space-y-0.5">
        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-all">
            <span class="material-symbols-outlined" style="font-size:20px">contact_support</span>
            <span>Bantuan</span>
        </a>
        {{-- Trigger logout modal dari Alpine di app.blade.php --}}
        <button @click="logoutOpen = true; sidebarOpen = false"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-error hover:bg-red-50 transition-all">
            <span class="material-symbols-outlined" style="font-size:20px">logout</span>
            <span>Keluar</span>
        </button>
    </div>
</aside>
