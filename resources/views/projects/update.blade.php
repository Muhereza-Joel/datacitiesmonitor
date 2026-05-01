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
        <h1>Update Project</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                <li class="breadcrumb-item active">Update Project</li>
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
            <div class="col-sm-12">
                <div class="card p-2">
                    <div class="card-body">
                        <form id="updateProjectForm" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="form-group my-2">
                                <label for="name">Name of Project</label>
                                <textarea name="name" required class="form-control">{{ $project->name }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="description">Project Description</label>
                                <textarea rows="8" name="description" required class="form-control">{{ $project->description }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group my-2">
                                    <label for="startDate">Start Date</label>
                                    <input type="text" class="form-control datepicker" id="startDate" name="start_date" value="{{ $project->start_date }}" required>
                                    <div class="invalid-feedback">This field is required</div>
                                </div>
                                <div class="col-md-6 form-group my-2">
                                    <label for="endDate">End Date</label>
                                    <input type="text" class="form-control datepicker" id="endDate" name="end_date" value="{{ $project->end_date }}" required>
                                    <div class="invalid-feedback">This field is required</div>
                                </div>
                            </div>

                            <div class="form-group my-2">
                                <label for="status">Project Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="planned" {{ $project->status == 'planned' ? 'selected' : '' }}>Planned</option>
                                    <option value="active" {{ $project->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="on hold" {{ $project->status == 'on hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <div class="invalid-feedback">This field is required</div>

                                <button type="submit" class="btn btn-sm btn-primary mt-3">Update Project</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('#updateProjectForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("projects.update", $project->id) }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Toastify({
                    text: response.message || "Project Created Successfully",
                    duration: 4000,
                    gravity: 'bottom',
                    position: 'left',
                    backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                }).showToast();
            }
        });
    });
</script>