@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Update Organisation Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Update Organisation</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row g-1">
            <div class="col-sm-4">
                <div class="card p-2">
                    <div class="card-title">Update Organisation Details</div>
                    <div class="card-body">
                        
                        <form id="organization-registration-form" class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group my-2">
                                <label for="">Organization Logo</label>
                                <div class="text-center">
                                    <img id="profile-photo" src="{{ isset($organisation->logo) ? asset($organisation->logo) : asset('assets/img/placeholder.png') }}" class="rounded-circle" alt="Profile" width="200px" height="200px" style="border: 3px solid #999; object-fit: cover;">
                                </div>
                                <div class="pt-2">
                                    <input type="hidden" value="{{ isset($organisation->logo) ? asset($organisation->logo) : asset('assets/img/placeholder.png') }}" name="image_url" id="image_url">
                                    <input type="file" name="image" id="image" class="btn btn-outline btn-sm" accept="image/jpeg, image/png">

                                    <div class="invalid-feedback">Please choose organization logo</div>
                                </div>
                            </div>
                            <div class="form-group my-2">
                                <label for="">Organization Name</label>
                                <input type="text" class="form-control" value="{{$organisation->name}}" name="name" id="organization-name" required>
                                <div class="invalid-feedback">Organization name is required</div>
                            </div>

                            <div class="text-start mt-3">
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
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
        // Set up CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

                // Create a FormData object to handle file uploads and append extra fields
                let formData = new FormData(this);

                // Explicitly append the _method (PUT) and _token
                formData.append('_method', 'PUT');
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    method: 'POST', // Even though it's an update, send as POST with _method = PUT
                    url: '{{ route("organisations.update", $organisation->id) }}',
                    data: formData,
                    contentType: false, // Important for file upload
                    processData: false, // Important for file upload
                    success: function(response) {
                        Toastify({
                            text: response.message || "Organization updated successfully",
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
                        let errors = jqXHR.responseJSON.errors;
                        if (jqXHR.status === 422) { // Validation error
                            let errorMessages = [];
                            for (let field in errors) {
                                errorMessages.push(errors[field][0]); // Collect error messages
                            }
                            Toastify({
                                text: errorMessages.join(', '),
                                duration: 4000,
                                gravity: 'bottom',
                                position: 'left',
                                backgroundColor: 'red',
                            }).showToast();
                        } else if (jqXHR.status === 500) {
                            Toastify({
                                text: "An error occurred",
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