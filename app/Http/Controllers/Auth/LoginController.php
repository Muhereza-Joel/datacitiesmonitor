<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

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
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine if the identifier is an email or a name
        $field = filter_var($request->input('identifier'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Attempt to log the user in
        if (Auth::attempt([$field => $request->input('identifier'), 'password' => $request->input('password')])) {
            $user = Auth::user();

            // Check if user has preferences and two-factor authentication is enabled
            $preferences = isset($user->preferences) ? json_decode($user->preferences->preferences, true) : [];

            if (!empty($preferences) && ($preferences['two_factor_auth'] ?? 'false') === "true") {
                if (($preferences['auth_method'] ?? '') === "security_question" && !empty($preferences['security_question']) && !empty($preferences['security_question_answer'])) {

                    // Store the security question and expected answer in session
                    session([
                        'security_question' => $preferences['security_question'],
                        'expected_answer' => $preferences['security_question_answer'],
                        'pending_2fa' => true, // Flag to indicate pending 2FA verification
                        'user_id' => $user->id, // Store the user ID for re-login after verification
                    ]);

                    // Ensure session data is retained during redirection
                    session()->reflash();

                    Auth::logout();

                    // Redirect to the 2FA security question page
                    return redirect()->route('verify.security_question');
                }
            }

            // If 2FA is not required, proceed with storing user session data
            $organization = $user->organization;
            $profile = $user->profile;
            session(['organization' => $organization, 'profile' => $profile]);

            // Store other organizations in session
            $otherOrganizations = Organisation::where('id', '!=', $user->organisation->id)->get();
            session(['other_organizations' => $otherOrganizations]);

            // Dispatch login event
            event(new UserLoggedIn($user));

            return redirect()->intended($this->redirectTo);
        }

        // If authentication fails, redirect back with an error
        return back()->withInput($request->only('identifier'))
            ->withErrors(['identifier' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user
        if ($user) {
            event(new UserLoggedOut($user));
        }

        Auth::logout();

        // Optionally, invalidate the session or redirect
        return redirect('/'); // Redirect to your desired location after logout
    }

    public function verifySecurityQuestion()
    {
        if (!session('pending_2fa')) {
            return redirect()->route('login');
        }

        // Check if the security question is set in the session
        $securityQuestion = session('security_question');

        // If the security question is empty, proceed to re-login the user
        if (empty($securityQuestion)) {
            Auth::loginUsingId(session('user_id'));

            // Clear 2FA session data as it's not needed
            session()->forget(['security_question', 'expected_answer', 'pending_2fa']);

            // Redirect to the dashboard
            return redirect('/dashboard');
        }

        try {
            // Attempt to decrypt the security question
            $decryptedSecurityQuestion = Crypt::decryptString($securityQuestion);
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Decryption failed for security question: ' . $e->getMessage());

            // Optionally, clear the session and redirect back to login or show an error
            session()->forget(['security_question', 'expected_answer', 'pending_2fa']);
            return redirect()->route('login')->withErrors(['error' => 'Security question decryption failed. Please try again.']);
        }

        // Ensure session data is retained during redirection
        session()->reflash();

        return view('auth.security_question', ['question' => $decryptedSecurityQuestion]);
    }

    public function checkSecurityQuestionAnswer(Request $request)
    {
        $this->validate($request, [
            'answer' => 'required|string',
        ]);

        // Decrypt the expected answer from the session
        $expectedAnswer = Crypt::decryptString(session('expected_answer'));

        if ($expectedAnswer === $request->input('answer')) {
            // Clear 2FA session data after successful verification
            session()->forget(['security_question', 'expected_answer', 'pending_2fa']);

            // Re-login the user
            Auth::loginUsingId(session('user_id'));

            // Retrieve the authenticated user object
            $user = Auth::user();

            // Dispatch the event with the user object
            event(new UserLoggedIn($user));

            // Redirect to intended page
            return redirect("/dashboard");
        }

        return back()->withErrors(['errors' => 'Security Check Failed. Please try again.']);
    }
    
}
