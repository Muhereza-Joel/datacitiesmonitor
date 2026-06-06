@include('layouts.header')
@include('layouts.topBar')
@include('layouts.leftPane')

@php
use \Carbon\Carbon;
@endphp

<style>
    /* Theme-aware left border using Bootstrap CSS variable */
    .report-section-title {
        border-left: 5px solid var(--bs-primary);
        padding-left: 15px;
        margin-bottom: 20px;
        font-weight: 700;
    }

    /* Interactive report cards that work on light and dark backgrounds */
    .report-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid var(--bs-border-color-translucent);
        border-radius: 10px;
    }

    .report-card:hover {
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

    /* CSS Line-Clamping engine to restrict descriptions to exactly 2 rows */
    .clamp-description {
        display: -webkit-box;
        /* Standard and vendor-prefixed line-clamp for broader compatibility */
        line-clamp: 2;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 3rem;
        /* Ensures consistent grid card height alignments */
    }
</style>

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1 class="text-body-emphasis">All Reports</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item active">Manage Reports</li>
                    </ol>
                </nav>
            </div>

            <div class="text-end w-50 pt-3">

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

    <section class="section dashboard mt-4">
        {{-- Group reports by creation month & year to manage visual density --}}
        @forelse($reports->groupBy(function($item) { return $item->created_at->format('F Y'); }) as $dateGroup => $groupedItems)

        <div class="row mt-3">
            <div class="col-12">
                <h5 class="report-section-title text-body-emphasis d-flex align-items-center">
                    <i class="bi bi-calendar-range me-2 text-primary"></i>
                    {{ $dateGroup }}
                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill ms-2" style="font-size: 0.75rem;">
                        {{ $groupedItems->count() }} {{ Str::plural('Report', $groupedItems->count()) }}
                    </span>
                </h5>
            </div>
        </div>

        <div class="row">
            @foreach($groupedItems as $report)
            <div class="col-xxl-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 report-card bg-body text-body">
                    <div class="card-body pt-4 position-relative">

                        <div class="status-badge">
                            @switch(strtolower($report->status))
                            @case('submitted')
                            @case('approved')
                            <span class="badge bg-success">Submitted</span>
                            @break
                            @case('draft')
                            <span class="badge bg-warning text-dark">Draft</span>
                            @break
                            @case('pending')
                            @case('review')
                            <span class="badge bg-info text-dark">In Review</span>
                            @break
                            @default
                            <span class="badge bg-secondary">{{ ucfirst($report->status) }}</span>
                            @endswitch
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-body-secondary text-primary" style="width: 45px; height: 45px;">
                                <i class="bi bi-file-earmark-bar-graph" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h6 class="card-title p-0 mb-1 text-body-emphasis">
                            {{ Carbon::parse($report->reporting_month)->format('F Y') }} Report
                        </h6>

                        <p class="text-secondary mb-3 clamp-description">
                            {{ filled(trim($report->description ?? '')) ? $report->description : 'No report description provided.' }}
                        </p>

                        <p class="small text-secondary mb-3">
                            <i class="bi bi-person"></i> Prepared by: <span class="fw-medium">{{ $report->preparedBy->name }}</span>
                        </p>

                        <p class="text-secondary small mb-0 mt-auto" style="font-size: 0.75rem;">
                            <i class="bi bi-clock"></i> Created: {{ $report->created_at->format('M d, Y @ h:i A') }}
                        </p>
                    </div>

                    <div class="card-actions bg-body-tertiary">
                        @can('view', $report)
                        <a href="{{ route('reports.showSubmittedReports', $report->id) }}" class="btn btn-outline-info btn-sm" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        @endcan

                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @empty
        {{-- Custom Theme-Aware Empty State Message if database results count is 0 --}}
        <div class="row">
            <div class="col-12">
                <div class="card p-5 text-center bg-body text-body" style="border: 1px dashed var(--bs-border-color);">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-journal-x text-secondary" style="font-size: 3.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-body-emphasis mb-2">No Reports Available</h5>
                        <p class="text-secondary mx-auto mb-4" style="max-width: 450px;">
                            You have not logged any operational monthly statements yet. Generate your first performance report to keep project documented properly.
                        </p>

                    </div>
                </div>
            </div>
        </div>
        @endforelse

        @if($reports->hasPages())
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
                {{ $reports->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </section>

</main>

@include('layouts.footer')