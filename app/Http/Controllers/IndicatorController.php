<?php

namespace App\Http\Controllers;

use App\Events\UserActionPerformed;
use App\Exports\IndicatorsExport;
use App\Exports\ResponseExport;
use App\Exports\SingleIndicatorExport;
use App\Jobs\IndexIndicatorJob;
use App\Models\Archive;
use App\Models\Indicator;
use App\Models\Organisation;
use App\Models\Response;
use App\Models\TheoryOfChange;
use Barryvdh\DomPDF\PDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Jfcherng\Diff\DiffHelper;
use Maatwebsite\Excel\Facades\Excel;
use Venturecraft\Revisionable\Revision;
use ZipArchive;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageTitle = "All Indicators";
        $currentUser = Auth::user();

        // Start the query with the base conditions
        $query = Indicator::with(['theoryOfChange', 'organisation'])
            ->withCount('responses'); // Add response count

        // Check if the current user is a root user
        if ($currentUser->role !== 'root') {
            // Filter by organization for non-root users
            $organisation_id = $currentUser->organisation_id;
            $query->where('organisation_id', $organisation_id);
        }

        // Apply filters if they are present in the request
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('qualitative_progress')) {
            $query->where('qualitative_progress', $request->input('qualitative_progress'));
        }

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->input('category') . '%');
        }

        // Calculate the counts for all indicators before pagination
        $indicatorCounts = [
            'total' => $query->count(),
            'draft' => (clone $query)->where('status', 'draft')->count(),
            'review' => (clone $query)->where('status', 'review')->count(),
            'public' => (clone $query)->where('status', 'public')->count(),
            'archived' => (clone $query)->where('status', 'archived')->count(),
        ];

        // Order the results: those with at least one response first, then by created_at
        $query->orderByRaw('CASE WHEN responses_count > 0 THEN 0 ELSE 1 END, created_at DESC');

        // Paginate the filtered results
        $indicators = $query->paginate(24);

        // Transform the collection to add the 'current' value from the latest response
        $indicators->getCollection()->transform(function ($indicator) {
            // Fetch the latest response once
            $latestResponse = $indicator->responses()->orderBy('created_at', 'desc')->first();

            // Set the 'current' value from the latest response
            $indicator->current = $latestResponse ? $latestResponse->current : null;

            // Set the 'latest_response_date' from the latest response's created_at
            $indicator->latest_response_date = $latestResponse ? $latestResponse->created_at : null;

            return $indicator;
        });



        return view('indicators.list', compact('pageTitle', 'indicators', 'indicatorCounts'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Create Indicators";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        return view('indicators.create', compact('pageTitle', 'myOrganisation', 'theories'));
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
            'category' => 'string|required',
            'name' => 'string|required',
            'theory_of_change_id' => 'string|required|exists:theory_of_changes,id',
            'direction' => 'string|required',
            'indicator_title' => 'string|required',
            'definition' => 'string|required',
            'baseline' => 'required|numeric',
            'target' => 'required|numeric',
            'data_source' => 'string|required',
            'frequency' => 'string|required',
            'responsible' => 'string|required',
            'reporting' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        $indicator = Indicator::create($validated);

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        event(new UserActionPerformed(Auth::user(), 'create_indicator', 'Indicator', $indicator->id));

        return redirect()->back()->with(['success' => 'Indicator Created Successfully', 'myOrganisation' => $myOrganisation, 'theories' => $theories]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = "Indicator details";
        // Use first() to get a single result
        $indicator = Indicator::with('theoryOfChange')->where('id', $id)->firstOrFail();

        event(new UserActionPerformed(Auth::user(), 'visit_indicator', 'Indicator', $id));

        return view('indicators.view', compact('pageTitle', 'indicator'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = "Update Indicator details";
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        // Start the query to find the indicator
        $query = Indicator::where('id', $id);

        // Check if the current user is a root user
        if ($currentUser->role !== 'root') {
            // If not root, ensure the indicator belongs to the current user's organisation
            $query->where('organisation_id', $organisation_id);
        }

        // Find the indicator
        $indicator = $query->firstOrFail();

        // Fetch theories of change associated with the organization
        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        return view('indicators.update', compact('pageTitle', 'myOrganisation', 'indicator', 'theories'));
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
            'category' => 'string|required',
            'name' => 'string|required',
            'theory_of_change_id' => 'string|required|exists:theory_of_changes,id',
            'direction' => 'string|required',
            'indicator_title' => 'string|required',
            'definition' => 'string|required',
            'baseline' => 'required|numeric',
            'target' => 'required|numeric',
            'data_source' => 'string|required',
            'frequency' => 'string|required',
            'responsible' => 'string|required',
            'reporting' => 'string|required',
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $indicator = Indicator::findOrFail($id);
            $indicator->update($validated);

            // Fetch related responses
            $responses = $indicator->responses;

            // Get the updated baseline and target
            $baseline = $validated['baseline'];
            $target = $validated['target'];
            $direction = $validated['direction'];

            // Recalculate progress for each response
            foreach ($responses as $response) {
                $progress = 0;

                // Calculate progress based on the formula
                if ($baseline === $target) {
                    // If baseline equals target, set progress to 100%
                    $progress = 100;
                } else {
                    if ($direction === 'increasing') {
                        // For increasing, calculate progress using (current - baseline) / (target - baseline) * 100
                        $progress = (($response->current - $baseline) / ($target - $baseline)) * 100;

                        // Ensure that the current value is between baseline and target for increasing direction
                        if ($response->current < $baseline || $response->current > $target) {
                            throw new \Exception('Current value ' . $response->current . ' is out of range for increasing direction. It must be between ' . $baseline . ' and ' . $target . '.');
                        }
                    } elseif ($direction === 'decreasing') {
                        // For decreasing, calculate progress using (baseline - current) / (baseline - target) * 100
                        $progress = (($baseline - $response->current) / ($baseline - $target)) * 100;

                        // Ensure that the current value is between target and baseline for decreasing direction
                        if ($response->current > $baseline || $response->current < $target) {
                            throw new \Exception('Current value ' . $response->current . ' is out of range for decreasing direction. It must be between ' . $target . ' and ' . $baseline . '.');
                        }
                    }
                }

                // Ensure progress is within bounds (0 - 100%)
                $progress = max(0, min(100, $progress));

                // Save the updated response with the recalculated progress
                $response->progress = $progress;
                $response->save();
            }
        });

        // Other logic for returning a response
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $theories = TheoryOfChange::with('organisation')->where('organisation_id', $organisation_id)->get();

        event(new UserActionPerformed(Auth::user(), 'update_indicator', 'Indicator', $id));

        return redirect()->back()->with(['success' => 'Indicator Updated Successfully', 'myOrganisation' => $myOrganisation, 'theories' => $theories]);
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

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'string|required|in:draft,review,public,archived',
            'qualitative_progress' => 'string|required|in:on track,at risk,off track,completed,not started',
        ]);

        // Use a transaction to ensure atomic updates
        DB::beginTransaction();

        try {
            $indicator = Indicator::findOrFail($id);
            $indicator->status = $validated['status'];
            $indicator->qualitative_progress = $validated['qualitative_progress'];
            $indicator->save();

            // Update the status of the corresponding responses
            $indicator->responses()->update(['status' => $validated['status']]);

            // Commit the transaction if everything goes well
            DB::commit();

            event(new UserActionPerformed(Auth::user(), 'update_indicator_status', 'Indicator', $indicator->id));
            return redirect()->back()->with(['success' => 'Indicator Status Updated Successfully']);
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Failed to update Indicator Status: ' . $e->getMessage()]);
        }
    }

    public function getOrganisationPublications($id)
    {
        $pageTitle = "Publications";
        $type = request('type', 'public_indicators'); // Default to 'public_indicators'

        // Fetch the organisation
        $organisation = Organisation::find($id);

        if (!$organisation) {
            return redirect()->back()->with('error', 'Organisation not found.');
        }

        // Query based on the type
        if ($type == 'archives') {
            // Use Archive model for archived items
            $items = Archive::with('organisation')
                ->where('organisation_id', $id)
                ->whereIn('access_level', ['public'])
                ->paginate(25);
            $view = 'organisations.listArchives'; // View for archives
        } else {
            // Use Indicator model for public indicators
            $items = Indicator::with('organisation')
                ->where('organisation_id', $id)
                ->where('status', 'public')
                ->withCount('responses') // Add response count
                ->paginate(25);
            $view = 'organisations.listPublicIndicators'; // View for public indicators
        }

        return view($view, compact('pageTitle', 'items', 'organisation', 'type'));
    }

    // Export all indicators with their responses
    public function exportAllWithResponses()
    {
        return Excel::download(new IndicatorsExport, 'all_indicators_with_responses.csv');
    }

    // Export a single indicator with its responses
    public function exportSingleWithResponses($id)
    {
        return Excel::download(new SingleIndicatorExport($id), 'indicator_' . $id . '_with_responses.csv');
    }

    public function exportIndicatorAndResponses($indicatorId)
    {
        // Prepare the export objects
        $indicatorExport = new SingleIndicatorExport($indicatorId);
        $responsesExport = new ResponseExport($indicatorId);

        // Define the directory and ensure it exists
        $archiveDir = storage_path("app/public/archives/");
        if (!file_exists($archiveDir)) {
            mkdir($archiveDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Create the zip file
        $zipFileName = $archiveDir . "indicator_responses_$indicatorId.zip";
        $zip = new ZipArchive();

        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            // Export indicator to a file in the 'public' disk
            $indicatorFileName = "indicator_$indicatorId.xlsx";
            Excel::store($indicatorExport, $indicatorFileName, 'public');
            $indicatorPath = storage_path("app/public/$indicatorFileName"); // Ensure correct path
            if (file_exists($indicatorPath)) {
                $zip->addFile($indicatorPath, $indicatorFileName);
            }

            // Export responses to a file in the 'public' disk
            $responsesFileName = "responses_$indicatorId.xlsx";
            Excel::store($responsesExport, $responsesFileName, 'public');
            $responsesPath = storage_path("app/public/$responsesFileName"); // Ensure correct path
            if (file_exists($responsesPath)) {
                $zip->addFile($responsesPath, $responsesFileName);
            }

            // Close the zip file after adding files
            $zip->close();

            // Return the zip file as a download
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            // Error: could not create zip file
            return response()->json(['error' => 'Could not create zip file'], 500);
        }
    }

    public function exportIndicatorAndResponsesAsCSV($indicatorId)
    {
        // Prepare the export objects
        $indicatorExport = new SingleIndicatorExport($indicatorId);
        $responsesExport = new ResponseExport($indicatorId);

        // Define the directory and ensure it exists
        $archiveDir = storage_path("app/public/archives/");
        if (!file_exists($archiveDir)) {
            mkdir($archiveDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Create the zip file
        $zipFileName = $archiveDir . "indicator_responses_$indicatorId.zip";
        $zip = new ZipArchive();

        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            // Export indicator to a CSV file in the 'public' disk
            $indicatorFileName = "indicator_$indicatorId.csv";
            Excel::store($indicatorExport, $indicatorFileName, 'public', \Maatwebsite\Excel\Excel::CSV);
            $indicatorPath = storage_path("app/public/$indicatorFileName");
            if (file_exists($indicatorPath)) {
                $zip->addFile($indicatorPath, $indicatorFileName);
            }

            // Export responses to a CSV file in the 'public' disk
            $responsesFileName = "responses_$indicatorId.csv";
            Excel::store($responsesExport, $responsesFileName, 'public', \Maatwebsite\Excel\Excel::CSV);
            $responsesPath = storage_path("app/public/$responsesFileName");
            if (file_exists($responsesPath)) {
                $zip->addFile($responsesPath, $responsesFileName);
            }

            // Close the zip file after adding files
            $zip->close();

            // Return the zip file as a download
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            // Error: could not create zip file
            return response()->json(['error' => 'Could not create zip file'], 500);
        }
    }


    public function getLineChartData($indicatorId)
    {
        // Get the indicator to fetch baseline and target values
        $indicator = Indicator::findOrFail($indicatorId);

        // Fetch responses associated with the indicator
        $responses = Response::where('indicator_id', $indicatorId)
            ->orderBy('created_at', 'asc')
            ->get(['created_at', 'current']);

        $responseCount = $responses->count();
        $labels = $responses->pluck('created_at')->map(function ($date) {
            return $date->format('Y-m-d'); // Format the date as needed
        });

        $data = $responses->pluck('current');

        // Add dummy labels if there is only one response
        if ($responseCount < 5) {
            $dummyLabelsCount = 5 - $responseCount; // Ensure a minimum of 5 labels

            for ($i = 0; $i < $dummyLabelsCount; $i++) {
                $labels->push("Label " . ($responseCount + $i + 1)); // Example dummy label
                $data->push($data->last()); // Repeat the last data value to match the dummy label
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'baseline' => $indicator->baseline, // Include baseline value
            'target' => $indicator->target      // Include target value
        ]);
    }

    public function exportIndicatorPDF($id)
    {
        // Fetch the indicator along with its responses
        $indicator = Indicator::with('responses')->findOrFail($id);

        // Generate the QR code directly to a base64 string
        $qrCode = new QrCode('https://monitor.opendata-analytics.org/indicators/' . $indicator->id); // URL or data you want to encode
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);

        // Convert to base64
        $qrCodeBase64 = base64_encode($qrCodeImage->getString());

        // Create an instance of the PDF and load the view with data
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.indicator', compact('indicator', 'qrCodeBase64'))
            ->setOption('keep-table-together', true)
            ->setPaper('A4')
            ->setOption('margin-bottom', 10)
            ->setOption('password', 'user-password') // User password
            ->setOption('permissions', 'owner-password') // Owner permissions password
            ->setOption('print-media-type', true)
            ->setOption('no-copy', true) // Disallow copying
            ->setOption('no-modify', true) // Disallow modification
            ->setOption('no-annotate', true); // Disallow annotations
        // Format the filename
        $formattedDate = now()->format('Y-m-d'); // Get the current date in the desired format
        $titleSlug = $this->slugify($indicator->indicator_title); // Use the slugify function

        // Stream or download the PDF
        return $pdf->download("indicator-{$titleSlug}-{$formattedDate}.pdf");
    }

    // Function to slugify the title
    private function slugify($text)
    {
        // Replace non-letter or digits by hyphens
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $text);

        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // Trim
        $text = trim($text, '-');

        // Remove duplicate -'s
        $text = preg_replace('~-+~', '-', $text);

        // Return the slug
        return strtolower($text);
    }

    public function getIndicatorHistory($id)
    {
        $pageTitle = "Indicator History";
        $indicator = Indicator::findOrFail($id);
        $revisions = $indicator->revisionHistory;

        // Renderer configuration for better diff visualization
        $rendererOptions = [
            'detailLevel' => 'word', // Compare at the word level
            'insertedClass' => 'text-success', // CSS class for inserted text
            'deletedClass' => 'text-danger',  // CSS class for deleted text
        ];

        // Add diffs to each revision and format keys
        foreach ($revisions as $revision) {
            if ($revision->old_value && $revision->new_value) {
                // Sanitize HTML to extract text content only
                $oldPlainText = strip_tags($revision->old_value);
                $newPlainText = strip_tags($revision->new_value);

                $revision->diffHtml = DiffHelper::calculate(
                    $oldPlainText,
                    $newPlainText,
                    'Inline',
                    $rendererOptions
                );
            } else {
                $revision->diffHtml = null;
            }

            // Format the keys for display (e.g., 'indicator_title' to 'Indicator Title')
            if ($revision->key) {
                $revision->formattedKey = $this->formatKey($revision->key);
            }
        }

        return view('indicators.revisions', compact('pageTitle', 'indicator', 'revisions'));
    }


    public function formatKey($key)
    {
        // Check if the word 'Indicator' is already in the key
        if (stripos($key, 'indicator') === false) {
            // Prefix the key with 'Indicator' if not already present
            $key = 'indicator_' . $key;
        }

        // Replace underscores with spaces
        $formatted = str_replace('_', ' ', $key);

        // Capitalize the first letter of each word
        $formatted = ucwords($formatted);

        // Special case handling (e.g., 'ID' should be 'ID' instead of 'Id')
        $formatted = str_replace('Id', 'ID', $formatted);

        return $formatted;
    }

    public function revertIndicatorHistory($id, $revisionId)
    {

        $revision = Revision::findOrFail($revisionId);

        $indicator = $revision->revisionable; // Assuming the revision is related to the Indicator model

        $indicator->update([
            $revision->key => $revision->old_value, // Update the specific field with the old value
        ]);

        return redirect()->route('indicator.history', $indicator->id)
            ->with('success', 'Indicator has been reverted successfully!');
    }

    public function moveResponse($id)
    {
        $pageTitle = "Move Response";
        $responseId = $id;

        $currentUser = Auth::user();
        // Find the related indicator for the given response ID
        $excludedIndicatorId = Response::where('id', $id)->value('indicator_id');

        // Start the query with the base conditions
        $query = Indicator::with(['theoryOfChange', 'organisation'])
            ->withCount('responses') // Add response count
            ->where('id', '!=', $excludedIndicatorId); // Exclude the related indicator

        // Check if the current user is a root user
        if ($currentUser->role !== 'root') {
            // Filter by organization for non-root users
            $organisation_id = $currentUser->organisation_id;
            $query->where('organisation_id', $organisation_id);
        }

        // Order the results: those with at least one response first, then by created_at
        $query->orderByRaw('CASE WHEN responses_count > 0 THEN 0 ELSE 1 END, created_at DESC');

        // Paginate the filtered results
        $indicators = $query->paginate(24);

        // Transform the collection to add the 'current' value from the latest response
        $indicators->getCollection()->transform(function ($indicator) {
            // Fetch the latest response once
            $latestResponse = $indicator->responses()->orderBy('created_at', 'desc')->first();

            // Set the 'current' value from the latest response
            $indicator->current = $latestResponse ? $latestResponse->current : null;

            // Set the 'latest_response_date' from the latest response's created_at
            $indicator->latest_response_date = $latestResponse ? $latestResponse->created_at : null;

            return $indicator;
        });

        return view('indicators.moveResponses', compact('pageTitle', 'indicators', 'responseId'));
    }


    public function saveMovedResponse(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'response_id' => 'required|string|exists:responses,id',
            'selected_indicator' => 'required|string|exists:indicators,id',
            'current' => 'required|numeric',
        ]);

        // Start a transaction
        DB::transaction(function () use ($validated) {
            // Fetch the response being moved
            $response = Response::findOrFail($validated['response_id']);

            // Fetch the new indicator where the response is being moved
            $newIndicator = Indicator::findOrFail($validated['selected_indicator']);

            $baseline = $newIndicator->baseline;
            $target = $newIndicator->target;
            $direction = $newIndicator->direction; // Can be 'increase' or 'decrease'
            $current = $validated['current'];

            // Validate that current is within the acceptable range
            if (($direction === 'increasing' && ($current < $baseline || $current > $target)) ||
                ($direction === 'decreasing' && ($current > $baseline || $current < $target))
            ) {
                // Redirect back with errors if validation fails
                return redirect()->back()->withErrors([
                    'current' => "The current value must be between the baseline ($baseline) and target ($target) based on the indicator direction."
                ]);
            }

            // Recalculate progress based on the new indicator's direction
            if ($direction === 'increasing') {
                $progress = ($current - $baseline) / ($target - $baseline) * 100;
            } elseif ($direction === 'decreasing') {
                $progress = ($baseline - $current) / ($baseline - $target) * 100;
            } else {
                $progress = 0; // Default in case of unexpected direction value
            }

            // Ensure progress stays within 0-100 range
            $progress = max(0, min(100, $progress));

            // Update response with new indicator_id, recalculated progress, and current value
            $response->update([
                'indicator_id' => $newIndicator->id,
                'progress' => $progress,
                'current' => $current,
            ]);

            // Update files table to reflect the new indicator_id
            DB::table('files')
                ->where('response_id', $response->id)
                ->update(['indicator_id' => $newIndicator->id]);

            // Log the action
            event(new UserActionPerformed(Auth::user(), 'move_response', 'Response', $response->id));
        });
        // Redirect to indicators.index with success message only after successful transaction
        return redirect()->route('indicators.index')->with('success', 'Response Moved Successfully');
    }
}
