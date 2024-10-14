<?php

namespace App\Http\Controllers;

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

        // Add organisation_id and user_id to the validated data
        $validated['organisation_id'] = $organisation_id;
        $validated['user_id'] = $user_id;

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

        // Check if the user is authorized to update this response (optional)
        // $this->authorize('update', $response);

        // Update the response with the validated data
        $response->update($validated);

        return response()->json([
            'message' => 'Response updated successfully!',
        ], 200);
    }

    public function getResponsesForIndicator($id)
    {
        $indicatorId = $id;
        $pageTitle = "Indicator responses";
        // Fetch the responses along with related indicator and user (from the users table)
        $responses = Response::with(['indicator', 'user'])
            ->where('indicator_id', $id)
            ->orderBy('user_id')  // First order by user
            ->orderBy('created_at')  // Then order by created_at for each user
            ->get();

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
