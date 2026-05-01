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
        <h1>Update Area of Focus</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                <li class="breadcrumb-item active">Update Area of Focus</li>
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
                        <form id="updateAreaOfFocusForm" class="needs-validation" novalidate method="post">
                            @csrf
                            @method('PUT')

                            <div class="form-group my-2">
                                <label for="project_id">Project</label>
                                <select name="project_id" id="project_id" class="form-control" required>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ $areaOfFocus->project_id == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group my-2">
                                <label for="name">Name</label>
                                <textarea name="name" required class="form-control">{{ $areaOfFocus->name }}</textarea>
                            </div>

                            <div class="form-group my-2">
                                <label for="description">Description</label>
                                <textarea rows="8" name="description" required class="form-control">{{ $areaOfFocus->description }}</textarea>
                            </div>

                            <div class="form-group my-2">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="active" {{ $areaOfFocus->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $areaOfFocus->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary">Update Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>
    $('#updateAreaOfFocusForm').on('submit', function(event) {
        event.preventDefault();
        if (this.checkValidity() === true) {
            $.ajax({
                url: '{{ route("areas-of-focus.update", $areaOfFocus->id) }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                    }).showToast();
                    setTimeout(() => {
                        window.location.href = "{{ route('areas-of-focus.index') }}";
                    }, 1500);
                },
                error: function(jqXHR) {
                    alert('Update failed: ' + (jqXHR.responseJSON.message || 'Unknown error'));
                }
            });
        }
    });
</script>