<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportArea;
use App\Models\AreaOfFocus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportareas.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $reportId
     * @return \Illuminate\Http\Response
     */
    public function create(string $reportId)
    {
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        // Secure parent matching context verification
        $report = Report::where('organisation_id', $organisation_id)->findOrFail($reportId);

        $areasOfFocus = AreaOfFocus::where('organisation_id', $organisation_id)
            ->where('project_id', $report->project_id)
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->orderBy('name', 'asc')
            ->get();

        return view('reportareas.create', compact('report', 'areasOfFocus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $reportId)
    {
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        // Secure parent matching verification check across organization context boundary lines
        $report = Report::where('organisation_id', $organisation_id)->findOrFail($reportId);

        // Run validation against fields matching ReportArea's structural constraints
        $validated = $request->validate([
            'area_of_focus_id'     => 'required|uuid|exists:area_of_foci,id',
            'objective'            => 'required|string',
            'status'               => 'nullable|string|in:pending,ongoing,completed,delayed',
            'activities_conducted' => 'nullable|string',
            'achievements'         => 'nullable|string',
            'challenges'           => 'nullable|string',
            'risks'                => 'nullable|string',
            'opportunities'        => 'nullable|string',
            'action_plans'         => 'nullable|string',
            'lessons_learned'      => 'nullable|string',
            'recommendations'      => 'nullable|string',
            'stakeholder_feedback' => 'nullable|string',
        ]);

        // Inject framework-scoped tenancy values to secure the database payload write operation
        $validated['report_id']       = $report->id;
        $validated['project_id']      = $report->project_id; // Set structural project link from parent report record
        $validated['organisation_id'] = $organisation_id;

        // Instantiate database records; UUID injection kicks off via standard Model Booting closures automatically
        $reportArea = ReportArea::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Operational focus metrics block successfully appended to report.',
            'data'    => $reportArea
        ], 201);
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
