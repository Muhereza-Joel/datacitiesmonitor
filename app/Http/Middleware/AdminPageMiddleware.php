<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['error' => 'You must be logged in to access this resource.']);
        }

        // Check if the user has one of the required roles
        if (!str_starts_with(Auth::user()->organisation->name, 'Administrator')) {
            // Return a custom view for unauthorized access (e.g., 403 error page)
            return response()->view('errors.403', ['message' => 'You do not have permission to access this resource.']);
        }

        return $next($request);
    }


}
