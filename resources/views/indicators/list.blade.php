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
                <div class="card">
                    <div class="card-body">
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
                        <div class="status-key pt-1 pb-0">
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
                    </div>
                </div>

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

    <section class="section dashboard">
        <div class="row g-2">


            @if($indicators->isEmpty())
            <p>No Indicators found...</p>
            @else
            @foreach($indicators as $indicator)
            <div class="col-sm-6">
                <div class="card p-2 status-{{ strtolower($indicator->status) }}">
                    <div class="card-title  ms-2 d-flex">
                        <div class="text-start w-75">
                            <img src="{{ asset($indicator->organisation->logo) }}" class="rounded-circle p-1 me-1" width="30px" height="30px" alt="">
                            @if($indicator->category === "None")
                            <span class="badge bg-success text-light">Un Categorised</span>
                            @else
                            <span class="badge bg-primary text-light">{{ $indicator->category }}</span> indicator

                            @endif

                            <span class="badge bg-secondary text-light">{{$indicator->qualitative_progress}}</span>

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
                        <a href="{{ route('indicators.show', $indicator->id) }}" class="one-line-truncate btn-link h5 fw-bold">{{ $indicator->name }}</a>
                        <div class="text-muted mt-1">
                            <!-- Format the created_at date using Carbon -->
                            <small>Created on: {{ \Carbon\Carbon::parse($indicator->created_at)->format('M d, Y \a\t g:iA') }}</small>
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