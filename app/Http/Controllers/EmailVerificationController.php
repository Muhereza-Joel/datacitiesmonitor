<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function sendVerification($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Check if the user has already verified their email
        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('success', 'Your email is already verified.');
        }

        // Trigger the email verification
        event(new Registered($user));

        return redirect('/dashboard')->with('success', 'Verification email sent!');
    }
}
