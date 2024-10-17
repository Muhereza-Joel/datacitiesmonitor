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

        // Filter the results based on the organization and eager load relationships
        $results = Indicator::with(['theoryOfChange', 'organisation', 'responses' => function ($query) {
            $query->latest()->limit(1); // Eager load the latest response
        }])
            ->withCount('responses') // Add response count
            ->whereIn('id', $searchResults->pluck('id')) // Filter results by IDs from the search
            ->where('organisation_id', Auth::user()->organisation_id) // Filter by organization
            ->paginate(24); // Adjust pagination as needed

        return view('search.results', compact('pageTitle', 'results'));
    }
}
