@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<main id="main" class="main">

    <section class="section dashboard mt-3">

        <div class="alert alert-warning mt-2 px-3 py-1">
            <h5>Welcome back, {{ Auth::User()->name }}</h5>
            <hr>
            <h2>Your are a member of {{ $myOrganisation->name }}</h2>

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
                setCookie('filterIndicatorsTourFinished', 'true', 30); // Set cookie with expiry of 7 days
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