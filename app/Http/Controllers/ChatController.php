<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Store;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /** Buyer: daftar percakapan aktif */
    public function buyerInbox()
    {
        $buyerId = auth()->id();

        // Dapatkan semua chat milik buyer ini, dikelompokkan berdasarkan store_id
        $chatGroups = ChatMessage::where('buyer_id', $buyerId)
            ->orderBy('_id', -1)
            ->get()
            ->groupBy('store_id');

        $conversations = [];
        foreach ($chatGroups as $storeId => $msgs) {
            $store = Store::find($storeId);
            if ($store) {
                $lastMsg = $msgs->first();
                $unreadCount = $msgs->where('sender_role', 'owner')->where('is_read', false)->count();
                $conversations[] = [
                    'store' => $store,
                    'last_message' => $lastMsg,
                    'unread_count' => $unreadCount,
                ];
            }
        }

        return view('buyer.chat-inbox', compact('conversations'));
    }

    /** Buyer: buka chat dengan toko tertentu */
    public function buyerChat($storeSlug)
    {
        $store    = Store::where('slug', $storeSlug)->firstOrFail();
        $buyerId  = auth()->id();

        $messages = ChatMessage::where('store_id', $store->id)
            ->where('buyer_id', $buyerId)
            ->orderBy('_id', 1)
            ->get();

        // Tandai pesan owner sebagai sudah dibaca oleh buyer
        ChatMessage::where('store_id', $store->id)
            ->where('buyer_id', $buyerId)
            ->where('sender_role', 'owner')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('buyer.chat', compact('store', 'messages'));
    }

    /** Buyer: kirim pesan ke toko */
    public function buyerSend(Request $request, $storeSlug)
    {
        $store = Store::where('slug', $storeSlug)->firstOrFail();

        $request->validate(['message' => 'required|string|max:1000']);

        ChatMessage::create([
            'store_id'    => $store->id,
            'buyer_id'    => auth()->id(),
            'sender_id'   => auth()->id(),
            'sender_role' => 'buyer',
            'message'     => $request->message,
            'is_read'     => false,
            'product_id'  => $request->product_id,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }
        return back();
    }

    /** Owner: daftar percakapan masuk */
    public function ownerInbox()
    {
        $user  = auth()->user();
        $store = $user->store;
        if (!$store) return redirect()->route('dashboard');

        // Grup pesan berdasarkan buyer
        $conversations = ChatMessage::where('store_id', $store->id)
            ->where('sender_role', 'buyer')
            ->orderBy('_id', -1)
            ->get()
            ->groupBy('sender_id')
            ->map(function ($msgs) {
                return $msgs->first(); // pesan terakhir per buyer
            })
            ->values();

        $unreadCount = ChatMessage::where('store_id', $store->id)
            ->where('sender_role', 'buyer')
            ->where('is_read', false)
            ->count();

        return view('owner.chat-inbox', compact('store', 'conversations', 'unreadCount'));
    }

    /** Owner: buka percakapan dengan buyer tertentu */
    public function ownerChat($buyerId)
    {
        $user  = auth()->user();
        $store = $user->store;
        if (!$store) return redirect()->route('dashboard');

        $messages = ChatMessage::where('store_id', $store->id)
            ->where('buyer_id', $buyerId)
            ->orderBy('_id', 1)
            ->get();

        // Tandai pesan buyer sebagai sudah dibaca
        ChatMessage::where('store_id', $store->id)
            ->where('buyer_id', $buyerId)
            ->where('sender_role', 'buyer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $buyer = \App\Models\User::find($buyerId);

        return view('owner.chat', compact('store', 'messages', 'buyer', 'buyerId'));
    }

    /** Owner: balas pesan buyer */
    public function ownerSend(Request $request, $buyerId)
    {
        $user  = auth()->user();
        $store = $user->store;
        if (!$store) return response()->json(['error' => 'No store'], 403);

        $request->validate(['message' => 'required|string|max:1000']);

        ChatMessage::create([
            'store_id'    => $store->id,
            'buyer_id'    => $buyerId,
            'sender_id'   => $user->id,
            'sender_role' => 'owner',
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return response()->json(['ok' => true]);
    }

    /** Polling: ambil pesan terbaru (AJAX) */
    public function poll(Request $request)
    {
        $storeId  = $request->store_id;
        $buyerId  = $request->buyer_id;
        $lastId   = $request->last_id ?? null;
        $role     = $request->role; // 'buyer' atau 'owner'

        $query = ChatMessage::where('store_id', $storeId)
            ->where('buyer_id', $buyerId);

        if ($lastId) {
            $query->where('_id', '>', $lastId);
        }

        $messages = $query->orderBy('_id', 1)->get()->map(function ($m) use ($role) {
            return [
                'id'      => (string) $m->id,
                'message' => $m->message,
                'role'    => $m->sender_role,
                'time'    => $m->created_at?->format('H:i'),
                'is_mine' => ($role === $m->sender_role),
            ];
        });

        return response()->json($messages);
    }
}
