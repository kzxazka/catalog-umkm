<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black/50 md:hidden" @click="sidebarOpen = false" style="display: none;"></div>

<!-- SideNavBar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="h-screen w-64 fixed left-0 top-0 bg-surface-container-low border-r border-outline-variant flex flex-col py-6 px-4 overflow-y-auto z-50 md:translate-x-0 transition-transform duration-300">
    
    @if(auth()->user()->role === 'superadmin')
    <!-- Admin Branding -->
    <div class="mb-8 px-2 flex items-center gap-3">
        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-on-primary">account_balance</span>
        </div>
        <div>
            <h2 class="font-headline-md text-xl font-bold text-primary leading-tight">Admin Dinas</h2>
            <p class="text-[12px] text-on-surface-variant">Kemenkop & UKM</p>
        </div>
    </div>
    @else
    <!-- UMKM Branding -->
    <div class="mb-8 px-2">
        <div class="flex items-center gap-3 p-3 bg-primary-container rounded-xl text-on-primary-container">
            <span class="material-symbols-outlined text-on-primary-container">store</span>
            <div>
                <p class="font-label-md leading-tight text-sm font-bold">{{ auth()->user()->store ? auth()->user()->store->name : 'Toko Saya' }}</p>
                <p class="text-[11px] opacity-80 mt-1 uppercase tracking-widest">Dashboard UMKM</p>
            </div>
        </div>
    </div>
    @endif

    <nav class="flex-1 space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md transition-all duration-200">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>

        @if(auth()->user()->role === 'superadmin')
        <a href="{{ route('admin.verifikasi') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.verifikasi') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md transition-all duration-200 group">
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">fact_check</span>
            <span>Verifikasi UMKM</span>
        </a>
        <a href="{{ route('admin.sme_database') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.sme_database') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md transition-all duration-200 group">
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">storefront</span>
            <span>SME Database</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.laporan') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md transition-all duration-200 group">
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">analytics</span>
            <span>Laporan Wilayah</span>
        </a>
        @else
        <a href="{{ route('owner.products') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('owner.products') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md transition-all duration-200 group">
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">inventory_2</span>
            <span>Daftar Produk</span>
        </a>
        <a href="{{ route('owner.links') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('owner.links') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md transition-all duration-200 group">
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">link</span>
            <span>Tautan Eksternal</span>
        </a>
        <a href="{{ route('owner.settings') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('owner.settings') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md transition-all duration-200 group">
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">settings</span>
            <span>Pengaturan Profil</span>
        </a>
        @if(auth()->user()->store)
        <div class="mt-4 pt-4 border-t border-outline-variant/30">
            <a href="{{ route('store.public', auth()->user()->store->slug) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-primary hover:bg-primary/5 rounded-lg font-label-md transition-all duration-200 border border-transparent hover:border-primary/20">
                <span class="material-symbols-outlined">open_in_new</span>
                <span>Lihat Etalase Publik</span>
            </a>
        </div>
        @endif
        @endif
    </nav>

    <div class="mt-auto pt-6 border-t border-outline-variant space-y-1">
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-label-md transition-all duration-200">
            <span class="material-symbols-outlined">contact_support</span>
            <span>Bantuan</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-error hover:bg-error-container/20 rounded-lg font-label-md transition-all duration-200">
                <span class="material-symbols-outlined">logout</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
