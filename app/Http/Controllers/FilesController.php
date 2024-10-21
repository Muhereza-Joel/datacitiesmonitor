<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validated = $request->validate([
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,xls,xlsx,docx,doc|max:2048',
            'response_id' => 'required|uuid',
            'organisation_id' => 'required|uuid',
            'indicator_id' => 'required|uuid',
        ]);

        $uploadedFiles = $request->file('files');
        $savedFiles = [];

        if ($uploadedFiles) {
            // Use a transaction to ensure atomicity
            DB::beginTransaction();

            try {
                foreach ($uploadedFiles as $file) {
                    // Generate a unique filename
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $relativePath = 'uploads/files/' . $filename;

                    // Save file details to the database first
                    $savedFile = Files::create([
                        'response_id' => $validated['response_id'],
                        'indicator_id' => $validated['indicator_id'],
                        'organisation_id' => $validated['organisation_id'],
                        'name' => $filename,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'path' => $relativePath,
                        'extension' => $file->getClientOriginalExtension(),
                    ]);

                    // If create is successful, move the file to the uploads directory
                    $file->move(public_path('uploads/files'), $filename);

                    // Add the saved file to the response data
                    $savedFiles[] = $savedFile;
                }

                // Commit the transaction
                DB::commit();

                return response()->json([
                    'message' => 'Files uploaded successfully!',
                    'files' => $savedFiles
                ], 200);
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();

                // Log the error for debugging
                Log::error('File upload error: ' . $e->getMessage());

                return response()->json([
                    'message' => 'File upload failed. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // If no files are provided
        return response()->json(['message' => 'No files provided'], 400);
    }


    public function getResponseFiles($responseId)
    {
        // Validate the response_id
        if (!Uuid::isValid($responseId)) {
            return response()->json(['message' => 'Invalid response ID'], 400);
        }

        // Retrieve the files associated with the given response_id
        $files = Files::where('response_id', $responseId)->get();

        // Check if any files were found
        if ($files->isEmpty()) {
            return response()->json(['message' => 'No files found for the specified response'], 404);
        }

        // Return the files as a JSON response
        return response()->json([
            'message' => 'Files retrieved successfully',
            'files' => $files
        ], 200);
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

        $file = Files::findOrFail($id);

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Perform the soft delete
        $file->delete();

        return response()->json(['message' => 'File deleted successfully'], 200);
    }

    public function delete_forever($id)
    {
        $file = Files::find($id);

        if (!$file) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        // Construct the full path to the file on the server
        $filePath = public_path($file->path);

        // Delete the file from the storage
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the file record from the database
        $file->delete();

        // Return a success response
        return response()->json([
            'message' => 'File deleted successfully'
        ], 200);
    }
}
