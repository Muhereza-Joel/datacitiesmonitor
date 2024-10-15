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

        // Fetch logs filtered by the organisation_id through the users table and the specified date range
        $logs = UserActionLog::with('user')
            ->whereHas('user', function ($query) use ($organisation_id) {
                $query->where('organisation_id', $organisation_id);
            })
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->paginate(24);

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
