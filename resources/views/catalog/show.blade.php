@php
    $isOwnerOrAdmin = auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin', 'owner']);
@endphp

@if($isOwnerOrAdmin)
    <x-app-layout>
        @include('catalog._partials.show-content')
    </x-app-layout>
@else
    <x-buyer-layout title="{{ $product->name }} — Portal UMKM">
        <x-slot name="head">
            <meta name="description" content="{{ Str::limit($product->description, 150) }}" />
        </x-slot>
        @include('catalog._partials.show-content')
    </x-buyer-layout>
@endif
