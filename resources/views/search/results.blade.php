@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Search results</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Search Results</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-2">
               
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
            @if($results->isEmpty())
            <p>No Indicators found...</p>
            @else
            @foreach($results as $indicator)
            <div class="col-sm-4">
                <div class="card p-2">
                    <div class="card-title  ms-2 d-flex">
                        <div class="text-start w-50">
                            Category <span class="badge bg-primary text-light">{{$indicator->category}}</span>

                        </div>
                        <div class="text-end w-50">

                            <a href="{{ route('indicators.edit', $indicator->id) }}" class="icon" title="Edit Indicator">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="" class="icon" title="Delete Indicator">
                                <i class="bi bi-trash text-danger"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-success">Indicator Name</small>
                        <a href="{{ route('indicators.show', $indicator->id) }}" class="two-line-truncate btn-link h5 fw-bold">{{ $indicator->name }}</a>
                    </div>

                    <div class="card-footer p-0 py-2">

                        <div class="text-start">
                            <a href="{{ route('indicators.response.create', $indicator->id) }}" class="btn btn-link btn-sm fw-bold">Add Response
                                <i class="bi bi-box-arrow-in-up-right ms-2"></i>
                            </a>
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
                {{ $results->links('pagination::bootstrap-4') }} <!-- Use Bootstrap 4 Pagination -->
            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>

</script>