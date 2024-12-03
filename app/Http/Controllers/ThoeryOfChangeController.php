<?php

namespace App\Http\Controllers;

use App\Events\UserActionPerformed;
use App\Models\Indicator;
use App\Models\Organisation;
use App\Models\TheoryOfChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Renderer\Html\Inline;
use Venturecraft\Revisionable\Revision;

class ThoeryOfChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "Theories of Change";
        $currentUser = Auth::user();

        // Check if the current user is a root user
        if ($currentUser->role === 'root') {
            // Fetch all theories of change if the role is root
            $theories = TheoryOfChange::with('organisation')
                ->withCount('indicators') // Include the count of related indicators
                ->get();
        } else {
            // Fetch theories of change for the current user's organization
            $organisation_id = $currentUser->organisation_id;
            $theories = TheoryOfChange::with('organisation')
                ->withCount('indicators') // Include the count of related indicators
                ->where('organisation_id', $organisation_id)
                ->get();
        }

        return view('theory.list', compact('pageTitle', 'theories'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Create Theory of Change";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        return view('theory.create', compact('pageTitle', 'myOrganisation'));
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
            'title' => 'string|required|max:200',
            'description' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        $theoryOfChange = TheoryOfChange::create($validated);

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        event(new UserActionPerformed($currentUser, 'create_toc', 'TheoryOfChange', $theoryOfChange->id));

        return redirect()->back()
            ->with(['success' => 'ToC Created Successfully', 'myOrganisation' => $myOrganisation]);
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
        $pageTitle = "Create Theory of Change";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $toc = TheoryOfChange::findOrFail($id);

        event(new UserActionPerformed(Auth::user(), 'visit_toc', 'TheoryOfChange', $id));

        return view('theory.update', compact('pageTitle', 'myOrganisation', 'toc'));
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
            'title' => 'string|required|max:200',
            'description' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        $theory = TheoryOfChange::findOrFail($id);

        $theory->update($validated);

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        event(new UserActionPerformed(Auth::user(), 'update_toc', 'TheoryOfChange', $id));

        return redirect()->back()
            ->with(['success' => 'ToC Updated Successfully', 'myOrganisation' => $myOrganisation]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $theory = TheoryOfChange::findOrFail($id);
        $theory->delete();

        event(new UserActionPerformed(Auth::user(), 'delete_toc', 'TheoryOfChange', $id));

        return redirect()->back()->with(['success' => 'ToC deleted successfully.', 'myOrganisation' => $myOrganisation]);
    }

    public function getIndicators($id)
    {
        $pageTitle = "All Indicators linked to ToC";
        $currentUser = Auth::user();

        // Start the query with the base conditions
        $query = Indicator::with(['theoryOfChange', 'responses']) // Eager load Theory of Change and responses
            ->withCount('responses'); // Include the count of related responses

        // Check if the current user is a root user
        if ($currentUser->role !== 'root') {
            // Filter by organization for non-root users
            $organisation_id = $currentUser->organisation_id;
            $query->where('theory_of_change_id', $id)
                ->where('organisation_id', $organisation_id); // Apply both filters
        } else {
            $query->where('theory_of_change_id', $id); // Apply Theory of Change filter
        }

        // Order by response count first (descending), then by created_at for the indicators
        $indicators = $query->orderBy('responses_count', 'desc') // Indicators with responses first
            ->orderBy('created_at', 'desc') // Then order by creation date
            ->paginate(25);

        // Use getCollection() to transform the collection and assign the 'current' value from the latest response
        $indicators->getCollection()->transform(function ($indicator) {
            // Fetch the latest response based on 'original_created_at' in descending order
            $latestResponse = $indicator->responses()->orderBy('created_at', 'desc')->first();
            $indicator->current = $latestResponse ? $latestResponse->current : null; // Set the latest 'current' value

            // Get the last time a response was added based on 'created_at' in descending order
            $latestResponseDate = $indicator->responses()->orderBy('created_at', 'desc')->first();
            $indicator->latest_response_date = $latestResponseDate ? $latestResponseDate->created_at : null; // Set the latest response date

            return $indicator;
        });

        event(new UserActionPerformed(Auth::user(), 'visit_toc', 'TheoryOfChange', $id));

        return view('theory.connectedIndicators', compact('pageTitle', 'indicators'));
    }




    public function createIndicatorUsingToC($id)
    {
        $pageTitle = "Create Indicator Using ToC";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $toc_id = $id;
        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        event(new UserActionPerformed(Auth::user(), 'visit_toc', 'TheoryOfChange', $id));

        return view('theory.createIndicatorForToC', compact('pageTitle', 'myOrganisation', 'theories', 'toc_id'));
    }


    public function getToCHistory($id)
    {
        $pageTitle = "Toc History";
        $toc = TheoryOfChange::findOrFail($id);
        $revisions = $toc->revisionHistory;

        // Renderer configuration for better diff visualization
        $rendererOptions = [
            'detailLevel' => 'word', // Compare at the word level
            'insertedClass' => 'text-success', // CSS class for inserted text
            'deletedClass' => 'text-danger',  // CSS class for deleted text
        ];

        // Add diffs to each revision
        foreach ($revisions as $revision) {
            if ($revision->old_value && $revision->new_value) {
                // Sanitize HTML to extract text content only
                $oldPlainText = strip_tags($revision->old_value);
                $newPlainText = strip_tags($revision->new_value);

                $revision->diffHtml = DiffHelper::calculate(
                    $oldPlainText,
                    $newPlainText,
                    'Inline',
                    $rendererOptions
                );
            } else {
                $revision->diffHtml = null;
            }
        }

        return view('theory.revisions', compact('pageTitle', 'toc', 'revisions'));
    }

    public function revertToCHistory($id, $revisionId)
    {
        // Fetch the revision by ID
        $revision = Revision::findOrFail($revisionId);

        // Get the related TheoryOfChange model
        $toc = $revision->revisionable; // Assuming the revision is related to the TheoryOfChange model

        // Update the model with the old value from the revision
        $toc->update([
            $revision->key => $revision->old_value, // Update the specific field with the old value
        ]);

        // Optionally, return a success message or redirect
        return redirect()->route('theory.history', $toc->id)
            ->with('success', 'Theory of Change has been reverted successfully!');
    }
}
