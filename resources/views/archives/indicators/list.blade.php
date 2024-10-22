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
        <h1>Indicators in {{ $archive->title }} archive</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Indicators in archive</li>
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
            <span class="alert alert-info">No Indicators found...</span>
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
                            <span class="badge bg-primary text-light">{{ $indicator->category }} indicator</span>

                            @endif

                            <span class="badge bg-secondary text-light">{{$indicator->qualitative_progress}}</span>
                            <span class="badge bg-light text-primary">
                                {{ $indicator->responses_count }} response{{ $indicator->responses_count !== 1 ? 's' : '' }}
                            </span>
                        </div>
                        <div class="text-end w-25">

                            <!-- -->
                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-success">Indicator Name</small>
                        <a href="{{ route('archives.indicator.details', $indicator->indicator_id) }}" class="two-line-truncate btn-link h5 fw-bold">{{ $indicator->name }}</a>
                        <div class="text-muted mt-1">
                            <!-- Format the created_at date using Carbon -->
                            <small>Created on: {{ \Carbon\Carbon::parse($indicator->created_at)->timezone('Africa/Nairobi')->format('M d, Y \a\t g:i a') }}</small>

                        </div>
                        @if($indicator->responses_count > 0)
                        @include('layouts.ruller')
                        @endif
                    </div>

                    <div class="card-footer p-0 py-2">

                        <div class="text-start">
                            <a href="{{ route('archives.indicator.responses', $indicator->indicator_id) }}" class="btn btn-link btn-sm fw-bold">View Responses
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