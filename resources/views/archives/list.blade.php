@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Showing All Archives</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Archives</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-2">
                <div>
                    <a href="{{ route('archives.create') }}" style="border-radius: 70px !important;" class="btn btn-primary btn-sm py-3 px-3">Create Archive</a>
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
        <div class="row g-1">
            @if($archives->isEmpty())
            <span>No archives found</span>
            @else
            @foreach($archives as $archive)
            <div class="col-sm-4">
                <div class="card p-2">
                    <div class="card-title">
                        <div class="d-flex">
                            <div class="d-flex ms-2 text-start w-50">
                                <span class="badge bg-secondary text-light">Visibility: {{ $archive->access_level}}</span>
                                <span class="badge bg-primary text-light mx-2">Status: {{ $archive->access_level}}</span>
                            </div>
                            <div class="text-end w-50">
                                <a href="{{ route('archives.edit', $archive->id) }}" class="icon" title="Edit Archive">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="" class="icon" title="Delete Indicator" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $archive->id }}">
                                    <i class="bi bi-trash text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <small class="text-success">Archive name</small>
                        <a href="{{ route('archives.show', $archive->id) }}" class="one-line-truncate btn-link h5 fw-bold">{{ $archive->title }} <i class="bi bi-box-arrow-in-up-right ms-2"></i></a>

                        <div class="accordion" id="accordionArchive{{$loop->iteration}}">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$loop->iteration}}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->iteration}}" aria-expanded="false" aria-controls="collapse{{$loop->iteration}}">
                                        <small class="text-success">Expand To Read More</small>
                                    </button>
                                </h2>
                                <div id="collapse{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="heading{{$loop->iteration}}" data-bs-parent="#accordionArchive{{$loop->iteration}}">
                                    <div class="accordion-body">
                                        <h5>Description</h5>
                                        <p>{{ $archive->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h6>Created On: {{ $archive->created_at }}</h6>
                    </div>
                </div>
                <div class="modal fade" id="deleteModal{{ $archive->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $archive->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel{{ $archive->id }}">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this archive?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('archives.destroy', $archive->id) }}" method="POST" id="delete-form{{ $archive->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form{{ $archive->id }}').submit();">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $archives->links('pagination::bootstrap-4') }} <!-- Use Bootstrap 4 Pagination -->
            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {

    })
</script>