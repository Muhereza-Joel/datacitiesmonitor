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

<!-- projects/show.blade.php -->
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Project Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">View Project</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title">{{ $project->name }}</h5>
                        <p class="small fst-italic">{{ $project->description }}</p>

                        <h5 class="card-title">Timeline & Status</h5>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Start Date</div>
                            <div class="col-lg-9 col-md-8">{{ Carbon::parse($project->start_date)->format('d M, Y') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">End Date</div>
                            <div class="col-lg-9 col-md-8">{{ Carbon::parse($project->end_date)->format('d M, Y') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'planned' ? 'info' : 'secondary') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary btn-sm">Edit Project</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


@include('layouts.footer')

<script>

</script>