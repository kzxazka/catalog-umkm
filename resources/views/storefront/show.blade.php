@php
    $isOwnerOrAdmin = auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin', 'owner']);
@endphp

@if($isOwnerOrAdmin)
    <x-app-layout>
        @include('storefront._partials.show-content')
    </x-app-layout>
@else
    <x-buyer-layout title="{{ $store->name }} — Portal UMKM">
        <x-slot name="head">
            <meta name="description" content="{{ Str::limit($store->description, 150) }}" />
            <meta property="og:title" content="{{ $store->name }} — Mitra UMKM" />
            <meta property="og:description" content="{{ Str::limit($store->description, 150) }}" />
        </x-slot>
        @include('storefront._partials.show-content')
    </x-buyer-layout>
@endif
