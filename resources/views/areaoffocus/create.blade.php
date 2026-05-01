@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

@php
use \Carbon\Carbon;
@endphp

<style>
    .event-visibility h6 {
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    .event-visibility p {
        margin-bottom: 10px;
    }

    .event-visibility ul {
        list-style-type: disc;
        padding-left: 20px;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Create Area of Focus</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                <li class="breadcrumb-item active">Create Area of Focus</li>
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
            <div class="col-sm-8">
                <div class="card p-2">
                    <div class="card-title">Create New Area of Focus</div>
                    <div class="alert alert-warning p-2">This area of focus you are about to create will belong to <span class="badge bg-primary">{{$myOrganisation['name']}}</span></div>

                    <div class="card-body">
                        <form action="{{ route('areas-of-focus.store') }}" class="needs-validation" novalidate id="createAreaOfFocusForm" method="post">
                            @csrf

                            <div class="form-group my-2">
                                <label for="name">Project</label>
                                <select name="project_id" id="project_id" class="form-control" required>
                                    <option value="">Select Project</option>
                                    @foreach($myOrganisation->projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="name">Name of Area of Focus</label>
                                <textarea placeholder="Area of focus name goes here..." type="text" name="name" required class="form-control">{{ old('name')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="description">Area of Focus Description</label>
                                <textarea placeholder="Area of focus description goes here..." type="text" rows="8" name="description" required class="form-control">{{ old('description')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <div class="invalid-feedback">Please select the status.</div>
                            </div>

                            <input type="hidden" value="{{$myOrganisation->id}}" name="organisation_id">

                            <div class="text-start">
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card p-2">
                    <div class="card-title">Instructions</div>
                    <div class="card-body">
                        <ul>
                            <li><strong>Area of Focus Name:</strong> Provide a brief and descriptive title for the area of focus.</li>
                            <li><strong>Area of Focus Description:</strong> Explain the purpose and scope of the area of focus.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>
    $('#createAreaOfFocusForm').on('submit', function(event) {
        event.preventDefault();

        if (this.checkValidity() === true) {
            let fromData = $(this).serialize();

            $.ajax({
                url: '{{ route("areas-of-focus.store") }}',
                type: 'POST',
                data: fromData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    Toastify({
                        text: response.message || "Area of Focus Created Successfully",
                        duration: 4000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                    }).showToast();

                    setTimeout(function() {
                        window.location.reload();
                    }, 2000)

                },
                error: function() {

                    if (jqXHR.status === 500) {
                        Toastify({
                            text: jqXHR.responseJSON.message || "An error occurred while creating area of focus",
                            duration: 4000,
                            gravity: 'bottom',
                            position: 'left',
                            backgroundColor: 'linear-gradient(to right, #ff416c, #ff4b2b)',
                        }).showToast();


                    }
                }
            });
        }
    });

    $(document).ajaxStart(function() {
        $("#loader").removeClass('d-none');
    });

    $(document).ajaxStop(function() {
        $("#loader").addClass('d-none');
    });
</script>