@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>User Information</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-3">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Add New Organisation User</a>
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Add New User</a>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row g-1">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ isset($userDetails->image_url) ? asset($userDetails->image_url) : asset('assets/img/avatar.png') }}" alt="Profile" class="rounded-circle" width="350px" height="350px">


                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>


                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview p-3" id="profile-overview">
                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">About</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['about'] ?? 'N/A'}}</div>
                                </div>

                                <h5 class="card-title fw-bold text-dark">Biography</h5>

                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Full Name</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['name'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Date of Birth</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['dob'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Gender</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['gender'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Company</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['company'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Job</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['job'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">NIN Number</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['nin'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Email</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['email'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Country</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['country'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">District</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['district'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Village</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['village'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row my-4">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-dark">Phone</div>
                                    <div class="col-lg-9 col-md-8 text-dark">{{$userDetails['phone'] ?? 'N/A'}}</div>
                                </div>

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')