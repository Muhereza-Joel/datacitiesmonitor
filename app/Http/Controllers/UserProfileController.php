<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Rules\AgeRule;
use App\Rules\NinRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'user_id' => 'required|string|max:255|exists:users,id',
            'name' => 'required|string|max:100',
            'about' => 'nullable|string',
            'company' => 'required|string',
            'job' => 'required|string',
            'nin' => ['required', 'string', 'max:14', new NinRule()],
            'email' => 'required|string|email',
            'gender' => 'required|in:male,female,other', // Replace with actual enum values
            'dob' => ['required', 'date', new AgeRule()],
            'country' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'phone' => 'required|string',
        ]);

        // Find the profile by user_id, or create a new instance if not found
        $profile = Profile::firstOrNew(['user_id' => $request->user_id]);

        // Update the profile with the validated data
        $profile->fill($validated);
        $profile->save();

        return redirect()->back()->with(['success' => 'Profile Updated Successfully']);
    }

    public function checkCurrentPassword(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'password' => 'required|string',
        ]);

        // Check if the provided password matches the authenticated user's password
        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {
            return response()->json(['exists' => true]);
        }

        return response()->json(['exists' => false]);
    }

    public function updatePassword(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8', // Ensure new password has at least 8 characters
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }
}
