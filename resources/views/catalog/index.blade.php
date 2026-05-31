@php
    $isOwnerOrAdmin = auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin', 'owner']);
@endphp

@if($isOwnerOrAdmin)
    {{-- Owner & Admin: pakai layout dashboard dengan sidebar --}}
    <x-app-layout>
        @include('catalog._partials.index-content')
    </x-app-layout>
@else
    {{-- Buyer & Guest: pakai buyer layout --}}
    <x-buyer-layout title="Katalog Produk UMKM — Portal Dinas Perdagangan">
        <x-slot name="head">
            <meta name="description" content="Temukan produk lokal terbaik dari UMKM terverifikasi Dinas Perdagangan." />
        </x-slot>
        @include('catalog._partials.index-content')
    </x-buyer-layout>
@endif
