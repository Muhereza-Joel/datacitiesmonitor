@include('layouts.header')
@include('layouts.topBar')
@include('layouts.leftPane')

@php
use \Carbon\Carbon;
@endphp

<style>
    /* Theme-aware left border using Bootstrap CSS variable */
    .project-section-title {
        border-left: 5px solid var(--bs-primary);
        padding-left: 15px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    /* Interactive project cards that work on light and dark backgrounds */
    .project-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid var(--bs-border-color-translucent);
        border-radius: 10px;
    }

    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.15);
    }

    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .card-actions {
        border-top: 1px solid var(--bs-border-color-translucent);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 10px 15px;
        border-radius: 0 0 10px 10px;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1 class="text-body-emphasis">Manage Projects</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item active">Manage Projects</li>
                    </ol>
                </nav>
            </div>

            <div class="text-end w-50 pt-3">
                @can('create', \App\Models\Project::class)
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Create New Project
                </a>
                @endcan
            </div>
        </div>
    </div>@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <section class="section dashboard mt-4">
        <div class="row">
            @forelse($projects as $project)
            <div class="col-xxl-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 project-card bg-body text-body">
                    <div class="card-body pt-4 position-relative">

                        <div class="status-badge">
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
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-body-secondary text-primary" style="width: 45px; height: 45px;">
                                <i class="bi bi-folder" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>

                        <h5 class="card-title p-0 mb-2 text-body-emphasis">{{ $project->name }}</h5>

                        <p class="card-text small text-secondary mb-3">
                            {{ Str::limit($project->description, 90) }}
                        </p>

                        <p class="text-secondary small mb-0 mt-auto">
                            <i class="bi bi-calendar3"></i>
                            {{ Carbon::parse($project->start_date)->format('M d, Y') }} -
                            {{ Carbon::parse($project->end_date)->format('M d, Y') }}
                        </p>
                    </div>

                    <div class="card-actions bg-body-tertiary">
                        @can('viewAny', $project)
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-info btn-sm" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        @endcan
                        @can('update', $project)
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            @empty
            {{-- Custom Theme-Aware Empty State Message --}}
            <div class="col-12">
                <div class="card p-5 text-center bg-body text-body style=" border: 1px dashed var(--bs-border-color);">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-diagram-3 text-secondary" style="font-size: 3.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-body-emphasis mb-2">No Active Projects Found</h5>
                        <p class="text-secondary mx-auto mb-4" style="max-width: 450px;">
                            There are no projects recorded for your organization right now. Creating a project allows you to organize tracking operations and map target metrics easily.
                        </p>
                        <a href="{{ route('projects.create') }}" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-1"></i> Create Your First Project
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </section>

</main>@include('layouts.footer')

<script>
</script>