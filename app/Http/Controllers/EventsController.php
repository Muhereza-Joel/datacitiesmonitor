<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($action = null, $visibility = 'all')
    {
        $pageTitle = "Manage Events";
        $myOrganisation = Auth::user()->organisation;

        // Ensure myOrganisation exists to prevent null errors
        if (!$myOrganisation) {
            abort(403, "You do not belong to an organisation.");
        }

        // Filter events by organisation_id and order by created_at
        $events = Event::where('organisation_id', $myOrganisation->id)
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        return view('events.list', compact('pageTitle', 'myOrganisation', 'action', 'events'));
    }



    public function showCalender()
    {
        $pageTitle = "Calendar";
        $myOrganisation = Auth::user()->organisation;
        return view('events.calendar', compact('pageTitle', 'myOrganisation'));
    }

    public function getEvents($visibility)
    {
        // Get the logged-in user's organisation
        $myOrganisation = Auth::user()->organisation;

        // Start by querying all events
        $eventsQuery = Event::with('organisation')->where('active', 1); // Eager load the organisation relationship directly

        // Apply visibility-based filtering
        if ($visibility === 'all') {
            // Show events that are either 'all' visibility or 'external' visibility
            $eventsQuery->where(function ($query) {
                $query->where('visibility', 'all')
                    ->orWhere('visibility', 'external');
            });
        } elseif ($visibility === 'internal') {
            // Only show events with the same organisation_id and internal visibility
            $eventsQuery->where('organisation_id', $myOrganisation->id)
                ->where('visibility', 'internal');
        } elseif ($visibility === 'external') {
            // Show events that are visible only to other organisations
            $eventsQuery->where('organisation_id', '!=', $myOrganisation->id)
                ->where('visibility', 'external');
        }

        // Get the events based on the filtered query
        $events = $eventsQuery->get();

        // Format the events to match the structure expected by your front-end
        $formattedEvents = $events->map(function ($event) {
            return [
                'title' => strip_tags($event->event), // Use strip_tags to remove HTML
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'logo' => $event->organisation ? asset($event->organisation->logo) : null, // Ensure organisation exists before accessing logo
                'visibility' => $event->visibility, // Assuming 'visibility' is a valid attribute
            ];
        });

        // Return the formatted events as a JSON response
        return response()->json($formattedEvents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'event' => 'string|required',
            'visibility' => 'string|required|in:all,internal,external',
            'active' => 'numeric|required',
            'start_date' => 'date|required',
            'end_date' => 'date|required',
        ]);

        $validated['organisation_id'] = Auth::user()->organisation->id;
        $validated['user_id'] = Auth::user()->id;

        $event = Event::create($validated);

        return response()->json(['success' => true, 'event' => $event]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Edit Event";
        $event = Event::findOrFail($id);
        return view('events.edit', compact('pageTitle', 'event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'event' => 'string|required',
            'visibility' => 'string|required|in:all,internal,external',
            'active' => 'numeric|required',
            'start_date' => 'date|required',
            'end_date' => 'date|required',
        ]);

        $event = Event::findOrFail($id);
        $event->update($validated);

        return response()->json(['success' => true, 'event' => $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->back()->with(['success' => 'Event deleted successfully.']);
    }
}
