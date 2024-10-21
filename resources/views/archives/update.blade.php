@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Update Archive Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Update Archive</li>
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
                    <div class="card-title">Update Archive</div>
                    <div class="card-body">
                        <form action="{{ route('archives.update', $archive->id) }}" method="post" class="form needs-validation" novalidate>
                            @csrf <!-- Add CSRF token for form submission -->
                            @method('PUT')
                            <hr>
                            <div class="form-group my-2">
                                <label for="title">Title</label>
                                <input value="{{ $archive->title }}" placeholder="Archive title goes here..." type="text" name="title" class="form-control" required>
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            <input type="hidden" name="organisation_id" value="{{ old('organisation_id', $myOrganisation->id) }}">
                            <div class="form-group my-2">
                                <label for="description">Archive Description</label>
                                <textarea placeholder="Briefly explain the contents of the archive" rows="5" class="form-control" name="description" id="description" required>{{ $archive->description }}</textarea>
                                <div class="invalid-feedback">This value is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="access_level">Status</label>
                                <select name="status" id="" class="form-control" required>
                                    <option value="">Select status</option>
                                    <option value="active" {{ $archive->status == 'active' ? 'selected' : '' }} >Active</option>
                                    <option value="inactive" {{ $archive->status == 'inactive' ? 'selected' : '' }} >Inactive</option>
                                    
                                </select>
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            <div class="form-group my-2">
                                <label for="access_level">Access level</label>
                                <select name="access_level" id="" class="form-control" required>
                                    <option value="">Select Access Level</option>
                                    <option value="public" {{ $archive->access_level == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ $archive->access_level == 'private' ? 'selected' : '' }}>Private</option>
                                    <option value="restricted" {{ $archive->access_level == 'restricted' ? 'selected' : '' }}>Restricted</option>
                                </select>
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            @if(Gate::allows('update', $archive))
                            <button class="btn btn-primary btn-sm my-2">Save</button>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
           

        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {

    })
</script>