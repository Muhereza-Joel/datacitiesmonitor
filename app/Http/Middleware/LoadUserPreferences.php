<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadUserPreferences
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // Get system preferences and decode each value as an array
        $systemPreferences = DB::table('system_preferences')->pluck('value', 'key')->map(function ($value) {
            return json_decode($value, true) ?? []; // Return an empty array if decoding fails
        });

        // Retrieve dark_mode preference from cookie if available
        $darkModeFromCookie = $request->cookie('dark_mode');

        if (Auth::check()) {
            // Ensure user preferences are always an array
            $userPreferences = Auth::user()->preferences ? json_decode(Auth::user()->preferences->preferences, true) : [];
            $userPreferences = is_array($userPreferences) ? $userPreferences : []; // Ensure it's an array

            // Merge user preferences with system defaults
            $preferences = array_merge($systemPreferences->toArray(), $userPreferences);

            // Store preferences in session
            session(['user.preferences' => $preferences]);

            // Save dark_mode in a cookie if set in user preferences
            if (isset($preferences['dark_mode'])) {
                cookie()->queue('dark_mode', $preferences['dark_mode'], 43200); // 30 days
            }
        } else {
            // If user is not authenticated, use system preferences or cookie value
            $preferences = $systemPreferences->toArray();

            // Override dark_mode preference with cookie if available
            if ($darkModeFromCookie !== null) {
                $preferences['dark_mode'] = $darkModeFromCookie;
            }

            // Store in session for consistency across requests
            session(['user.preferences' => $preferences]);
        }

        return $next($request);
    }
}
