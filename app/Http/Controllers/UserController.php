<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Users';
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        // Check if the current user is a root user
        if ($currentUser->role === 'root') {
            // Fetch all users with organisation and profile if the role is root
            $users = User::with(['organisation', 'profile'])->paginate(25);
        } else {
            // Load users with organisation and profile for the current user's organization
            $users = User::with(['organisation', 'profile'])
                ->where('organisation_id', $organisation_id)
                ->paginate(25);
        }

        return view('users.list', compact('pageTitle', 'users'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create User';
        $currentUser = Auth::user(); // Get the currently authenticated user
        $organisation_id = $currentUser->organisation_id; // Get the organization ID associated with the user

        // Find the organization using the organization ID
        $myOrganisation = Organisation::findOrFail($organisation_id);

        return view('users.create', compact('pageTitle', 'myOrganisation'));
    }


    public function create_organisation_user()
    {
        $pageTitle = 'Create User';
        $organisations = Organisation::all();

        return view('users.createOrganisationUser', compact('pageTitle', 'organisations'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|required|max:30|unique:users,name',
            'email' => 'string|required|max:50|unique:users,email',
            'role' => 'required|string|in:admin,user,viewer',
            'password' => 'string|required|min:8', // Ensure the password is at least 8 characters
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        // Hash the password before storing it
        $validated['password'] = bcrypt($validated['password']);

        // Create the user with the validated data
        User::create($validated);

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        return redirect()->back()
            ->with(['success' => 'User Created Successfully', 'myOrganisation' => $myOrganisation]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = "User Details";
        $userDetails = User::findOrFail($id);
        return view('users.view', compact('pageTitle', 'userDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Soft delete the user

        return response()->json(['message' => 'User deleted successfully.']);
    }


    public function updateRole(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'role' => 'required|string|in:root,admin,user,viewer', // Validate the role
        ]);

        // Find the user by ID
        $user = User::findOrFail($id); // This will throw a 404 if not found

        // Update the user's role
        $user->role = $validated['role'];
        $user->save(); // Save the changes

        // Return a success response
        return response()->json(['message' => 'User role updated successfully.']);
    }

    public function updateEmail(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $id, // Validate the email, ensuring it's unique except for the current user
        ]);

        // Find the user by ID
        $user = User::findOrFail($id); // This will throw a 404 if not found

        // Update the user's email
        $user->email = $validated['email'];
        $user->save(); // Save the changes

        // Return a success response
        return response()->json(['message' => 'User email updated successfully.']);
    }
}
