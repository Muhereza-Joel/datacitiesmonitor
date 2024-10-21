@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<style>
    .status-draft {
        border-top: 8px solid #fc03a1;
        border-left: 3px solid #fc03a1;
    }

    .status-review {
        border-top: 8px solid #0a1157;
        border-left: 3px solid #0a1157;
    }

    .status-public {
        border-top: 8px solid green;
        border-left: 3px solid green;
    }

    .status-archived {
        border-top: 8px solid #1cc9be;
        border-left: 3px solid #1cc9be;
    }

    .status-key {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .status-key span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-key .key-draft {
        width: 20px;
        height: 10px;
        background-color: #fc03a1;
    }

    .status-key .key-review {
        width: 20px;
        height: 10px;
        background-color: #0a1157;
    }

    .status-key .key-public {
        width: 20px;
        height: 10px;
        background-color: green;
    }

    .status-key .key-archived {
        width: 20px;
        height: 10px;
        background-color: #1cc9be;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle mt-3">

        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Showing Indicator Details</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('archives.index') }}">Archives</a></li>
                        <li class="breadcrumb-item active">Indicators</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 mt-2">
                <div class="btn-group" role="group" aria-label="Administrator Actions">


                    <a class="btn btn-primary btn-sm" href="{{ route('archives.indicator.responses', $indicator->indicator_id) }}">Indicator Responses</a>
                    <a class="btn btn-primary btn-sm mx-2" href="{{ route('archives.show', $indicator->archive_id) }}">Go back to archive</a>

                </div>

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
        <div class="row g-2">
            <div class="col-sm-8">

                <div class="card">
                    <div class="accordion" id="tocAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingToC">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseToC" aria-expanded="true" aria-controls="collapseToC">
                                    Click To View Theory of Change Details
                                </button>
                            </h2>
                            <div id="collapseToC" class="accordion-collapse collapse" aria-labelledby="headingToC" data-bs-parent="#tocAccordion">
                                <div class="accordion-body">
                                    <p>{!! $indicator->theoryOfChange->description !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card mt-2 status-{{$indicator->status}}">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="text-start w-50">
                                <h5 class="mb-0">Indicator Details</h5>

                            </div>
                            <div class="text-end w-50">
                                <small>Indicator Status</small>
                                <span class="badge bg-info">{{ $indicator->status }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Indicator Category:</div>
                            <div class="col-sm-8">
                                @if($indicator->category === "None")
                                <span class="badge bg-success text-light">Un Categorised</span>
                                @else
                                <span class="badge bg-primary text-light">{{ $indicator->category }} indicator</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Indicator Name:</div>
                            <div class="col-sm-8">{{ $indicator->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Indicator Title:</div>
                            <div class="col-sm-8">{{ $indicator->indicator_title }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Indicator Definition:</div>
                            <div class="col-sm-8">{{ $indicator->definition }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Data Source:</div>
                            <div class="col-sm-8">{{ $indicator->data_source }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Data Collection Frequency:</div>
                            <div class="col-sm-8">{{ $indicator->frequency }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Reporting:</div>
                            <div class="col-sm-8">{{ $indicator->reporting }}</div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <h5>Indicator Status Key</h5>
                    <div class="status-key">
                        <span>
                            <div class="key-draft"></div> Draft
                        </span>
                        <span>
                            <div class="key-review"></div> Review
                        </span>
                        <span>
                            <div class="key-public"></div> Public
                        </span>
                        <span>
                            <div class="key-archived"></div> Archived
                        </span>

                    </div>

                </div>
                <div class="card">
                    <div class="card-header">Indicator Metrics</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Baseline</div>
                            <div class="col-sm-8">{{ $indicator->baseline }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Target</div>
                            <div class="col-sm-8">{{ $indicator->target }}</div>
                        </div>

                        @if($indicator->current_state)
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Current State</div>
                            <div class="col-sm-8">{{ $indicator->current_state }}</div>
                        </div>
                        @endif

                        @if($indicator->qualitative_progress)
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold">Quantitative Progress</div>
                            <div class="col-sm-8">{{ $indicator->qualitative_progress }}</div>
                        </div>
                        @endif


                    </div>
                </div>

            </div>
        </div>
    </section>



</main><!-- End #main -->

@include('layouts.footer')

<script>

</script>