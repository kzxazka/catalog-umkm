<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6 pb-24">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div>
            <h1 class="text-xl font-bold text-primary">Live Chat</h1>
            <p class="text-xs text-on-surface-variant">Percakapan masuk dari buyer</p>
        </div>
        @if($unreadCount > 0)
        <span class="ml-auto px-2.5 py-1 bg-error text-on-error text-xs font-bold rounded-full">
            {{ $unreadCount }} baru
        </span>
        @endif
    </div>

    @if($conversations->isEmpty())
    <div class="bg-white border border-outline-variant rounded-2xl p-12 text-center">
        <span class="material-symbols-outlined text-outline-variant text-5xl mb-4 block">forum</span>
        <h3 class="text-lg font-bold text-on-surface mb-2">Belum Ada Pesan Masuk</h3>
        <p class="text-sm text-on-surface-variant">Pesan dari buyer akan muncul di sini.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($conversations as $msg)
        @php
            $buyer = \App\Models\User::find($msg->sender_id);
        @endphp
        <a href="{{ route('owner.chat', $msg->sender_id) }}"
           class="bg-white border border-outline-variant rounded-2xl p-4 flex items-center gap-4 hover:border-primary hover:shadow-sm transition-all group">
            <div class="w-11 h-11 rounded-full bg-primary flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                @if($buyer && $buyer->avatar_path)
                    <img src="{{ asset('storage/'.$buyer->avatar_path) }}" alt="" class="w-11 h-11 rounded-full object-cover" />
                @else
                    {{ $buyer ? $buyer->initials : '?' }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-bold text-on-surface">{{ $buyer?->name ?? 'Buyer' }}</p>
                    <span class="text-xs text-on-surface-variant">{{ $msg->created_at?->diffForHumans() }}</span>
                </div>
                <p class="text-xs text-on-surface-variant truncate">{{ $msg->message }}</p>
            </div>
            @if(!$msg->is_read)
            <div class="w-2.5 h-2.5 rounded-full bg-secondary flex-shrink-0"></div>
            @endif
            <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" style="font-size:18px">chevron_right</span>
        </a>
        @endforeach
    </div>
    @endif
</div>
</x-app-layout>
