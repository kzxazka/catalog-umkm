<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    /** Show single event details page for visitors */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        
        // Fetch other active events as recommendations
        $otherEvents = Event::where('status', 'active')
            ->where('_id', '!=', $id)
            ->limit(3)
            ->get();

        return view('events.show', compact('event', 'otherEvents'));
    }
}
