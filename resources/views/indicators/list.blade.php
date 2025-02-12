@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="row">
            <div class="col-sm-2">
                <h1>Indicators</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Indicators</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-8 pt-2 step-0">
                <!-- Filter Section -->
                <form action="{{ route('indicators.index') }}" method="GET" class="d-flex align-items-center gap-3">
                    <div class="form-group mt-2 filter-tour-step-1">

                        <select class="form-select" id="status" name="status">
                            <option value="">Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                            <option value="public" {{ request('status') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="form-group mt-2 filter-tour-step-2">

                        <select class="form-select" id="qualitative_progress" name="qualitative_progress">
                            <option value="">Qualitative Progress</option>
                            <option value="on track" {{ request('qualitative_progress') == 'on track' ? 'selected' : '' }}>On Track</option>
                            <option value="at risk" {{ request('qualitative_progress') == 'at risk' ? 'selected' : '' }}>At Risk</option>
                            <option value="off track" {{ request('qualitative_progress') == 'off track' ? 'selected' : '' }}>Off Track</option>
                            <option value="completed" {{ request('qualitative_progress') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="not started" {{ request('qualitative_progress') == 'not started' ? 'selected' : '' }}>Not Started</option>
                        </select>
                    </div>
                    <div class="form-group mt-2 filter-tour-step-3">

                        <input autocomplete="off" type="text" class="form-control" id="category" name="category" value="{{ request('category') }}" placeholder="Enter category">
                    </div>
                    <div class="form-group pt-3 filter-tour-step-4">
                        <button type="submit" class="btn btn-primary btn-sm mt-0">Filter</button>
                        <a href="{{ route('indicators.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </form>


            </div>
            <div class="col-sm-2">
                <div class="text-end pt-2">
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
            <div class="key-draft"></div> Draft <span class="fw-bold text-primary">({{ $indicatorCounts['draft'] }})</span>
        </span>
        <span>
            <div class="key-review"></div> Review <span class="fw-bold text-primary">({{ $indicatorCounts['review'] }})</span>
        </span>
        <span>
            <div class="key-public"></div> Public <span class="fw-bold text-primary">({{ $indicatorCounts['public'] }})</span>
        </span>
        <span>
            <div class="key-archived"></div> Archived <span class="fw-bold text-primary">({{ $indicatorCounts['archived'] }})</span>
        </span>
        <div class="">Total: {{ $indicatorCounts['total'] }} Indicators</div>
    </div>

    <section class="section dashboard">
        @if($indicators->isEmpty())
        <p class="alert alert-info">No Indicators found...</p>
        @else
        <div class="row g-2">
            @foreach($indicators as $indicator)
            <div class="col-sm-6">
                <div class="card p-2 status-{{ strtolower($indicator->status) }}">
                    <div class="card-title ms-2 d-flex">
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
                            @if($indicator->revisionHistory->isNotEmpty()) <!-- Check if revisions are available -->
                            <a href="{{ route('indicator.history', $indicator->id) }}" class="icon" title="View Revision History">
                                <i class="bi bi-clock-history"></i> <!-- Same size for all icons -->
                            </a>
                            @endif
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
                                Last Response Added: {{ $indicator->latest_response_date->diffForHumans() }}
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
                {{ $indicators->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>


    </section>

</main><!-- End #main -->

@include('layouts.footer')