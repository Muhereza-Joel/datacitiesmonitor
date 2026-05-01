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
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Manage Projects</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item active">Manage Projects</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-3">

                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">Create New Project</a>

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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body mt-3">
                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Project Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($projects->count() > 0)
                                @foreach($projects as $project)
                                <tr>
                                    <td><strong>{{ $project->name }}</strong></td>
                                    <td>{{ Str::limit($project->description, 50) }}</td>
                                    <td>
                                        <small>
                                            {{ Carbon::parse($project->start_date)->format('M d, Y') }} -
                                            {{ Carbon::parse($project->end_date)->format('M d, Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        @switch($project->status)
                                        @case('active')
                                        <span class="badge bg-success">Active</span>
                                        @break
                                        @case('planned')
                                        <span class="badge bg-info text-dark">Planned</span>
                                        @break
                                        @case('completed')
                                        <span class="badge bg-primary">Completed</span>
                                        @break
                                        @case('on hold')
                                        <span class="badge bg-warning text-dark">On Hold</span>
                                        @break
                                        @default
                                        <span class="badge bg-secondary">{{ ucfirst($project->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No projects found for your organization.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>

</script>