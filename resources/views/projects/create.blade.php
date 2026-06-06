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
        <h1>Create Project</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                <li class="breadcrumb-item active">Create Project</li>
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
                    <div class="card-title">Create New Project</div>
                    <div class="alert alert-warning p-2">This project you are about to create will belong to <span class="badge bg-primary">{{$myOrganisation['name']}}</span></div>

                    <div class="card-body">
                        <form action="{{ route('projects.store') }}" class="needs-validation" novalidate id="createProjectForm" method="post">
                            @csrf

                            <div class="form-group my-2">
                                <label for="name">Name of Project</label>
                                <textarea placeholder="Project name goes here..." type="text" name="name" required class="form-control">{{ old('name')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="description">Project Description</label>
                                <textarea placeholder="Project description goes here..." type="text" rows="8" name="description" required class="form-control">{{ old('description')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input autocomplete="off" type="text" class="form-control" id="startDate" name="start_date" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>
                            <div class="form-group my-2">
                                <label for="endDate" class="form-label">End Date</label>
                                <input autocomplete="off" type="text" class="form-control" id="endDate" name="end_date" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="status">Project Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="on hold" {{ old('status') === 'on hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="planned" {{ old('status') === 'planned' ? 'selected' : '' }}>Planned</option>
                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <div class="invalid-feedback">Please select the project status.</div>
                            </div>

                            <input type="hidden" value="{{$myOrganisation->id}}" name="organisation_id">

                            <div class="text-start">
                                @can('create', \App\Models\Project::class)
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                @endcan
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
                            <li><strong>Project Name:</strong> Provide a brief and descriptive title for the project.</li>
                            <li><strong>Project Description:</strong> Explain the purpose and scope of the project.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>
    var today = new Date();
    $("#startDate, #endDate").datepicker({
        minDate: today,
        dateFormat: 'yy-mm-dd'
    });

    $('#createProjectForm').on('submit', function(event) {
        event.preventDefault();

        if (this.checkValidity() === true) {
            let fromData = $(this).serialize();

            $.ajax({
                url: '{{ route("projects.store") }}',
                type: 'POST',
                data: fromData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    Toastify({
                        text: response.message || "Project Created Successfully",
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
                            text: jqXHR.responseJSON.message || "An error occurred while creating project",
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