@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Create New Archive</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Create Archive</li>
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
                    <div class="card-title">Create Archive</div>
                    <div class="card-body">
                        <form action="{{ route('archives.store') }}" method="post" class="form needs-validation" novalidate>
                            @csrf <!-- Add CSRF token for form submission -->
                            <p class="my-2">This archive will belong to <span class="badge bg-warning">{{ $myOrganisation->name}}</span> organisation</p>
                            <hr>
                            <div class="form-group my-2">
                                <label for="title">Title</label>
                                <input placeholder="Archive title goes here..." type="text" name="title" class="form-control" required>
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            <input type="hidden" name="organisation_id" value="{{ old('organisation_id', $myOrganisation->id) }}">
                            <div class="form-group my-2">
                                <label for="description">Archive Description</label>
                                <textarea placeholder="Briefly explain the contents of the archive" rows="5" class="form-control" name="description" id="description" required></textarea>
                                <div class="invalid-feedback">This value is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="access_level">Status</label>
                                <select name="status" id="" class="form-control" required>
                                    <option value="">Select status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    
                                </select>
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            <div class="form-group my-2">
                                <label for="access_level">Access level</label>
                                <select name="access_level" id="" class="form-control" required>
                                    <option value="">Select Access Level</option>
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="restricted">Restricted</option>
                                </select>
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            <button class="btn btn-primary btn-sm my-2">Save</button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card p-2">
                    
                    <div class="card-body">
                        <small class="text-secondary">
                            <strong>Creating an Archive for Your Indicators</strong>
                            <p>Welcome to the Archive Creation section! This feature allows you to organize and store your indicators effectively. Here’s how to get started:</p>

                            <ul>
                                <li><strong>Title:</strong> Give your archive a clear and concise title that reflects its content. This will help you and others easily identify the purpose of the archive.</li>
                                <li><strong>Description:</strong> Provide a brief overview of what this archive contains. You can include important details, objectives, or the context of the indicators being archived.</li>
                                <li><strong>Status:</strong> Choose the status of your archive (e.g., active or inactive). This helps in managing visibility and organization of your archived indicators.</li>
                                <li><strong>Access Level:</strong> Determine who can view this archive. Options include 'public' for open access, 'restricted' for specific users, or 'private' for personal use.</li>

                            </ul>
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

    })
</script>