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

        <div class="alert alert-warning">
            <h5>Welcome back, {{ Auth::User()->name }}, {{ $myOrganisation->name }} is your organisation</h5>
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

<script>
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        const value = "; " + document.cookie;
        const parts = value.split("; " + name + "=");
        if (parts.length === 2) {
            return parts.pop().split(";").shift();
        }
        return null;
    }

    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            classes: 'shadow-md bg-purple-dark text-white rounded', // Add custom classes for theme
            scrollTo: false,
            arrow: true, // Show an arrow pointing to the target element
            popperOptions: {
                modifiers: [{
                    name: 'offset',
                    options: {
                        offset: [0, 12], // Adjusts the position of the popover
                    },
                }, ],
            },
        }
    });

    tour.addStep({
        id: 'step-0',
        text: '<h5><strong>{{ Auth::user()->name }}, the M&E Monitor has been enhanced with new features.</strong><hr></h5><h3>Lets take a tour of the newly added sections.</h3>',
        buttons: [{
            text: 'No, I already no',
            action: tour.cancel,
            classes: 'shepherd-button-secondary',
        }, {
            text: 'Yeah, Lets Start',
            action: tour.next,
        }],
    });

    tour.addStep({
        id: 'step-1',
        text: 'Theories of Change (ToC) <hr>Monitor now includes Theories of Change, allowing you to manage them in this section and link them to your indicators.',
        attachTo: {
            element: 'li.dashboard-tour-step-1', // Target the element you want to highlight
            on: 'left', // Position the popover on top of the target element
        },
        buttons: [{
            text: 'Back',
            action: tour.back,
            classes: 'shepherd-button-secondary',
        }, {
            text: 'Next',
            action: tour.next,
        }],
    });

    tour.addStep({
        id: 'step-2',
        text: 'Archives Section <hr>Monitor now features an Archives section, enabling you to create archives that store your indicators. Once archived, these indicators serve as a centralized repository for your archived data.',
        attachTo: {
            element: 'li.dashboard-tour-step-2', // Target the element you want to highlight
            on: 'left', // Position the popover on top of the target element
        },
        buttons: [{
            text: 'Back',
            action: tour.back,
            classes: 'shepherd-button-secondary',
        }, {
            text: 'Next',
            action: tour.next,
        }],
    });

    tour.addStep({
        id: 'step-3',
        text: 'User Activity Section <hr>Monitor now allows you to track user interactions within the M&E System, providing you with real-time insights from this section.',
        attachTo: {
            element: 'li.dashboard-tour-step-3', // Target the element you want to highlight
            on: 'left', // Position the popover on top of the target element
        },
        buttons: [{
            text: 'Back',
            action: tour.back,
            classes: 'shepherd-button-secondary',
        }, {
            text: 'Next',
            action: tour.next,
        }],
    });
    tour.addStep({
        id: 'step-4',
        text: 'Publications Section <hr>Monitor now provides access to public indicators and archives from other organizations through this section.',
        attachTo: {
            element: 'li.dashboard-tour-step-4', // Target the element you want to highlight
            on: 'left', // Position the popover on top of the target element
        },
        buttons: [{
            text: 'Back',
            action: tour.back,
            classes: 'shepherd-button-secondary',
        }, {
            text: 'Next',
            action: tour.next,
        }],
    });
    tour.addStep({
        id: 'step-5',
        text: 'Profile Section <hr>You can now manage your profile and access other system settings from this section.',
        attachTo: {
            element: 'li.dashboard-tour-step-5', // Target the element you want to highlight
            on: 'top', // Position the popover on top of the target element
        },
        buttons: [{
            text: 'Back',
            action: tour.back,
            classes: 'shepherd-button-secondary',
        }, {
            text: 'Next',
            action: tour.next,
        }],
    });

    tour.addStep({
        id: 'step-6',
        text: '<h5><strong>{{ Auth::user()->name }}</strong>, please take a look on these new features and see how they work. <stong>Good Luck!</stong></h5>',
        buttons: [{
            text: 'Back',
            action: tour.back,
            classes: 'shepherd-button-secondary',
        }, {
            text: 'Got It',
            action: function() {
                setCookie('dashboardTourFinished', 'true', 30); // Set cookie with expiry of 7 days
                tour.complete();
            },
        }],
    });

    document.addEventListener('DOMContentLoaded', function() {
        const filterTourFinished = getCookie('dashboardTourFinished') === 'true';

        if (filterTourFinished) {
            tour.cancel();
        } else {
            tour.start();
        }
    });
</script>