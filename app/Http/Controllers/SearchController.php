<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $pageTitle = "Search Results";

        // Perform search using TNTSearch and get the initial results
        $searchResults = Indicator::search($query)->get(); // Use get() to retrieve all search results

        // Start the query for indicators
        $indicatorQuery = Indicator::with([
            'theoryOfChange',
            'organisation',
            'responses' => function ($query) {
                $query->latest()->limit(1); // Eager load the latest response
            }
        ])
            ->withCount('responses'); // Add response count

        // Check if the current user is a root user
        $currentUser = Auth::user();
        if ($currentUser->role === 'root') {
            // If root, do not filter by organization
            $indicatorQuery->whereIn('id', $searchResults->pluck('id')); // Filter results by IDs from the search
        } else {
            // For non-root users, filter by organization
            $indicatorQuery->whereIn('id', $searchResults->pluck('id')) // Filter results by IDs from the search
                ->where('organisation_id', $currentUser->organisation_id); // Filter by organization
        }

        // Order by responses count in descending order and paginate the results
        $results = $indicatorQuery->orderByDesc('responses_count')->paginate(24);

        // Map the results to include the 'current' value from the latest response
        $results->getCollection()->transform(function ($indicator) {
            $latestResponse = $indicator->responses->first();
            $indicator->current = $latestResponse ? $latestResponse->current : null; // Add latest 'current' value
            return $indicator;
        });

        return view('search.results', compact('pageTitle', 'results'));
    }
}
