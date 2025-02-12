@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<main id="main" class="main">

    <section class="section dashboard mt-3">
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

        <?php
        // Get the current hour
        $hour = now()->format('H');

        // Determine the time of day and set the appropriate greeting
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }
        ?>

        <div class="alert alert-warning p-1">
            <h5>{{ $greeting }}, {{ Auth::User()->name }}. Your account is registered under <span class="badge bg-dark">{{ $myOrganisation->name }}</span> as your organisation</h5>
        </div>


        @if(session('user.preferences.two_factor_auth') === "true" && empty(session('user.preferences.security_question')))
        <div class="alert alert-danger">
            <strong>Attention, {{ Auth::user()->name }}!</strong>
            <p>
                Two-factor authentication is enabled on your account, but you haven't set up a security question and answer yet.
                Please complete this setup to enhance the security of your account.
            </p>
            <a href="{{ route('preferences.show') }}" class="btn btn-primary btn-sm mt-2">
                Set Up Security Question
            </a>
        </div>
        @elseif(session('user.preferences.two_factor_auth') !== "true")
        <div class="alert alert-info">
            <strong>Enhance Your Account Security! {{ Auth::user()->name }}</strong>
            <p class="m-0">
                Two-factor authentication is currently disabled on your account. Enable it to add an extra layer of protection.
            </p>
            <small class="mb-2">
                With two-factor authentication, even if someone knows your password, they won’t be able to access your account without the additional verification step.
            </small><br>
            <a href="{{ route('preferences.show') }}" class="btn btn-secondary btn-sm mt-2">
                Enable Two-Factor Authentication
            </a>
        </div>
        @endif


        @if(Auth::User()->role == 'viewer')
        <div class="alert alert-danger p-2">
            {{Auth::User()->name}}, your permissions only allow you to view data. If you need to modify or delete any information, please contact the Administrator to update your permissions.
        </div>
        @endif

        <div class="row g-1">

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
        </div>

        <div class="row g-1 mt-4">
            <div class="col-sm-6">
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">{{Auth::user()->name }}, here is your recent activities log</h5>

                        <div class="activity">
                            <!-- Recent Indicators Visited -->
                            <div class="card p-4">
                                <h6>
                                    You Recently Visited {{ $recentActivities['recentIndicators']->count() }}
                                    Indicator{{ $recentActivities['recentIndicators']->count() !== 1 ? 's' : '' }}
                                </h6>

                                @forelse($recentActivities['recentIndicators'] as $indicator)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $indicator->created_at->diffForHumans() }}</div>
                                    <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                                    <div class="activity-content py-3">
                                        Visited Indicator: <a href="{{ route('indicators.show', $indicator->indicator_id) }}" class="fw-bold text-primary">{{ $indicator->indicator_title }}</a>
                                    </div>
                                </div>
                                @empty
                                <div class="activity-item d-flex">
                                    <div class="activity-content"><span class="badge bg-info">No recent indicators visited.</span></div>
                                </div>
                                @endforelse

                            </div>

                            <!-- Recent ToCs Visited -->
                            <div class="card p-4 my-2">
                                <h6>
                                    You Recently Visited {{ $recentActivities['recentToCs']->count() }}
                                    Theor{{ $recentActivities['recentToCs']->count() !== 1 ? 'ies' : 'y' }} of Change
                                </h6>

                                @forelse($recentActivities['recentToCs'] as $toc)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $toc->created_at->diffForHumans() }}</div>
                                    <i class="bi bi-circle-fill activity-badge text-primary align-self-start"></i>
                                    <div class="activity-content">
                                        Visited ToC: <a href="{{ route('theory.index') }}" class="fw-bold text-primary">{{ $toc->toc_title }}</a>
                                    </div>
                                </div>
                                @empty
                                <div class="activity-item d-flex">
                                    <div class="activity-content"><span class="badge bg-info">No recent ToCs visited.</span></div>
                                </div>
                                @endforelse

                            </div>

                            <!-- Recent Responses Visited -->
                            <!-- <div class="card p-4 my-2">
                                <h5>Recent Responses Visited</h5>
                                @forelse($recentActivities['recentResponses'] as $response)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $response->created_at->diffForHumans() }}</div>
                                    <i class="bi bi-circle-fill activity-badge text-info align-self-start"></i>
                                    <div class="activity-content">
                                        Visited Response: <a href="#" class="fw-bold text-dark">{{ $response->resource_id }}</a>
                                    </div>
                                </div>
                                @empty
                                <div class="activity-item d-flex">
                                    <div class="activity-content"><span class="badge bg-info">No recent responses visited.</span></div>
                                </div>
                                @endforelse

                            </div> -->

                            <!-- Last Logins -->
                            <div class="card p-4 my-2">
                                <h6>Last Logins</h6>
                                @forelse($recentActivities['lastLogins'] as $login)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $login->created_at->diffForHumans() }}</div>
                                    <i class="bi bi-circle-fill activity-badge text-muted align-self-start"></i>
                                    <div class="activity-content">
                                        Login from IP: {{ $login->ip_address }}
                                    </div>
                                </div>
                                @empty
                                <div class="activity-item d-flex">
                                    <div class="activity-content">No login history available.</div>
                                </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
</main>

@include('layouts.footer')