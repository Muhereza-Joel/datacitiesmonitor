@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');


<main id="main" class="main">

    <div class="pagetitle">
        <h1>Create New Monitor User.</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Create User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Display validation errors -->
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <section class="section dashboard">
        <div class="row g-1">
            <div class="col-sm-5">
                <div class="card p-2">
                    <div class="card-title">Create New Organisation User</div>

                    <div class="card-body">
                        <div id="invalid-registration" class="alert alert-danger alert-dismissible fade d-none p-1" role="alert">
                            <span class="text-center"></span>
                        </div>
                        <form id="registration-form" action="{{ route('users.store')}}" method="post" class="row g-3 needs-validation" novalidate>
                            @csrf

                            <div class="col-12">
                                <label for="yourEmail" class="form-label">Email Address of User</label><br>
                                <small class="text-success">Note that the email should be valid, because its used during password resets.</small>
                                <input value="{{ old('email') }}" type="email" name="email" class="form-control" id="yourEmail" required placeholder="Please Enter Email adddress Here">
                                <div class="invalid-feedback">This value is required</div>
                            </div>

                            <div class="col-12">
                                <label for="yourUsername" class="form-label">Username</label><br>
                                <small class="text-success">Note that the username can also be used during logins.</small>
                                <div class="input-group has-validation">
                                    <input value="{{ old('name') }}" type="text" name="name" class="form-control" id="yourUsername" required placeholder="Enter a login username here">
                                    <div class="invalid-feedback">This value is required.</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="yourPassword" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="yourPassword" required placeholder="Enter your password here">
                                <div class="invalid-feedback">This value is required.</div>
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="">Select user role</option>
                                    @if(Auth::user()->role === 'root')
                                    <option value="root" {{ old('role') == 'root' ? 'selected' : '' }}>Super User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    @endif
                                    <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                <div class="invalid-feedback">This value is required</div>
                            </div>

                            <div class="form-group">
                                <label for="organisation">Organization</label>
                                <select name="organisation_id" id="organisation" class="form-control" required>
                                    <option value="">Select organisation</option>

                                    @foreach($organisations as $row)
                                    @if($row->active == 'true')
                                    <option value="{{ $row->id }}" {{ old('organisation_id') == $row->id ? 'selected' : '' }}>{{ $row['name'] }}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">This value is required</div>
                            </div>


                            <div class="col-12">
                                <button id="submit-button" class="btn btn-primary btn-sm" type="submit">Create User</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card p-2">
                    <div class="card-title">User Roles Explanation</div>
                    <div class="card-body">
                        <h5 class="card-title">Administrator</h5>
                        <p class="card-text">Administrators can add and manage indicators, manage responses, manage user accounts and roles, and have full access to all data and system settings.</p>

                        <h5 class="card-title">Users</h5>
                        <p class="card-text">Users can create indicators and responses as well, view all data (indicators, responses), modify there responses but cannot delete any data.</p>

                        <h5 class="card-title">Viewers</h5>
                        <p class="card-text">Viewers can view all data (indicators, responses, user profiles) but cannot add, modify, or delete any data.</p>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {

    })
</script>