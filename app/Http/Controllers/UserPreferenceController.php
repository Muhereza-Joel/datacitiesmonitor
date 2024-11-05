<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UserPreferenceController extends Controller
{
    public function updatePreference(Request $request)
    {
        $request->validate([
            'preferences' => 'required|array',
            'preferences.*' => 'required|string', // Each preference key should be a string
        ]);

        $user = Auth::user();
        $preferences = $user->preferences()->firstOrCreate([]);

        // Decode preferences JSON to an array if it's not already an array
        $preferencesData = is_array($preferences->preferences) ? $preferences->preferences : json_decode($preferences->preferences, true);
        $preferencesData = $preferencesData ?? []; // Ensure it's an array

        // Update each preference key-value pair in the request
        foreach ($request->preferences as $key => $value) {
            if ($key === 'security_question' || $key === 'security_question_answer') {
                // Encrypt the security question and answer
                $preferencesData[$key] = Crypt::encryptString($value);
            } else {
                $preferencesData[$key] = $value;
            }
        }

        // Encode the preferences back to JSON before saving
        $preferences->preferences = json_encode($preferencesData);
        $preferences->save();

        // Update session with the new preferences
        session(['user.preferences' => $preferencesData]);

        return response()->json(['status' => 'success']);
    }



    public function showPreferences()
    {
        $pageTitle = 'System Settings';
        return view('preferences.index', compact('pageTitle'));
    }
}
