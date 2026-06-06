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

    /* Interactive cards that work on light and dark backgrounds */
    .focus-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid var(--bs-border-color-translucent);
        border-radius: 10px;
    }

    .focus-card:hover {
        transform: translateY(-5px);
        /* Subtle shadow adjustment that looks good on both backgrounds */
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

    .project-group-container {
        margin-bottom: 40px;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1 class="text-body-emphasis">Manage Reporting Areas Of Focus For Projects</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item active">Manage Areas Of Focus</li>
                    </ol>
                </nav>
            </div>

            <div class="text-end w-50 pt-3">
                <a href="{{ route('areas-of-focus.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Create Area Of Focus
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
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

    <section class="section dashboard">
        {{-- Grouping Areas of Focus by Project Name --}}
        @forelse($areasOfFocus->groupBy('project.name') as $projectName => $items)
        <div class="project-group-container mt-4">
            <h4 class="project-section-title text-body-emphasis">
                {{ $projectName }}
                <span class="badge bg-primary rounded-pill" style="font-size: 0.8rem;">{{ $items->count() }}</span>
            </h4>

            <div class="row">
                @foreach($items as $item)
                <div class="col-xxl-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 focus-card bg-body text-body">
                        <div class="card-body pt-4 position-relative">
                            <div class="status-badge">
                                <span class="badge {{ $item->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-body-secondary text-primary" style="width: 45px; height: 45px;">
                                    <i class="bi bi-bullseye" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>

                            <h5 class="card-title p-0 mb-2 text-body-emphasis">{{ $item->name }}</h5>

                            <p class="text-secondary small mb-0">
                                <i class="bi bi-calendar3"></i> Created: {{ $item->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="card-actions bg-body-tertiary">
                            <a href="{{ route('areas-of-focus.show', $item->id) }}" class="btn btn-outline-info btn-sm" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('areas-of-focus.edit', $item->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        {{-- Custom Theme-Aware Empty State Message --}}
        <div class="card p-5 text-center bg-body text-body mt-4 style=" border: 1px dashed var(--bs-border-color);">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-folder2-open text-secondary" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-bold text-body-emphasis mb-2">No Active Areas of Focus Found</h5>
                <p class="text-secondary mx-auto mb-4" style="max-width: 420px;">
                    It looks like there is no data recorded yet. Areas of Focus help track critical segments of your projects. Get started by creating your very first entry.
                </p>
                <a href="{{ route('areas-of-focus.create') }}" class="btn btn-primary px-4">
                    <i class="bi bi-plus-circle me-1"></i> Create First Area of Focus
                </a>
            </div>
        </div>
        @endforelse
    </section>

</main>

@include('layouts.footer')