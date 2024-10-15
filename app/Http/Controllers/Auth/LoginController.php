<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'name'; // Default username field
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function login(Request $request)
    {
        // Validate the incoming request for either name or email
        $this->validate($request, [
            'identifier' => 'required|string', // Use a single field called 'identifier'
            'password' => 'required|string',
        ]);

        $field = filter_var($request->input('identifier'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Attempt to log the user in
        if (Auth::attempt([$field => $request->input('identifier'), 'password' => $request->input('password')])) {
          
            $user = Auth::user();

            $organization = $user->organization; // Assuming a relationship like `belongsTo` exists in User model
            $profile = $user->profile; // Assuming a relationship like `belongsTo` exists in User model
            session(['organization' => $organization]);
            session(['profile' => $profile]);

       
            $otherOrganizations = Organisation::where('id', '!=', $user->organisation->id)->get();
            session(['other_organizations' => $otherOrganizations]);

          
            return redirect()->intended($this->redirectTo);
        }

        // If authentication fails, redirect back with an error
        return back()->withInput($request->only('identifier'))
            ->withErrors(['identifier' => 'The provided credentials do not match our records.']);
    }
}
