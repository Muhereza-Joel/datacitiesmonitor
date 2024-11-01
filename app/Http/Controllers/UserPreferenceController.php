<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    public function updatePreference(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required',
        ]);

        $user = Auth::user();
        $preferences = $user->preferences()->firstOrCreate([]);

        // Decode preferences JSON to an array if it's not already an array
        $preferencesData = is_array($preferences->preferences) ? $preferences->preferences : json_decode($preferences->preferences, true);
        $preferencesData = $preferencesData ?? []; // Ensure it's an array

        // Update the preference
        $preferencesData[$request->key] = $request->value;

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
