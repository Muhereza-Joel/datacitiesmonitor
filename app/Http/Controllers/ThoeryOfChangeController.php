<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\Organisation;
use App\Models\TheoryOfChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $organisation_id = $currentUser->organisation_id;
        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

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

        TheoryOfChange::create($validated);

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

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

        return redirect()->back()->with(['success' => 'ToC deleted successfully.', 'myOrganisation' => $myOrganisation]);
    }

    public function getIndicators($id)
    {
        $pageTitle = "All Indicators linked to ToC";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $indicators = Indicator::with('theoryOfChange')->where('theory_of_change_id', $id)->where('organisation_id', $organisation_id)->paginate(25);

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

        return view('theory.createIndicatorForToC', compact('pageTitle', 'myOrganisation', 'theories', 'toc_id'));
    }
}
