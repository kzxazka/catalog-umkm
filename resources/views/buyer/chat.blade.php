<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat dengan {{ $store->name }} — Portal UMKM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--pri:#011a48;--pri-c:#1b305e;--sec:#9e421e;--surf:#f8f9ff;--out:#c5c6d0;--on:#0b1c30;--onv:#44464f}
        *{font-family:'Public Sans',sans-serif;box-sizing:border-box;margin:0;padding:0}
        body{background:var(--surf);display:flex;flex-direction:column;height:100dvh;overflow:hidden}
        .chat-header{background:var(--pri);padding:12px 16px;display:flex;align-items:center;gap:12px;flex-shrink:0;box-shadow:0 2px 8px rgba(1,26,72,.2)}
        .chat-header .back{color:#b2c5fd;text-decoration:none;display:flex;align-items:center}
        .chat-header .back:hover{color:#fff}
        .store-avatar{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,rgba(255,255,255,.2),rgba(255,255,255,.1));display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .chat-header h1{color:#fff;font-size:14px;font-weight:700;line-height:1.2}
        .chat-header p{color:#b2c5fd;font-size:11px}

        .chat-messages{flex:1;overflow-y:auto;padding:16px 12px;display:flex;flex-direction:column;gap:10px}
        @media(min-width:768px){.chat-messages{padding:20px 24px}}
        .msg-row{display:flex;flex-direction:column;max-width:75%;gap:3px}
        .msg-row.mine{align-self:flex-end;align-items:flex-end}
        .msg-row.theirs{align-self:flex-start;align-items:flex-start}
        .bubble{padding:10px 14px;border-radius:14px;font-size:13px;line-height:1.55;word-break:break-word}
        .bubble.mine{background:var(--pri);color:#fff;border-bottom-right-radius:4px}
        .bubble.theirs{background:#fff;color:var(--on);border:1px solid var(--out);border-bottom-left-radius:4px}
        .msg-time{font-size:10px;color:var(--onv)}
        .sender-name{font-size:10px;font-weight:700;color:var(--pri)}
        .date-divider{text-align:center;font-size:10px;color:var(--onv);font-weight:600;padding:4px 12px;background:rgba(255,255,255,.7);border-radius:100px;align-self:center;border:1px solid var(--out)}
        .empty-chat{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--onv);gap:8px;padding:32px}
        .empty-chat .material-symbols-outlined{font-size:48px;color:var(--out)}
        .empty-chat p{font-size:13px;text-align:center}

        .chat-input-area{background:#fff;border-top:1px solid var(--out);padding:10px 12px;display:flex;align-items:flex-end;gap:8px;flex-shrink:0}
        @media(min-width:768px){.chat-input-area{padding:12px 24px}}
        .chat-textarea{flex:1;padding:10px 14px;border:1.5px solid var(--out);border-radius:20px;font-family:'Public Sans',sans-serif;font-size:13px;outline:none;resize:none;max-height:120px;overflow-y:auto;transition:border-color .15s;line-height:1.4}
        .chat-textarea:focus{border-color:var(--pri)}
        .send-btn{width:40px;height:40px;border-radius:50%;background:var(--pri);border:none;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;transition:opacity .2s}
        .send-btn:hover{opacity:.85}
        .send-btn .material-symbols-outlined{font-size:18px}
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="chat-header">
    <a href="{{ route('catalog.index') }}" class="back">
        <span class="material-symbols-outlined" style="font-size:22px">arrow_back</span>
    </a>
    <div class="store-avatar">
        <span class="material-symbols-outlined" style="color:#fff;font-size:20px">storefront</span>
    </div>
    <div>
        <h1>{{ $store->name }}</h1>
        <p>
            <span class="material-symbols-outlined" style="font-size:11px;vertical-align:middle">verified</span>
            Mitra Terverifikasi
        </p>
    </div>
</div>

{{-- MESSAGES --}}
<div class="chat-messages" id="chat-messages">
    @if($messages->isEmpty())
    <div class="empty-chat">
        <span class="material-symbols-outlined">forum</span>
        <p>Belum ada pesan. Mulai percakapan dengan pemilik toko!</p>
    </div>
    @else
    @foreach($messages as $msg)
    @php $isMine = $msg->sender_role === 'buyer'; @endphp
    <div class="msg-row {{ $isMine ? 'mine' : 'theirs' }}">
        @if(!$isMine)
        <span class="sender-name">{{ $store->name }}</span>
        @endif
        <div class="bubble {{ $isMine ? 'mine' : 'theirs' }}">{{ $msg->message }}</div>
        <span class="msg-time">{{ $msg->created_at?->format('H:i') }}</span>
    </div>
    @endforeach
    @endif
</div>

{{-- INPUT --}}
<div class="chat-input-area">
    <textarea class="chat-textarea"
              id="chat-input"
              placeholder="Ketik pesan..."
              rows="1"
              onInput="autoResize(this)"
              onKeyDown="handleEnter(event)"></textarea>
    <button class="send-btn" onclick="sendMessage()">
        <span class="material-symbols-outlined">send</span>
    </button>
</div>

<script>
const storeSlug = '{{ $store->slug }}';
const storeId   = '{{ $store->id }}';
const buyerId   = '{{ auth()->id() }}';
let lastId      = '{{ $messages->last()?->id ?? "" }}';

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function handleEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function appendMessage(msg, isMine) {
    const container = document.getElementById('chat-messages');
    // Remove empty state
    const empty = container.querySelector('.empty-chat');
    if (empty) empty.remove();

    const row = document.createElement('div');
    row.className = 'msg-row ' + (isMine ? 'mine' : 'theirs');
    row.dataset.id = msg.id;

    if (!isMine) {
        const name = document.createElement('span');
        name.className = 'sender-name';
        name.textContent = '{{ $store->name }}';
        row.appendChild(name);
    }

    const bubble = document.createElement('div');
    bubble.className = 'bubble ' + (isMine ? 'mine' : 'theirs');
    bubble.textContent = msg.message;
    row.appendChild(bubble);

    const time = document.createElement('span');
    time.className = 'msg-time';
    time.textContent = msg.time;
    row.appendChild(time);

    container.appendChild(row);
    container.scrollTop = container.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const text  = input.value.trim();
    if (!text) return;

    input.value = '';
    input.style.height = 'auto';

    try {
        const res = await fetch(`/chat/${storeSlug}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: text })
        });

        if (res.ok) {
            appendMessage({ message: text, time: new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}) }, true);
        }
    } catch (e) {
        console.error(e);
    }
}

// Polling setiap 4 detik untuk pesan baru dari owner
async function pollMessages() {
    try {
        const res = await fetch(`/chat/poll?store_id=${storeId}&buyer_id=${buyerId}&last_id=${lastId}&role=buyer`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        const msgs = await res.json();
        msgs.forEach(msg => {
            if (!document.querySelector(`[data-id="${msg.id}"]`)) {
                appendMessage(msg, msg.is_mine);
                lastId = msg.id;
            }
        });
    } catch(e) {}
}

// Scroll to bottom on load
document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
setInterval(pollMessages, 4000);
</script>
</body>
</html>
