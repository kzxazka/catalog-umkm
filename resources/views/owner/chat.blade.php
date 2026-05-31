<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat dengan {{ $buyer?->name ?? 'Buyer' }} — {{ $store->name }}</title>
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
        .back{color:#b2c5fd;text-decoration:none;display:flex;align-items:center}
        .back:hover{color:#fff}
        .buyer-avatar{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:14px;flex-shrink:0}
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
        .sender-name{font-size:10px;font-weight:700;color:var(--sec)}
        .empty-chat{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--onv);gap:8px;padding:32px}
        .empty-chat .material-symbols-outlined{font-size:48px;color:var(--out)}
        .chat-input-area{background:#fff;border-top:1px solid var(--out);padding:10px 12px;display:flex;align-items:flex-end;gap:8px;flex-shrink:0}
        @media(min-width:768px){.chat-input-area{padding:12px 24px}}
        .chat-textarea{flex:1;padding:10px 14px;border:1.5px solid var(--out);border-radius:20px;font-family:'Public Sans',sans-serif;font-size:13px;outline:none;resize:none;max-height:120px;overflow-y:auto;transition:border-color .15s;line-height:1.4}
        .chat-textarea:focus{border-color:var(--pri)}
        .send-btn{width:40px;height:40px;border-radius:50%;background:var(--pri);border:none;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;transition:opacity .2s}
        .send-btn:hover{opacity:.85}
    </style>
</head>
<body>

<div class="chat-header">
    <a href="{{ route('owner.chat.inbox') }}" class="back">
        <span class="material-symbols-outlined" style="font-size:22px">arrow_back</span>
    </a>
    <div class="buyer-avatar">
        @if($buyer && $buyer->avatar_path)
            <img src="{{ asset('storage/'.$buyer->avatar_path) }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;" />
        @else
            {{ $buyer?->initials ?? '?' }}
        @endif
    </div>
    <div>
        <h1>{{ $buyer?->name ?? 'Buyer' }}</h1>
        <p>{{ $buyer?->email ?? '' }}</p>
    </div>
</div>

<div class="chat-messages" id="chat-messages">
    @if($messages->isEmpty())
    <div class="empty-chat">
        <span class="material-symbols-outlined">forum</span>
        <p>Belum ada pesan dari buyer ini.</p>
    </div>
    @else
    @foreach($messages as $msg)
    @php $isMine = $msg->sender_role === 'owner'; @endphp
    <div class="msg-row {{ $isMine ? 'mine' : 'theirs' }}" data-id="{{ $msg->id }}">
        @if(!$isMine)
        <span class="sender-name">{{ $buyer?->name ?? 'Buyer' }}</span>
        @endif
        <div class="bubble {{ $isMine ? 'mine' : 'theirs' }}">{{ $msg->message }}</div>
        <span class="msg-time">{{ $msg->created_at?->format('H:i') }}</span>
    </div>
    @endforeach
    @endif
</div>

<div class="chat-input-area">
    <textarea class="chat-textarea" id="chat-input" placeholder="Balas pesan..." rows="1"
              onInput="autoResize(this)" onKeyDown="handleEnter(event)"></textarea>
    <button class="send-btn" onclick="sendMessage()">
        <span class="material-symbols-outlined">send</span>
    </button>
</div>

<script>
const buyerId  = '{{ $buyerId }}';
const storeId  = '{{ $store->id }}';
let lastId     = '{{ $messages->last()?->id ?? "" }}';

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}
function handleEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
}
function appendMessage(msg, isMine) {
    const container = document.getElementById('chat-messages');
    const empty = container.querySelector('.empty-chat');
    if (empty) empty.remove();
    const row = document.createElement('div');
    row.className = 'msg-row ' + (isMine ? 'mine' : 'theirs');
    row.dataset.id = msg.id || '';
    const bubble = document.createElement('div');
    bubble.className = 'bubble ' + (isMine ? 'mine' : 'theirs');
    bubble.textContent = msg.message;
    row.appendChild(bubble);
    const time = document.createElement('span');
    time.className = 'msg-time';
    time.textContent = msg.time || '';
    row.appendChild(time);
    container.appendChild(row);
    container.scrollTop = container.scrollHeight;
}
async function sendMessage() {
    const input = document.getElementById('chat-input');
    const text = input.value.trim();
    if (!text) return;
    input.value = ''; input.style.height = 'auto';
    try {
        const res = await fetch(`/owner/chat/${buyerId}/send`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ message: text })
        });
        if (res.ok) {
            appendMessage({ message: text, time: new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}) }, true);
        }
    } catch(e) {}
}
async function pollMessages() {
    try {
        const res = await fetch(`/chat/poll?store_id=${storeId}&buyer_id=${buyerId}&last_id=${lastId}&role=owner`, {
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
document.getElementById('chat-messages').scrollTop = document.getElementById('chat-messages').scrollHeight;
setInterval(pollMessages, 4000);
</script>
</body>
</html>
