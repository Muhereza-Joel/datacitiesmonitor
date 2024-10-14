<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $pageTitle = "Search Results";
        // Perform search using TNTSearch
        $results = Indicator::search($query)->paginate();
        
        return view('search.results', compact('pageTitle','results'));
    }
}
