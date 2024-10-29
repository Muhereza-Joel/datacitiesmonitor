<?php

namespace App\Http\Controllers;

use App\Models\UserActionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $pageTitle = "User Logs";
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        // Determine the date range based on the filter
        switch ($filter) {
            case 'last_three_days':
                $startDate = Carbon::now()->subDays(3)->startOfDay();
                break;
            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfDay();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfDay();
                break;
            default: // 'today'
                $startDate = Carbon::now()->startOfDay();
                break;
        }

        // Start the query for logs
        $query = UserActionLog::with(['user', 'user.profile']) // Eager load the user and their profile
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc');

        // Check if the current user is a root user
        if ($currentUser->role === 'root') {
            // If the user is root, do not filter by organization
        } else {
            // Filter by organization for non-root users
            $query->whereHas('user', function ($query) use ($organisation_id) {
                $query->where('organisation_id', $organisation_id);
            });
        }

        // Execute the query and paginate or get results as needed
        $logs = $query->paginate(25); // Example pagination

        // Paginate the results
        $logs = $query->paginate(24);

        return view('logs.list', compact('pageTitle', 'logs', 'filter'));
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
        //
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
        //
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
        //
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
}
