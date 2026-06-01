<x-buyer-layout title="{{ $event->title }} — Event Portal UMKM">
    <x-slot name="head">
        <meta name="description" content="{{ Str::limit(strip_tags($event->description), 150) }}" />
    </x-slot>

    <div class="bg-[#f8f9ff] min-h-screen pb-16">
        {{-- Navigation --}}
        <nav class="py-5 px-6 max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1 text-xs font-black uppercase tracking-widest text-on-surface-variant hover:text-primary transition">
                <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span> Kembali ke Beranda
            </a>
            <span class="bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                📢 EVENT DINAS PERDAGANGAN
            </span>
        </nav>

        {{-- Main Article --}}
        <main class="max-w-4xl mx-auto px-4 sm:px-6">
            <article class="bg-white rounded-3xl border border-outline-variant/60 shadow-xl overflow-hidden">
                
                {{-- Banner/Photo Section --}}
                <div class="w-full aspect-[21/9] sm:aspect-[16/6] bg-surface-container relative overflow-hidden border-b border-outline-variant/40">
                    @if($event->image)
                        <img src="{{ asset('storage/events/' . $event->image) }}" 
                             alt="{{ $event->title }}"
                             class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant/40">
                            <span class="material-symbols-outlined text-6xl">campaign</span>
                        </div>
                    @endif
                </div>

                {{-- Content Body --}}
                <div class="p-6 sm:p-10">
                    <h1 class="text-2xl sm:text-4xl font-black uppercase tracking-tight text-on-surface leading-tight mb-6">
                        {{ $event->title }}
                    </h1>

                    {{-- Event Metadata Card --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-surface-container-low border border-outline-variant/50 rounded-2xl p-5 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined" style="font-size:20px">calendar_month</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Waktu Pelaksanaan</p>
                                <p class="text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined" style="font-size:20px">location_on</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Lokasi Event</p>
                                <p class="text-sm font-bold text-on-surface">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Event Description --}}
                    <div class="prose max-w-none text-on-surface text-sm sm:text-base leading-relaxed font-sans space-y-4">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </article>

            {{-- Other / Recent Events --}}
            @if($otherEvents->isNotEmpty())
                <div class="mt-16">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-black uppercase tracking-wider text-sm sm:text-base text-on-surface flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-primary rounded-full animate-ping"></span>
                            Event Menarik Lainnya
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        @foreach($otherEvents as $other)
                            <a href="{{ route('events.show', $other->id) }}" 
                               class="bg-white rounded-2xl border border-outline-variant/60 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col h-full group">
                                <div class="aspect-[16/9] bg-surface-container overflow-hidden">
                                    @if($other->image)
                                        <img src="{{ asset('storage/events/' . $other->image) }}" 
                                             alt="{{ $other->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant/40">
                                            <span class="material-symbols-outlined text-4xl">campaign</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="font-bold text-xs uppercase text-on-surface tracking-tight group-hover:text-primary transition-colors line-clamp-2 leading-tight mb-2">
                                            {{ $other->title }}
                                        </h3>
                                        <p class="text-[10px] text-on-surface-variant line-clamp-3 mb-4 leading-relaxed">
                                            {{ Str::limit(strip_tags($other->description), 90) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1.5 pt-3 border-t border-outline-variant/50 text-[9px] font-black text-on-surface-variant uppercase tracking-wider">
                                        <span class="material-symbols-outlined" style="font-size:12px">calendar_today</span>
                                        {{ \Carbon\Carbon::parse($other->event_date)->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>
    </div>
</x-buyer-layout>
