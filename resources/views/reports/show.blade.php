@include('layouts.header')
@include('layouts.topBar')
@include('layouts.leftPane')

@php
use \Carbon\Carbon;
@endphp

<style>
    .report-details-header {
        border-left: 5px solid var(--bs-primary);
        padding-left: 15px;
        margin-bottom: 25px;
    }

    .meta-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .content-block {
        padding: 12px 15px;
        border-radius: 6px;
        white-space: pre-line;
        /* Preserves formatting and spacing if Quill HTML is stripped to plain-text */
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--bs-primary-snuggle, rgba(13, 110, 253, 0.05));
        color: var(--bs-primary);
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
    }
</style>

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-start">
                <h1 class="text-body-emphasis">Report Details</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Manage Reports</a></li>
                        <li class="breadcrumb-item active">Report Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-warning btn-sm me-2">
                    <i class="bi bi-pencil"></i> Edit Report Meta
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to List
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

    <section class="section dashboard mt-4">
        <div class="row g-4">

            <div class="col-lg-4">
                <div class="card h-100 bg-body text-body border shadow-sm">
                    <div class="card-body pt-4">
                        <div class="text-left mb-4">
                            @switch(strtolower($report->status))
                            @case('submitted')
                            @case('approved')
                            <span class="badge bg-success px-3 py-2 rounded-pill">Approved / Submitted</span>
                            @break
                            @case('draft')
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Report Still inDraft Mode</span>
                            @break
                            @default
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($report->status) }}</span>
                            @endswitch
                        </div>

                        <hr class="text-muted">

                        <h6 class="card-title p-0 mb-1 text-body-emphasis">
                            {{ Carbon::parse($report->reporting_month)->format('F Y') }} Report
                        </h6>

                        <p class="text-secondary mb-3">
                            {{ filled(trim($report->description ?? '')) ? $report->description : 'No report description provided.' }}
                        </p>

                        <div class="mb-3">
                            <span class="text-secondary meta-label d-block">Assigned Project</span>
                            <span class="fw-semibold text-body-emphasis">{{ $report->project->name ?? 'Unassigned Project Entity' }}</span>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary meta-label d-block">Parent Organisation</span>
                            <span class="text-body">{{ $report->organisation->name ?? 'System Root Tenant' }}</span>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary meta-label d-block">Prepared By</span>
                            <span class="text-body"><i class="bi bi-person me-1"></i> {{ $report->preparedBy->name ?? 'System Generated Account' }}</span>
                        </div>

                        <div class="mb-0">
                            <span class="text-secondary meta-label d-block">Creation Date and Time</span>
                            <span class="text-body small"><i class="bi bi-clock me-1"></i> {{ $report->created_at->format('M d, Y @ h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card bg-body text-body border shadow-sm">
                    <div class="card-header bg-transparent border-bottom pt-3 pb-2 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold text-body-emphasis mb-0">
                                <i class="bi bi-layers text-primary me-2"></i> Submissions on focus areas linked to project
                            </h5>

                        </div>

                        <a href="{{ route('reports.areas.create', $report->id) }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Submission
                        </a>
                    </div>

                    <div class="card-body pt-3">
                        @if($report->reportAreas->isNotEmpty())
                        <div class="accordion accordion-flush border rounded" id="focusAreasAccordion">
                            @foreach($report->reportAreas as $index => $area)
                            <div class="accordion-item">
                                <h2 class="accordion-header " id="heading-{{ $area->id }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $area->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $area->id }}">
                                        <div class="px-2 d-flex align-items-center justify-content-between w-100 pe-3">
                                            <div>
                                                <span class="badge bg-primary text-white me-2">#{{ $index + 1 }}</span>
                                                <span class="text-body-emphasis">{{ $area->areaOfFocus->name ?? 'Operational Focus Group' }}</span>
                                            </div>
                                            @if($area->status)
                                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill fs-7 py-1 px-2">{{ ucfirst($area->status) }}</span>
                                            @endif
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $area->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $area->id }}" data-bs-parent="#focusAreasAccordion">
                                    <div class="accordion-body bg-light-subtle pt-4">
                                        <div class="row g-3">

                                            @if($area->objective)
                                            <div class="col-12">
                                                <span class="text-secondary meta-label d-block mb-1">Target Objective</span>
                                                <div class="content-block bg-body-secondary text-body-emphasis fw-medium">{!! strip_tags($area->objective) !!}</div>
                                            </div>
                                            @endif

                                            @if($area->activities_conducted)
                                            <div class="col-md-6">
                                                <span class="text-secondary meta-label d-block mb-1">Activities Conducted</span>
                                                <div class="content-block bg-body-tertiary small text-body">{!! strip_tags($area->activities_conducted) !!}</div>
                                            </div>
                                            @endif

                                            @if($area->achievements)
                                            <div class="col-md-6">
                                                <span class="text-success meta-label d-block mb-1">Key Achievements</span>
                                                <div class="content-block bg-success-subtle text-success-emphasis small">{!! strip_tags($area->achievements) !!}</div>
                                            </div>
                                            @endif

                                            @if($area->challenges)
                                            <div class="col-md-6">
                                                <span class="text-danger meta-label d-block mb-1">Encountered Challenges</span>
                                                <div class="content-block bg-danger-subtle text-danger-emphasis small">{!! strip_tags($area->challenges) !!}</div>
                                            </div>
                                            @endif

                                            @if($area->risks)
                                            <div class="col-md-6">
                                                <span class="text-warning meta-label d-block mb-1">Identified Risks</span>
                                                <div class="content-block bg-warning-subtle text-warning-emphasis small">{!! strip_tags($area->risks) !!}</div>
                                            </div>
                                            @endif

                                            @if($area->opportunities)
                                            <div class="col-md-6">
                                                <span class="text-info meta-label d-block mb-1">Strategic Opportunities</span>
                                                <div class="content-block bg-info-subtle text-info-emphasis small">{!! strip_tags($area->opportunities) !!}</div>
                                            </div>
                                            @endif

                                            @if($area->action_plans)
                                            <div class="col-md-6">
                                                <span class="text-primary meta-label d-block mb-1">Immediate Action Plans</span>
                                                <div class="content-block bg-primary-subtle text-primary-emphasis small">{!! strip_tags($area->action_plans) !!}</div>
                                            </div>
                                            @endif

                                            @if($area->recommendations || $area->lessons_learned)
                                            <div class="col-12">
                                                <div class="row g-2">
                                                    @if($area->lessons_learned)
                                                    <div class="col-md-6">
                                                        <span class="text-secondary meta-label d-block mb-1">Lessons Learned</span>
                                                        <div class="text-body small border rounded p-2 mb-0 bg-body">{!! strip_tags($area->lessons_learned) !!}</div>
                                                    </div>
                                                    @endif
                                                    @if($area->recommendations)
                                                    <div class="col-md-6">
                                                        <span class="text-secondary meta-label d-block mb-1">Future Recommendations</span>
                                                        <div class="text-body small border rounded p-2 mb-0 bg-body">{!! strip_tags($area->recommendations) !!}</div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif

                                            @if($area->stakeholder_feedback)
                                            <div class="col-12 mt-2">
                                                <span class="text-muted small d-block"><i class="bi bi-chat-left-quote me-1"></i> Stakeholder Feedback:</span>
                                                <blockquote class="blockquote blockquote-footer mt-1 mb-0 ps-3 border-start text-secondary fs-6">
                                                    {!! strip_tags($area->stakeholder_feedback) !!}
                                                </blockquote>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5 border rounded-3 border-dashed">
                            <i class="bi bi-clipboard-x text-muted display-4 d-block mb-3"></i>
                            <h6 class="fw-bold text-body-emphasis">No Specific FocusArea Submissions Added</h6>
                            <p class="text-secondary small mx-auto mb-3" style="max-width: 380px;">
                                This parent report does not have specific operational focus updates logged under it yet.
                            </p>

                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>

</main>

@include('layouts.footer')