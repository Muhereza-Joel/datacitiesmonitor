<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        // Fetch reports using pagination to handle large datasets seamlessly
        $reports = Report::where('organisation_id', $organisation_id)
            ->with('preparedBy') // Eager load relation to prevent N+1 query performance hits
            ->orderBy('created_at', 'desc')
            ->paginate(12); // Displays 12 reports per page

        return view('reports.list', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Create Report";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        return view('reports.create', compact('pageTitle', 'myOrganisation'));
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
            'description' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'reporting_month' => 'required|date_format:Y-m-d',
            'status' => 'required|in:draft,submitted',
            'organisation_id' => 'required|exists:organisations,id',
        ]);

        $validatedData['prepared_by'] = Auth::id();

        // Create the report
        $report = Report::create($validatedData);

        return redirect()->route('reports.show', $report->id)->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Eager load everything in one database step
        $report = Report::with([
            'project',
            'organisation',
            'preparedBy',
            'reportAreas.areaOfFocus'
        ])->findOrFail($id);

        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $report = Report::findOrFail($id);
        return view('reports.update', compact('report', 'myOrganisation'));
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
        $report = Report::findOrFail($id);

        // Prevent editing submitted reports
        if ($report->status === 'submitted') {
            return response()->json([
                'message' => 'Submitted reports cannot be edited.'
            ], 422);
        }

        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'reporting_month' => 'required|date_format:Y-m-d',
            'status' => 'required|in:draft,submitted',
        ]);

        $report->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Report updated successfully.',
            'report' => $report
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
