@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>{{ $organisation->name }} publications for indicators</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Publications</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-3">

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


            @if($items->isEmpty())
            <p class="alert alert-info">No Public Indicators found...</p>
            @else
            @foreach($items as $indicator)
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
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-success">Indicator Name</small>
                        <a href="{{ route('indicators.show', $indicator->id) }}" class="two-line-truncate btn-link h6 fw-bold">{{ $indicator->name }}</a>
                        <div class="text-muted mt-1">
                            <!-- Format the created_at date using Carbon -->
                            <small>Created on: {{ $indicator->created_at->format('M d, Y \a\t g:ia') }}</small>
                        </div>

                    </div>

                    <div class="card-footer p-0 py-2">

                        <div class="text-start">
                            

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
                {{ $items->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>

</script>