@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<style>
    .activity-content .diff-container {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        padding: 10px;
        border-radius: 5px;
        background-color: #f8f9fa;
    }

    .diff-container .old-value {
        flex: 1;
        border-right: 1px solid #ddd;
        padding-right: 10px;
    }

    .diff-container .new-value {
        flex: 1;
        padding-left: 10px;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
        <h1> {{ $toc->title}} Revision History</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Revision History</li>
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
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Revision History <span>| Recent</span></h5>

                    <div class="activity">
                        @foreach ($revisions as $revision)
                        <div class="activity-item d-flex">
                            <div class="activite-label">{{ $revision->created_at->diffForHumans() }}</div>
                            <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                            <div class="activity-content">
                                <strong>{{ $revision->userResponsible()->name }} Updated Field:</strong> <span class="text-success">Theory of change {{ $revision->key }}</span>
                                <hr>
                                <strong>Changes:</strong><br>
                                @if (!empty($revision->diffHtml))
                                <!-- Render the diff with customized classes for added/removed changes -->
                                <div style="padding: 10px; border-radius: 5px;">
                                    {!! $revision->diffHtml !!}
                                </div>
                                @else
                                <!-- Fallback for plain values with vertical separator -->
                                <div class="diff-container" style="display: flex; align-items: flex-start; gap: 20px; padding: 10px; border-radius: 5px;">
                                    <div class="old-value" style="flex: 1; border-right: 1px solid #ddd; padding-right: 10px;">
                                        <strong>Old Value:</strong><br>
                                        {!! $revision->old_value !!}
                                    </div>
                                    <div class="new-value" style="flex: 1; padding-left: 10px;">
                                        <strong>New Value:</strong><br>
                                        {!! $revision->new_value !!}
                                    </div>
                                </div>
                                @endif
                                <hr>
                                <!-- Revert Button -->
                                <form action="{{ route('theory.revert', ['id' => $toc->id, 'revisionId' => $revision->id]) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-primary btn-sm mt-2">
                                        <i class="bi bi-arrow-counterclockwise"></i> Revert
                                    </button>
                                </form>
                            </div>
                        </div><!-- End activity item-->
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')