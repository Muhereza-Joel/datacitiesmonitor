<?php

namespace App\Http\Controllers;

use App\Events\UserActionPerformed;
use App\Jobs\IndexIndicatorJob;
use App\Models\Archive;
use App\Models\Indicator;
use App\Models\Organisation;
use App\Models\TheoryOfChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageTitle = "All Indicators";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        // Start the query with the base conditions
        $query = Indicator::with(['theoryOfChange', 'organisation'])
            ->withCount('responses') // Add response count
            ->where('organisation_id', $organisation_id);

        // Apply filters if they are present in the request
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('qualitative_progress')) {
            $query->where('qualitative_progress', $request->input('qualitative_progress'));
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->input('category') . '%');
        }

        // Order the results by created_at
        $query->orderBy('created_at', 'desc'); // Change 'desc' to 'asc' for ascending order

        // Paginate the filtered results
        $indicators = $query->paginate(24);

        // Iterate through each indicator to set the current value from the last response
        foreach ($indicators as $indicator) {
            // Check if there is a response and set the current value
            $latestResponse = $indicator->responses->first();
            $indicator->current = $latestResponse ? $latestResponse->current : null; // Assuming 'value' is the attribute for current
        }

        // Load the latest response's created_at for each indicator
        $indicators->load(['responses' => function ($query) {
            $query->select('indicator_id', 'created_at')->latest()->limit(1);
        }]);

        // Add each indicator to the TNTSearch index if not already indexed
        // foreach ($indicators as $indicator) {
        //     IndexIndicatorJob::dispatch($indicator);
        // }

        return view('indicators.list', compact('pageTitle', 'indicators'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Create Indicators";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        return view('indicators.create', compact('pageTitle', 'myOrganisation', 'theories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'string|required',
            'name' => 'string|required',
            'theory_of_change_id' => 'string|required|exists:theory_of_changes,id',
            'direction' => 'string|required',
            'indicator_title' => 'string|required',
            'definition' => 'string|required',
            'baseline' => 'required|numeric',
            'target' => 'required|numeric',
            'data_source' => 'string|required',
            'frequency' => 'string|required',
            'responsible' => 'string|required',
            'reporting' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        Indicator::create($validated);

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        return redirect()->back()->with(['success' => 'Indicator Created Successfully', 'myOrganisation' => $myOrganisation, 'theories' => $theories]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = "Indicator details";
        // Use first() to get a single result
        $indicator = Indicator::with('theoryOfChange')->where('id', $id)->firstOrFail();

        return view('indicators.view', compact('pageTitle', 'indicator'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Update Indicator details";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        // Find the indicator by id and ensure it belongs to the current user's organisation
        $indicator = Indicator::where('id', $id)
            ->where('organisation_id', $organisation_id)
            ->firstOrFail();

        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        return view('indicators.update', compact('pageTitle', 'myOrganisation', 'indicator', 'theories'));
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
            'category' => 'string|required',
            'name' => 'string|required',
            'theory_of_change_id' => 'string|required|exists:theory_of_changes,id',
            'direction' => 'string|required',
            'indicator_title' => 'string|required',
            'definition' => 'string|required',
            'baseline' => 'required|numeric',
            'target' => 'required|numeric',
            'data_source' => 'string|required',
            'frequency' => 'string|required',
            'responsible' => 'string|required',
            'reporting' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $indicator = Indicator::findOrFail($id);
            $indicator->update($validated);

            // Fetch related responses
            $responses = $indicator->responses;

            // Get the updated baseline and target
            $baseline = $validated['baseline'];
            $target = $validated['target'];
            $direction = $validated['direction'];

            // Recalculate progress for each response
            foreach ($responses as $response) {
                // Calculate progress based on the formula
                if ($baseline === $target) {
                    // If baseline equals target, set progress to 100%
                    $response->progress = 100;
                } else {
                    if ($direction === 'increasing') {
                        // For increasing, calculate progress using (current - baseline) / (target - baseline) * 100
                        $response->progress = (($response->current - $baseline) / ($target - $baseline)) * 100;
                    } elseif ($direction === 'decreasing') {
                        // For decreasing, calculate progress using (baseline - current) / (baseline - target) * 100
                        $response->progress = (($baseline - $response->current) / ($baseline - $target)) * 100;
                    }
                }

                // Validate that the recalculated progress is within bounds
                if ($response->progress < 0 || $response->progress > 100) {
                    throw new \Exception('Recalculated progress for response is out of bounds (0-100).');
                }

                // Check if the recalculated current value falls within the new baseline and target range
                if ($direction === 'increasing' && ($response->current < $baseline || $response->current > $target)) {
                    throw new \Exception('Current value ' . $response->current . ' is out of range for increasing direction. It must be between ' . $baseline . ' and ' . $target . '.');
                } elseif ($direction === 'decreasing' && ($response->current > $baseline || $response->current < $target)) {
                    throw new \Exception('Current value ' . $response->current . ' is out of range for decreasing direction. It must be between ' . $target . ' and ' . $baseline . '.');
                }

                // Save the updated response
                $response->save();
            }
        });

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        event(new UserActionPerformed(Auth::user(), 'Updated indicator', 'Indicator', $id));

        return redirect()->back()->with(['success' => 'Indicator Updated Successfully', 'myOrganisation' => $myOrganisation, 'theories' => $theories]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'string|required|in:draft,review,public,archived',
            'qualitative_progress' => 'string|required|in:on track,at risk,off track,completed,not started',
        ]);

        // Use a transaction to ensure atomic updates
        DB::beginTransaction();

        try {
            $indicator = Indicator::findOrFail($id);
            $indicator->status = $validated['status'];
            $indicator->qualitative_progress = $validated['qualitative_progress'];
            $indicator->save();

            // Update the status of the corresponding responses
            $indicator->responses()->update(['status' => $validated['status']]);

            // Commit the transaction if everything goes well
            DB::commit();

            return redirect()->back()->with(['success' => 'Indicator Status Updated Successfully']);
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Failed to update Indicator Status: ' . $e->getMessage()]);
        }
    }

    public function getOrganisationPublications($id)
    {
        $pageTitle = "Publications";
        $type = request('type', 'public_indicators'); // Default to 'public_indicators'

        // Fetch the organisation
        $organisation = Organisation::find($id);

        if (!$organisation) {
            return redirect()->back()->with('error', 'Organisation not found.');
        }

        // Query based on the type
        if ($type == 'archives') {
            // Use Archive model for archived items
            $items = Archive::with('organisation')
                ->where('organisation_id', $id)
                ->whereIn('access_level', ['public'])
                ->paginate(25);
            $view = 'organisations.listArchives'; // View for archives
        } else {
            // Use Indicator model for public indicators
            $items = Indicator::with('organisation')
                ->where('organisation_id', $id)
                ->where('status', 'public')
                ->withCount('responses') // Add response count
                ->paginate(25);
            $view = 'organisations.listPublicIndicators'; // View for public indicators
        }

        return view($view, compact('pageTitle', 'items', 'organisation', 'type'));
    }
}
