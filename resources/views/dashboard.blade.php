@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<main id="main" class="main">

    <section class="section dashboard mt-3">

        <div class="alert alert-warning mt-2 px-3 py-1">
            <h5>Welcome back, {{ Auth::User()->name }}</h5>
            <h6 class="fw-bold">| modifying data is allowed to only members of this organisation. Non-members can only view data.</h6>
            <hr>
           
            <h2>Your are a member of  {{ $myOrganisation->name }}</h2>

        </div>

        @if(Auth::User()->role == 'viewer')
        <div class="alert alert-danger p-2">
            {{Auth::User()->name}}, your permissions only allow you to view data. If you need to modify or delete any information, please contact the Administrator to update your permissions.
        </div>
        @endif

        <div class="row g-1 mt-4">

            <div class="col-md-3">
                <div class="row g-1">
                    <div class="">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">All <span>| Users under {{ $myOrganisation->name }}</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$usersCount}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="row g-1">
                    <div class="">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">All <span>| ToCs for {{ $myOrganisation->name }}</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$tocCount}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="row g-1">
                    <div class="">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">All <span>| Indicators for {{ $myOrganisation->name }}</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$indicatorCount}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="row g-1">
                    <div class="">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">All <span>| Responses for {{ $myOrganisation->name }}</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{$responseCount}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </section>
</main>

@include('layouts.footer')