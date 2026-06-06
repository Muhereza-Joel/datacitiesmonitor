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
        <h1>Area of Focus Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                <li class="breadcrumb-item active">Area of Focus Details</li>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-muted">Name</div>
                            <div class="col-lg-9 col-md-8 fw-bold">{{ $areaOfFocus->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-muted">Parent Project</div>
                            <div class="col-lg-9 col-md-8">{{ $areaOfFocus->project->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-muted">Description</div>
                            <div class="col-lg-9 col-md-8">{{ $areaOfFocus->description }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-muted">Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge {{ $areaOfFocus->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($areaOfFocus->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            @can('update', $areaOfFocus)
                            <a href="{{ route('areas-of-focus.edit', $areaOfFocus->id) }}" class="btn btn-primary btn-sm">Edit Area of Focus</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>

</script>