<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminEventController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
                    abort(403, 'Akses khusus Admin Dinas Perdagangan.');
                }
                return $next($request);
            }),
        ];
    }

    /** List all events (Admin panel) */
    public function index()
    {
        $events = Event::orderBy('_id', -1)->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    /** Show create event form */
    public function create()
    {
        return view('admin.events.create');
    }

    /** Store newly created event in database */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'location'    => 'required|string|max:250',
            'status'      => 'required|in:active,draft',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:3072', // Max 3MB
        ]);

        $data = $request->only(['title', 'description', 'event_date', 'location', 'status']);

        // Professional Cryptographic Image Upload Hashing
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Cryptographic MD5 hash of the original name, unique microtime and random seed
            $hashName = md5(uniqid(microtime(), true) . $file->getClientOriginalName());
            $filename = $hashName . '.' . $file->getClientOriginalExtension();
            
            // Save to public storage/events
            $file->storeAs('events', $filename, 'public');
            $data['image'] = $filename;
        }

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event baru berhasil dipublikasikan!');
    }

    /** Show edit event form */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    /** Update existing event details */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'location'    => 'required|string|max:250',
            'status'      => 'required|in:active,draft',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = $request->only(['title', 'description', 'event_date', 'location', 'status']);

        // Professional Cryptographic Image Upload Hashing on update
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($event->image) {
                Storage::disk('public')->delete('events/' . $event->image);
            }

            $file = $request->file('image');
            $hashName = md5(uniqid(microtime(), true) . $file->getClientOriginalName());
            $filename = $hashName . '.' . $file->getClientOriginalExtension();
            
            $file->storeAs('events', $filename, 'public');
            $data['image'] = $filename;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Informasi Event berhasil diperbarui!');
    }

    /** Delete event from storage */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Delete associated image file
        if ($event->image) {
            Storage::disk('public')->delete('events/' . $event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
