<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function showProfile()
    {
        $pageTitle = "User Profile";
        $currentUser = Auth::user()->id;

        $userDetails = User::with('profile')->where('id', $currentUser)->first();

        return view('profile.show', compact('pageTitle', 'userDetails'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Change 'image_url' to 'image' to match the file input name
        ]);

        // Find the profile by user_id, or create a new instance if not found
        $profile = Profile::firstOrNew(['user_id' => $request->user_id]);

        // Check if an image file is uploaded
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');

            // Generate a new filename
            $filename = time() . '_' . $file->getClientOriginalName();
            $relativePath = 'uploads/' . $filename;

            // Move the file to the 'uploads' directory
            $file->move(public_path('uploads'), $filename);


            $profile->image_url = $relativePath;
            $profile->save();


            if (Auth::check() && Auth::user()->id == $request->user_id) {
                Auth::user()->profile->image_url = $relativePath;
            }

            return response()->json([
                'message' => 'Profile photo updated successfully!',
                'image_url' => asset($relativePath),
            ], 200);
        }

        // Handle the case where no image is uploaded
        return response()->json(['error' => 'No image file provided.'], 400);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|max:100',
            'about' => 'string',
            'about' => 'string',
        ]);
    }
}
