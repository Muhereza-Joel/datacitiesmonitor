@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>AllOrganizations</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Organizations</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-3">
                <a href="{{ route('organisations.create') }}" class="btn btn-primary btn-sm">Add New Organisation</a>
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
            <div class="col">
                <div class="card p-2">
                    <div class="">
                        <h5 class="card-title">Current Registered Organizations</h5>

                    </div>

                    <div class="card-body">
                        @if(count($organisations) == 0)
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>No organizations found!</strong> Please register an organization to get started.
                        </div>
                        @else
                        <div class="row g-1" style="display: flex; flex-wrap: wrap;">
                            @foreach($organisations as $row)
                            <div class="col-sm-3 d-flex">
                                <div class="card p-2 flex-fill">
                                    <div class="card-title">
                                        {{$row['name']}}
                                        @if($row['active'] == 'true')
                                        <span class="badge bg-primary text-light">Active</span>
                                        @else
                                        <span class="badge bg-danger text-light">Inactive</span>
                                        @endif
                                    </div>
                                    <div class="card-body text-center">
                                        <img style="width: 150px; object-fit: contain; border: 3px solid #999"
                                            src="{{ isset($row->logo) ? asset($row->logo) : asset('assets/img/placeholder.png') }}"
                                            alt="logo" class="rounded-circle">

                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('organisations.edit', $row->id) }}" class="btn btn-primary btn-sm flex-fill me-1">Edit</a>
                                        <button class="btn btn-danger btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $row->id }}">Delete</button>
                                    </div>

                                    <div class="modal fade" id="deleteModal{{ $row->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $row->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $row->id }}">Confirm Deletion</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this organization?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('organisations.destroy', $row->id) }}" method="POST" id="delete-form{{ $row->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('delete-form{{ $row->id }}').submit();">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {

        $('#organization-registration-form').submit(function(e) {
            e.preventDefault();

            if (this.checkValidity() === true) {
                let orgName = $('#organization-name').val().trim();

                if (orgName.toLowerCase() === 'administrator') {
                    Toastify({
                        text: "Organization name cannot be 'Administrator'",
                        duration: 4000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: 'red',
                    }).showToast();
                    return false;
                }

                // Create a FormData object to handle file uploads
                let formData = new FormData(this);

                $.ajax({
                    method: 'post',
                    url: '{{ route("organisations.store") }}',
                    data: formData,
                    contentType: false, // Important for file upload
                    processData: false, // Important for file upload
                    success: function(response) {
                        Toastify({
                            text: response.message || "Row Created Successfully",
                            duration: 4000,
                            gravity: 'bottom',
                            position: 'left',
                            backgroundColor: '#05333e',
                        }).showToast();

                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    },
                    error: function(jqXHR) {
                        if (jqXHR.status === 500) {
                            Toastify({
                                text: "An Error Ocurred",
                                duration: 4000,
                                gravity: 'bottom',
                                position: 'left',
                                backgroundColor: 'red',
                            }).showToast();
                        }
                    }
                });
            }
        });

        // Image preview logic
        $('#image').change(function(e) {
            const file = e.target.files[0]; // Get the selected file
            if (file) {
                const reader = new FileReader(); // Create a new FileReader
                reader.onload = function(event) {
                    $('#profile-photo').attr('src', event.target.result); // Set the src attribute of the image
                };
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        });
    });
</script>