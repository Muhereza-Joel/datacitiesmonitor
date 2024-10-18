@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .status-draft {
        /* border-top: 10px solid #fc03a1; */
        border-left: 5px solid #fc03a1;
    }

    .status-review {
        /* border-top: 10px solid #0a1157; */
        border-left: 5px solid #0a1157;
    }

    .status-public {
        /* border-top: 10px solid green; */
        border-left: 5px solid green;
    }

    .status-archived {
        /* border-top: 10px solid #1cc9be; */
        border-left: 5px solid #1cc9be;
    }

    .status-key {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .status-key span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-key .key-draft {
        width: 20px;
        height: 10px;
        background-color: #fc03a1;
    }

    .status-key .key-review {
        width: 20px;
        height: 10px;
        background-color: #0a1157;
    }

    .status-key .key-public {
        width: 20px;
        height: 10px;
        background-color: green;
    }

    .status-key .key-archived {
        width: 20px;
        height: 10px;
        background-color: #1cc9be;
    }

    .drag-active {
        border: 2px dashed #28a745;
        /* Change border color */
        background-color: #f0f0f0;
        /* Light background */
        /* Add more styles as needed */
    }
</style>

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
            <div class="col-sm-8 pt-2">
                <!-- Filter Section -->
                <form action="{{ route('indicators.index') }}" method="GET" class="d-flex align-items-center gap-3">
                    <div class="form-group mt-2">

                        <select class="form-select" id="status" name="status">
                            <option value="">Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                            <option value="public" {{ request('status') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="form-group mt-2">

                        <select class="form-select" id="qualitative_progress" name="qualitative_progress">
                            <option value="">Qualitative Progress</option>
                            <option value="on track" {{ request('qualitative_progress') == 'on track' ? 'selected' : '' }}>On Track</option>
                            <option value="at risk" {{ request('qualitative_progress') == 'at risk' ? 'selected' : '' }}>At Risk</option>
                            <option value="off track" {{ request('qualitative_progress') == 'off track' ? 'selected' : '' }}>Off Track</option>
                            <option value="completed" {{ request('qualitative_progress') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="not started" {{ request('qualitative_progress') == 'not started' ? 'selected' : '' }}>Not Started</option>
                        </select>
                    </div>
                    <div class="form-group mt-2">

                        <input autocomplete="off" type="text" class="form-control" id="category" name="category" value="{{ request('category') }}" placeholder="Enter category">
                    </div>
                    <div class="form-group pt-3">
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
        @if($indicators->isEmpty())
        <p class="alert alert-info">No Indicators found...</p>
        @else
        <div class="row g-2">
            @foreach($indicators as $indicator)
            <div class="col-sm-6">
                <div class="card p-2 status-{{ strtolower($indicator->status) }}">
                    <div class="card-title ms-2 d-flex">
                        <div class="text-start w-75">
                            <img src="{{ asset($indicator->organisation->logo) }}" class="rounded-circle p-1 me-1" width="30px" height="30px" alt="">
                            @if($indicator->category === "None")
                            <span class="badge bg-success text-light">Un Categorised</span>
                            @else
                            <span class="badge bg-primary text-light">{{ $indicator->category }} indicator</span>
                            @endif
                            <span class="badge bg-secondary text-light">{{$indicator->qualitative_progress}}</span>
                            <span class="badge bg-light text-primary">
                                {{ $indicator->responses_count }} response{{ $indicator->responses_count !== 1 ? 's' : '' }}
                            </span>
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
                        <div class="text-muted mt-1">
                            <small>Created on: {{ $indicator->created_at->format('M d, Y \a\t g:iA') }}</small>
                        </div>

                        <div class="mt-3">
                            <div style="position: relative; height: 20px; background-color: #f0f0f0; border-radius: 5px; border: 1px solid #ccc;">
                                @php
                                // Calculate the positions based on the progress direction
                                $isIncreasing = $indicator->baseline < $indicator->target;
                                    $hasResponses = $indicator->responses->isNotEmpty(); // Check if the indicator has any responses
                                    $baselinePosition = ($indicator->baseline - min($indicator->baseline, $indicator->target)) / abs($indicator->target - $indicator->baseline) * 100;
                                    $currentPosition = ($indicator->current - min($indicator->baseline, $indicator->target)) / abs($indicator->target - $indicator->baseline) * 100;
                                    $targetPosition = ($indicator->target - min($indicator->baseline, $indicator->target)) / abs($indicator->target - $indicator->baseline) * 100;
                                    @endphp

                                    <!-- Faint Shade from Baseline to Current (Only for Increasing direction or if it has responses) -->
                                    @if ($isIncreasing || $hasResponses)
                                    <div style="position: absolute; left: {{ $isIncreasing ? $baselinePosition : $currentPosition }}%; right: {{ $isIncreasing ? 100 - $currentPosition : 100 - $baselinePosition }}%; height: 100%; background-color: rgba(144, 238, 144, 0.3); border-radius: 3px;" title="Shaded Area"></div>
                                    @endif

                                    <!-- Baseline Marker -->
                                    <div style="position: absolute; left: {{ $baselinePosition }}%; width: 6px; height: 100%; background-color: rgba(0, 0, 255, 0.5); border-radius: 3px;" title="Baseline"></div>
                                    <!-- Current State Marker -->
                                    <div style="position: absolute; left: {{ $currentPosition }}%; width: 6px; height: 100%; background-color: green; border-radius: 3px;" title="Current State"></div>
                                    <!-- Target Marker -->
                                    <div style="position: absolute; left: {{ $targetPosition }}%; width: 6px; height: 100%; background-color: red; border-radius: 3px;" title="Target"></div>
                            </div>

                            <!-- Horizontal Arrow Indicating Progress Direction -->
                            <div style="position: relative; margin-top: 5px;">
                                <div style="position: absolute; left: {{ $isIncreasing ? $baselinePosition : $targetPosition }}%; right: {{ $isIncreasing ? 100 - $targetPosition : 100 - $baselinePosition }}%; height: 0; border-top: 2px solid {{ $isIncreasing ? 'green' : 'red' }};">
                                    <div style="position: absolute; {{ $isIncreasing ? 'right: 0;' : 'left: 0;' }} top: -5px; border-left: 5px solid transparent; border-right: 5px solid transparent; border-bottom: 5px solid {{ $isIncreasing ? 'green' : 'red' }};">
                                    </div>
                                </div>
                            </div>

                            <!-- Ruler with Tick Marks -->
                            <div class="px-1" style="position: relative; margin-top: 15px;">
                                <div style="position: relative; height: 10px;">
                                    <div style="position: absolute; left: 0; top: 0; height: 100%; width: 100%; border-top: 1px solid #aaa;"></div>
                                    @php
                                    // Set start and end values for the loop
                                    $start = min($indicator->baseline, $indicator->target);
                                    $end = max($indicator->baseline, $indicator->target);
                                    @endphp

                                    @for ($i = $start; $i <= $end; $i +=(($end - $start) / 10))
                                        <div style="position: absolute; left: {{ (($i - $start) / ($end - $start)) * 100 }}%; height: 15px; border-left: 1px solid #aaa;">
                                </div>
                                <div style="position: absolute; left: {{ (($i - $start) / ($end - $start)) * 100 }}%; top: 15px; transform: translateX(-50%);">
                                    {{ number_format($i, 0) }}
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2 pt-3">
                            <div style="text-align: center;">
                                <span style="color: rgba(0, 0, 255, 0.5);">{{ $indicator->baseline }}</span><br>
                                <small>Baseline</small>
                            </div>
                            <div style="text-align: center;">
                                <span style="color: green;">{{ $indicator->current }}</span><br>
                                <small>Current State</small>
                            </div>
                            <div style="text-align: center;">
                                <span style="color: red;">{{ $indicator->target }}</span><br>
                                <small>Target</small>
                            </div>
                        </div>
                        
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

        </div>
        @endforeach
        @endif
        <!-- Pagination links -->
        <div class="d-flex justify-content-center">
            {{ $indicators->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>

        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>

</script>