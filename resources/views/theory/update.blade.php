@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Update ToC Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Organizations</li>
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

    <section class="section dashboard">
        <div class="row g-1">
            <div class="col-sm-6">
                <div class="card p-2">
                    <div class="card-title">Update Theory of Change</div>
                    <div class="card-body">
                        <form action="{{ route('theory.update', $toc->id ) }}" method="post" class="form needs-validation" novalidate>
                            @csrf <!-- Add CSRF token for form submission -->
                            @method('PUT')
                            
                            <hr>
                            <div class="form-group my-2">
                                <label for="title">Title</label>
                                <input value="{{ $toc->title }}" type="text" name="title" class="form-control" required>
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            <input type="hidden" name="organisation_id" value="{{ old('organisation_id', $myOrganisation->id) }}">
                            <div class="form-group">
                                <label for="description">ToC Description</label>
                                <textarea class="form-control" name="description" id="description">{{ $toc->description }}</textarea>
                            </div>
                            <button class="btn btn-primary btn-sm my-2">Save</button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card p-2">
                    <div class="card-title">Explanation</div>
                    <div class="card-body">
                        <small class="text-secondary">
                            The Theory of Change (ToC) connects the project's activities to the intended outcomes and impacts, illustrating the logical pathway from initial inputs through outputs to the ultimate goals. By clearly defining the relationships between various indicators—both outcome and output—this framework helps stakeholders understand the project's strategic approach and measure its success in achieving long-term objectives.
                        </small>


                    </div>
                </div>
            </div>

        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {
        tinymce.init({
            selector: 'textarea', // Target your textarea
            plugins: 'lists link table',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | link image| numlist bullist',
            menubar: false,
        });
    })
</script>