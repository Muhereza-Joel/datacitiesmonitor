<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\Organisation;
use App\Models\Response;
use App\Models\TheoryOfChange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pageTitle = 'Dashboard';
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        $myOrganisation = $myOrganisation = Organisation::findOrFail($organisation_id);
        $indicatorCount = Indicator::where('organisation_id', $organisation_id)->count();
        $usersCount = User::where('organisation_id', $organisation_id)->count();
        $tocCount = TheoryOfChange::where('organisation_id', $organisation_id)->count();

        $responseCount = Response::whereIn('indicator_id', function ($query) use ($organisation_id) {
            $query->select('id')->from('indicators')->where('organisation_id', $organisation_id);
        })->count();

        return view('dashboard', compact('pageTitle','myOrganisation', 'indicatorCount', 'responseCount', 'usersCount', 'tocCount'));
    }
}
