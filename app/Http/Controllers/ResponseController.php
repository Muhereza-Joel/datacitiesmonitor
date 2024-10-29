<?php

namespace App\Http\Controllers;

use App\Events\UserActionPerformed;
use App\Models\Indicator;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function createResponse($id)
    {
        $pageTitle = "Add response to indicator";

        $indicator = Indicator::findOrFail($id);

        // Get the last response for the indicator, ordered by the latest created response
        $lastResponse = Response::where('indicator_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Set the 'last_current_state' key for use in the view
        $lastCurrentState = [
            'last_current_state' => $lastResponse ? $lastResponse->current : null
        ];

        event(new UserActionPerformed(Auth::user(), 'visit_indicator', 'Indicator', $id));

        return view('indicators.responses.create', compact('pageTitle', 'indicator', 'lastCurrentState'));
    }

    public function storeResponse(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'indicator_id' => 'required|string|exists:indicators,id',
            'baseline' => 'required|numeric',
            'current' => 'required|numeric',
            'target' => 'required|numeric',
            'progress' => 'required|numeric',
            'lessons' => 'nullable|string',
            'notes' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        // Get current user and their organization
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $user_id = $currentUser->id;

        // Fetch the indicator to get its status
        $indicator = Indicator::findOrFail($request->indicator_id);
        $status = $indicator->status; // Assuming the Indicator model has a 'status' field

        // Add organisation_id, user_id, and status to the validated data
        $validated['organisation_id'] = $organisation_id;
        $validated['user_id'] = $user_id;
        $validated['status'] = $status; // Include the status of the indicator

        // Create the response with the additional fields
        Response::create($validated);

        return response()->json([
            'message' => 'Response added successfully!',
        ], 200);
    }


    public function editResponse($id)
    {
        $pageTitle = "Edit response details";

        $response = Response::with('indicator')->findOrFail($id);

        // Set the 'last_current_state' key for use in the view
        $lastCurrentState = [
            'last_current_state' => $response ? $response->current : null
        ];

        return view('indicators.responses.update', compact('pageTitle', 'response', 'lastCurrentState'));
    }

    public function updateResponse(Request $request)
    {
        // Validate request
        $id = $request->id;
        $validated = $request->validate([
            'indicator_id' => 'required|string|exists:indicators,id',
            'baseline' => 'required|numeric',
            'current' => 'required|numeric',
            'target' => 'required|numeric',
            'progress' => 'required|numeric',
            'lessons' => 'nullable|string',
            'notes' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        // Find the response by ID
        $response = Response::findOrFail($id);

        // Fetch the indicator to get its status
        $indicator = Indicator::findOrFail($request->indicator_id);
        $status = $indicator->status; // Assuming the Indicator model has a 'status' field

        // Get current user and their organization
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $user_id = $currentUser->id;

        // Add organisation_id, user_id, and status to the validated data
        $validated['organisation_id'] = $organisation_id;
        $validated['user_id'] = $user_id;
        $validated['status'] = $status; // Include the status of the indicator

        // Update the response with the validated data including status
        $response->update($validated);

        return response()->json([
            'message' => 'Response updated successfully!',
        ], 200);
    }


    public function getResponsesForIndicator($id)
    {
        $indicatorId = $id;
        $pageTitle = "Indicator responses";

        // Fetch the responses, ordering by created_at in descending order to get the latest first
        $responses = Response::with(['indicator', 'user'])
            ->where('indicator_id', $id)
            ->orderBy('created_at') // Order by created_at for latest responses first
            ->get();

        // Initialize a variable to store row numbers for each user
        $userRowNumbers = [];

        // Map through responses to set response tags based on the row number per user
        $responses = $responses->map(function ($response) use (&$userRowNumbers) {
            $userId = $response->user_id;

            // Initialize row number for a new user or increment for an existing one
            if (!isset($userRowNumbers[$userId])) {
                $userRowNumbers[$userId] = 1;
            } else {
                $userRowNumbers[$userId]++;
            }

            // Set response tag and response tag label
            $response->response_tag = $userRowNumbers[$userId];
            $response->response_tag_label = 'Response ' . $userRowNumbers[$userId];

            return $response;
        });

        return view('indicators.responses.list', compact('pageTitle', 'responses', 'indicatorId'));
    }


    public function deleteResponse($id)
    {
        $response = Response::find($id);

        if (!$response) {
            return redirect()->back()->with('error', 'Response not found');
        }

        $response->delete(); // This will soft delete the record

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Response deleted successfully');
    }
}
