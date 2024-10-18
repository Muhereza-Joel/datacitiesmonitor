@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Showing All Theories of Change</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Create ToC</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-3">
                @if(Gate::allows('create', App\Models\TheoryOfChange::class))
                <a href="{{ route('theory.create') }}" class="btn btn-primary btn-sm">Create New ToC</a>
                @endif
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
        <div class="row g-1">
            @if($theories->isEmpty())
            <div class="alert alert-info">No Theories found...</div>
            @else
            @forelse($theories as $theory)
            <div class="col-sm-4">
                <div class="card p-2">
                    <div class="card-title fw-bold ms-2">
                        <div class="d-flex">

                            <div class="w-75 text-start">
                                <img src="{{ asset($theory->organisation->logo) }}" class="rounded-circle p-1 me-1" width="30px" height="30px" alt="">
                                ToC has {{ $theory->indicators_count }} Indicators
                            </div>

                            @if(Gate::allows('delete', $theory))
                            <div class="w-25 text-end">
                                <a href="" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $theory->id }}"><i class="bi bi-trash text-danger"></i></a>
                            </div>
                            @endif

                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-success">Title</small>
                        <h4 class="two-line-truncate">{{ $theory->title }}</h4>

                        <div class="accordion" id="accordionToC{{$loop->iteration}}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$loop->iteration}}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->iteration}}" aria-expanded="false" aria-controls="collapse{{$loop->iteration}}">
                                        <small class="text-success">Expand To Read More</small>
                                    </button>
                                </h2>
                                <div id="collapse{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="heading{{$loop->iteration}}" data-bs-parent="#accordionToC{{$loop->iteration}}">
                                    <div class="accordion-body">
                                        <h5>Description</h5>
                                        <p>{!! $theory->description !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(Gate::allows('view', $theory))
                        <a href="{{ route('theory.indicators', $theory->id) }}" class="btn btn-link btn-sm fw-bold">View Connected Indicators <i class="bi bi-box-arrow-in-up-right ms-2"></i></a>
                        @endif
                        @if(Gate::allows('create', $theory))
                        <a href="{{ route('theory.indicators.create', $theory->id) }}" class="btn btn-link btn-sm fw-bold">Create New Indicator from ToC <i class="bi bi-box-arrow-in-up-right ms-2"></i></a>
                        @endif

                        @if(Gate::allows('update', $theory))
                        <a href="{{ route('theory.edit', $theory->id) }}" class="btn btn-link btn-sm fw-bold">Edit ToC Details <i class="bi bi-box-arrow-in-up-right ms-2"></i></a>
                        @endif
                    </div>
                </div>
                <div class="modal fade" id="deleteModal{{ $theory->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $theory->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel{{ $theory->id }}">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this theory of change?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('theory.destroy', $theory->id) }}" method="POST" id="delete-form{{ $theory->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form{{ $theory->id }}').submit();">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @empty
            <div class="alert alert-info">No Theories found...</div>
            @endforelse
            @endif
        </div>

    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>

</script>