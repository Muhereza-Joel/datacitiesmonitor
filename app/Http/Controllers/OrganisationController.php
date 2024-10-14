<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "Organisations";
        $organisations = Organisation::all();
        return view('organisations.list', compact('pageTitle', 'organisations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Create Organisation";
        $organisations = Organisation::all();
        return view('organisations.create', compact('pageTitle', 'organisations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image type and size
        ]);

        $organization = new Organisation();
        $organization->name = $request->name;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();
            $relativePath = 'uploads/' . $filename;
            $file->move(public_path('uploads'), $filename);
            $organization->logo = $relativePath;

            $organization->save();

            return response()->json([
                'message' => 'Organization created successfully!',
                'logo_url' => asset($relativePath)
            ], 200);
        }

        // If no image is provided
        return response()->json(['message' => 'No image provided'], 400);
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
        $pageTitle = "Update Organisation";
        $organisation = Organisation::findOrFail($id);
        return view('organisations.update', compact('pageTitle', 'organisation'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image type and size
        ]);

        $organization = Organisation::findOrFail($id);

        $organization->name = $request->input('name');

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $relativePath = 'uploads/' . $filename;

            $file->move(public_path('uploads'), $filename);

            if ($organization->logo && file_exists(public_path($organization->logo))) {
                unlink(public_path($organization->logo)); // Delete the old logo file
            }

            $organization->logo = $relativePath;
        }

        $organization->save();

        return response()->json([
            'message' => 'Organization updated successfully!',
            'organization' => $organization
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $organisation = Organisation::find($id);

        if (!$organisation) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $organisation->delete(); // This will soft delete the record

        return redirect()->back()->with('success', 'Organization deleted successfully');
    }

    public function restore($id)
    {
        $organisation = Organisation::withTrashed()->find($id); // Include soft-deleted records

        if (!$organisation) {
            return redirect()->back()->with('success', 'Organization not found');
        }

        $organisation->restore(); // Restore the soft-deleted record

        return redirect()->back()->with('success', 'Organization restored successfully');
    }
}
