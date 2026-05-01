<?php

namespace App\Http\Controllers;

use App\Models\AreaOfFocus;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaOfFocusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        // Fetch areas of focus belonging to the user's organization
        $areasOfFocus = AreaOfFocus::where('organisation_id', $currentUser->organisation_id)
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('areaoffocus.list', compact('areasOfFocus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Create Area of Focus";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $projects = $myOrganisation->projects()->orderBy('created_at', 'desc')->get();

        return view('areaoffocus.create', compact('pageTitle', 'myOrganisation', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'project_id' => 'required|exists:projects,id',
            'organisation_id' => 'required|exists:organisations,id',
        ]);

        AreaOfFocus::create($validatedData);

        return redirect()->route('areas-of-focus.index')->with('success', 'Area of Focus created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $areaOfFocus = AreaOfFocus::with(['project', 'organisation'])->findOrFail($id);
        $pageTitle = "Area of Focus Details";

        return view('areaoffocus.show', compact('areaOfFocus', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $areaOfFocus = AreaOfFocus::findOrFail($id);
        $currentUser = Auth::user();
        $myOrganisation = Organisation::findOrFail($currentUser->organisation_id);
        $projects = $myOrganisation->projects()->orderBy('created_at', 'desc')->get();
        $pageTitle = "Update Area of Focus";

        return view('areaoffocus.update', compact('areaOfFocus', 'myOrganisation', 'projects', 'pageTitle'));
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
        $areaOfFocus = AreaOfFocus::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'project_id' => 'required|exists:projects,id',
        ]);

        $areaOfFocus->update($validatedData);

        // Return JSON for the AJAX implementation used in the create view
        return response()->json([
            'status' => 'success',
            'message' => 'Area of Focus updated successfully.'
        ]);
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
