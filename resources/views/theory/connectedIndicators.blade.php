@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Showing All Indicators Linked To ToC</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Indicators</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-2">
                <div>
                    @if(Gate::allows('create', App\Models\Indicator::class))
                    <a href="{{ route('indicators.create') }}" style="border-radius: 70px !important;" class="btn btn-primary btn-sm py-3 px-3">Create Indicator</a>
                    @endif
                </div>
            </div>

        </div>
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
    <div class="status-key text-center pt-1 pb-0">
        <span>
            <div class="key-draft"></div> Draft
        </span>
        <span>
            <div class="key-review"></div> Review
        </span>
        <span>
            <div class="key-public"></div> Public
        </span>
        <span>
            <div class="key-archived"></div> Archived
        </span>
    </div>

    <section class="section dashboard">
        <div class="row g-2">
            @if($indicators->isEmpty())
            <p class="alert alert-info">No Indicators found...</p>
            @else
            @foreach($indicators as $indicator)
            <div class="col-sm-6">
                <div class="card p-2 status-{{ strtolower($indicator->status) }}">
                    <div class="card-title fw-bold ms-2 d-flex">
                        <div class="text-start w-75">

                            @if(session('user.preferences.show_indicator_organisation_logo') === 'true')
                            <img src="{{ asset($indicator->organisation->logo) }}" class="rounded-circle p-1 me-1" width="30px" height="30px" alt="">
                            @endif

                            @if(session('user.preferences.show_indicator_category') === 'true')
                            @if($indicator->category === "None")
                            <span class="badge bg-success text-light">Un Categorised</span>
                            @else
                            <span class="badge bg-primary text-light">{{ $indicator->category }} indicator</span>
                            @endif
                            @endif

                            @if(session('user.preferences.show_indicator_qualitative_status') === 'true')
                            <span class="badge bg-secondary text-light">{{$indicator->qualitative_progress}}</span>
                            @endif

                            @if(session('user.preferences.show_indicator_response_count') === 'true')
                            <span class="badge bg-light text-primary">
                                {{ $indicator->responses_count }} response{{ $indicator->responses_count !== 1 ? 's' : '' }}
                            </span>
                            @endif

                        </div>
                        <div class="text-end w-25">
                            @if(Gate::allows('update', $indicator))
                            <a href="{{ route('indicators.edit', $indicator->id) }}" class="icon" title="Edit Indicator">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @endif

                            @if(Gate::allows('delete', $indicator))
                            <a href="" class="icon" title="Delete Indicator">
                                <i class="bi bi-trash text-danger"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-success">Indicator Name</small>
                        <a href="{{ route('indicators.show', $indicator->id) }}" class="two-line-truncate btn-link h6 fw-bold">{{ $indicator->name }}</a>
                        @if(session('user.preferences.show_indicator_create_date') === 'true')
                        <div class="text-muted mt-1">
                            <!-- Format the created_at date using Carbon -->
                            <small>Created on: {{ $indicator->created_at->format('M d, Y \a\t g:iA') }}</small>
                        </div>
                        @endif

                        @if(session('user.preferences.show_indicator_ruller') === 'true' && $indicator->responses_count > 0)
                        @include('layouts.ruller')
                        @endif
                    </div>

                    <div class="card-footer p-0 py-2">

                        <div class="text-start">
                            @if(Gate::allows('create', App\Models\Response::class))
                            <a href="{{ route('indicators.response.create', $indicator->id) }}" class="btn btn-link btn-sm fw-bold">Add Response
                                <i class="bi bi-box-arrow-in-up-right ms-2"></i>
                            </a>
                            @endif
                            <a href="{{ route('indicator.responses', $indicator->id) }}" class="btn btn-link btn-sm fw-bold">View Responses
                                <i class="bi bi-box-arrow-in-up-right ms-2"></i>
                            </a>
                            @if($indicator->responses->isNotEmpty() && $indicator->responses->first()->created_at)
                            <span class="badge bg-light text-primary">
                                Last Response Added: {{ $indicator->responses->first()->created_at->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            @endforeach
            @endif
            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $indicators->links('pagination::bootstrap-4') }} <!-- Use Bootstrap 4 Pagination -->
            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>

</script>