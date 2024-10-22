<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\ArchivedIndicator;
use App\Models\ArchivedResponse;
use App\Models\Indicator;
use App\Models\Organisation;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArchivesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "All Archives";
        $currentUser = Auth::user();

        // Start the query for archives with organization data and count of indicators
        $query = Archive::with('organisation') // Eager load organization data
            ->withCount('indicators'); // Count indicators in each archive

        // Check if the current user is a root user
        if ($currentUser->role === 'root') {
            // If the user is root, do not filter by organization
        } else {
            // Filter by organization for non-root users
            $organisation_id = $currentUser->organisation_id;
            $query->where('organisation_id', $organisation_id);
        }

        // Paginate the results
        $archives = $query->paginate(25);

        return view('archives.list', compact('pageTitle', 'archives'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Create Archive";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        return view('archives.create', compact('pageTitle', 'myOrganisation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $user_id = $currentUser->id;

        $validated = $request->validate([
            'title' => 'string|required|max:150',
            'description' => 'string:required',
            'status' => 'string|required',
            'access_level' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id'
        ]);

        $validated['user_id'] = $user_id;

        Archive::create($validated);

        return redirect()->back()->with(["success" => "Archive created successfully", "myOrganisation" => $myOrganisation]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = "Indicators in Archive";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        $archive = Archive::findOrFail($id);

        // Retrieve the indicators from the archived_indicators table where the archive_id matches the given id
        $indicators = ArchivedIndicator::with([
            'theoryOfChange',
            'responses' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Order responses by the latest
            }
        ])
            ->withCount('responses') // Add response count
            ->where('archive_id', $id)
            ->where('organisation_id', $organisation_id) // Ensure it belongs to the current organization
            ->orderByDesc('responses_count') // Sort by responses count in descending order
            ->paginate(25);

        // If no archived indicators are found, you can handle that as needed
        if ($indicators->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'No indicators found in this archive.']);
        }

        // Add the 'current' value from the latest response to each indicator
        $indicators->getCollection()->transform(function ($indicator) {
            // Manually fetch the latest response based on 'created_at' in ascending order
            $latestResponse = $indicator->responses->sortBy('created_at')->last();
            $indicator->current = $latestResponse ? $latestResponse->current : null; // Add latest 'current' value
            return $indicator;
        });


        return view('archives.indicators.list', compact('pageTitle', 'indicators', 'archive'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Edit Archive details";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $archive = Archive::findOrFail($id);

        return view('archives.update', compact('pageTitle', 'archive', 'myOrganisation'));
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
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        $validated = $request->validate([
            'title' => 'string|required|max:150',
            'description' => 'string:required',
            'status' => 'string|required',
            'access_level' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id'
        ]);

        $archive = Archive::findOrFail($id);

        $archive->update($validated);

        return redirect()->back()->with(["success" => "Archive updated successfully", "myOrganisation" => $myOrganisation]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $archive = Archive::find($id);

        if (!$archive) {
            return response()->json(['message' => 'Archive not found'], 404);
        }

        $archive->delete(); // This will soft delete the record

        return redirect()->back()->with('success', 'Archive deleted successfully');
    }

    public function moveArchivedIndicatorToArchive($archive_id, $indicator_id)
    {
        // Find the specific archived indicator by its ID
        $indicator = Indicator::where('id', $indicator_id)->where('status', 'archived')->first();

        if (!$indicator) {
            return response()->json(['error' => 'No indicator with status archived found.'], 404);
        }

        // Use a transaction to ensure atomicity
        DB::beginTransaction();
        try {
            // Create a new ArchivedIndicator entry
            $archivedIndicator = ArchivedIndicator::create([
                'indicator_id' => $indicator->id,
                'name' => $indicator->name,
                'indicator_title' => $indicator->indicator_title,
                'definition' => $indicator->definition,
                'baseline' => $indicator->baseline,
                'target' => $indicator->target,
                'current_state' => $indicator->current_state,
                'data_source' => $indicator->data_source,
                'frequency' => $indicator->frequency,
                'responsible' => $indicator->responsible,
                'reporting' => $indicator->reporting,
                'status' => $indicator->status,
                'direction' => $indicator->direction,
                'category' => $indicator->category,
                'organisation_id' => $indicator->organisation_id,
                'archive_id' => $archive_id,
                'qualitative_progress' => $indicator->qualitative_progress,
                'is_manually_updated' => $indicator->is_manually_updated,
                'theory_of_change_id' => $indicator->theory_of_change_id,
            ]);

            // Move associated responses
            $responses = Response::where('indicator_id', $indicator->id)->get();
            foreach ($responses as $response) {
                ArchivedResponse::create([
                    'response_id' => $response->id,
                    'indicator_id' => $archivedIndicator->indicator_id, // Link to the archived indicator
                    'current' => $response->current,
                    'progress' => $response->progress,
                    'notes' => $response->notes,
                    'lessons' => $response->lessons,
                    'recommendations' => $response->recommendations,
                    'files' => $response->files,
                    'status' => $response->status,
                    'organisation_id' => $response->organisation_id,
                    'user_id' => $response->user_id,
                ]);

                // Delete the original response
                $response->delete();
            }

            // Delete the original indicator
            $indicator->delete();

            DB::commit();
            return response()->json(['success' => 'Indicator moved to the archive successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to move the indicator: ' . $e->getMessage()], 500);
        }
    }


    public function getArchives()
    {
        $archives = Archive::where('status', 'active')->get();
        return response()->json($archives);
    }


    public function getIndicator($id)
    {
        $pageTitle = "Indicator details";
        // Use first() to get a single result
        $indicator = ArchivedIndicator::with('theoryOfChange')->where('indicator_id', $id)->firstOrFail();

        return view('archives.indicators.view', compact('pageTitle', 'indicator'));
    }

    public function getResponsesForIndicator($indicator_id)
    {
        $indicatorId = $indicator_id;
        $pageTitle = "Indicator responses";
        // Fetch the responses along with related indicator and user (from the users table)
        $responses = ArchivedResponse::with(['indicator', 'user'])
            ->where('indicator_id', $indicator_id)
            ->orderBy('user_id')  // First order by user
            ->orderBy('created_at')  // Then order by created_at for each user
            ->get();

        // dd($responses);

        // Initialize variables for row number calculation
        $currentUserId = null;
        $rowNumber = 0;

        // Add the row number and formatted response tag
        $responses = $responses->map(function ($response) use (&$currentUserId, &$rowNumber) {
            if ($currentUserId !== $response->user_id) {
                // Reset row number if new user
                $currentUserId = $response->user_id;
                $rowNumber = 1;
            } else {
                // Increment row number for the same user
                $rowNumber++;
            }

            // Add response_tag and response_tag_label
            $response->response_tag = $rowNumber;
            $response->response_tag_label = 'Response ' . $rowNumber;

            return $response;
        });



        return view('archives.indicators.responses.list', compact('pageTitle', 'responses', 'indicatorId'));
    }
}
