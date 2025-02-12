@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">
    <form id="moveResponseForm" action="{{ route('save-moved-response') }}" method="POST">
        <div class="pagetitle">
            <div class="row">
                <div class="col-sm-2">
                    <h1>Move responses.</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Organise</li>
                        </ol>
                    </nav>

                </div>
                <div class="col-sm-8"></div>
                <div class="col-sm-2">
                    <div class="text-end pt-2">
                        @if(Gate::allows('create', App\Models\Indicator::class))
                        <button type="button" class="btn btn-primary mt-3" id="openModalButton">
                            Move Response
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- End Page Title -->
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->has('current'))
        <div class="alert alert-danger">
            {{ $errors->first('current') }}
        </div>
        @endif



        <section class="section dashboard">
            <div class="alert alert-info">
                <strong>Take a breath! {{ Auth::user()->name }}</strong>
                <p class="m-0">
                    Please choose an indicator from the list below where you want to move this response to.
                </p>
                <small class="mb-2">
                    Note that you will also have to provide the current state as your moving this response because progress will be recalculated due to this operation
                </small><br>

            </div>
            <div class="row g-1">

                @if($indicators->isEmpty())
                <p class="alert alert-info">No Indicators found...</p>
                @else
                <div class="row g-2">

                    @csrf
                    @method('PUT')
                    <input type="hidden" name="response_id" value="{{ $responseId }}">
                    <input type="hidden" name="selected_indicator" id="selectedIndicator">

                    @foreach($indicators as $indicator)
                    <div class="col-sm-6">
                        <div class="card p-2 status-{{ strtolower($indicator->status) }}">
                            <div class="card-title ms-2 d-flex">
                                <div class="text-start w-75">


                                    @if(session('user.preferences.show_indicator_organisation_logo') === 'true')
                                    <img src="{{ asset($indicator->organisation->logo) }}" class="rounded-circle p-1 me-1" width="30px" height="30px" alt="">
                                    @endif

                                    @if(session('user.preferences.show_indicator_category') === 'true')
                                    @if($indicator->category === "None")
                                    <span class="badge bg-success text-light">Un Categorised</span>
                                    @else
                                    <span class="badge bg-primary text-light">{{ $indicator->category }} indicator</span>
                                    @endif
                                    @endif

                                    @if(session('user.preferences.show_indicator_qualitative_status') === 'true')
                                    <span class="badge bg-secondary text-light">{{$indicator->qualitative_progress}}</span>
                                    @endif

                                    @if(session('user.preferences.show_indicator_response_count') === 'true')
                                    <span class="badge bg-light text-primary">
                                        {{ $indicator->responses_count }} response{{ $indicator->responses_count !== 1 ? 's' : '' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="text-end w-25">
                                    @if($indicator->revisionHistory->isNotEmpty()) <!-- Check if revisions are available -->
                                    <a href="{{ route('indicator.history', $indicator->id) }}" class="icon" title="View Revision History">
                                        <i class="bi bi-clock-history"></i> <!-- Same size for all icons -->
                                    </a>
                                    @endif
                                    @if(Gate::allows('update', $indicator))
                                    <a href="{{ route('indicators.edit', $indicator->id) }}" class="icon" title="Edit Indicator">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endif

                                    @if(Gate::allows('delete', $indicator))
                                    <a href="" class="icon" title="Delete Indicator">
                                        <i class="bi bi-trash text-danger"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                <small class="text-success">Indicator Name</small>
                                <a href="{{ route('indicators.show', $indicator->id) }}" class="two-line-truncate btn-link h6 fw-bold">{{ $indicator->name }}</a>

                                @if(session('user.preferences.show_indicator_create_date') === 'true')
                                <div class="text-muted mt-1">
                                    <small>Created on: {{ $indicator->created_at->format('M d, Y \a\t g:iA') }}</small>
                                </div>
                                @endif

                                @if(session('user.preferences.show_indicator_ruller') === 'true' && $indicator->responses_count > 0)
                                @include('layouts.ruller')
                                @endif

                            </div>

                            <div class="card-footer p-0 py-2">
                                <div class="text-start">
                                    <input type="radio" name="selected_indicator" value="{{ $indicator->id }}" required style="z-index: 997;transform: scale(2.7); position:absolute; top: 50%; left: -10px">
                                    @if($indicator->responses->isNotEmpty() && $indicator->responses->first()->created_at)
                                    <span class="badge bg-light text-primary">
                                        Last Response Added: {{ $indicator->latest_response_date->diffForHumans() }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                    @endforeach
                </div>
                @endif
            </div>
        </section>
        <!-- Bootstrap Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Move</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to move this response? Please enter the current state before proceeding.</p>
                        <div class="alert alert-info">

                            <p class="m-0">
                                {{ Auth::user()->name }}, the current state is required because monitor will have to recaliculate progress depending on baseline, target and progress direction of the indicator you selected!
                            </p>


                        </div>
                        <input type="text" name="current" id="currentStateInput" class="form-control" placeholder="Enter current state" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmMoveButton">Confirm Move</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {
        $("#openModalButton").on("click", function() {
            var selectedIndicator = $("input[name='selected_indicator']:checked").val();

            if (!selectedIndicator) {
                Toastify({
                    text: "Please select an indicator before proceeding.",
                    duration: 10000,
                    gravity: 'bottom',
                    position: 'right',
                    backgroundColor: '#eb3461',
                }).showToast();

                return;
            }

            // If an indicator is selected, open the modal
            $("#confirmModal").modal("show");
        });

        $("#confirmMoveButton").on("click", function() {
            var currentState = $("#currentStateInput").val().trim();

            if (currentState === "") {
                Toastify({
                    text: "Current state is required.",
                    duration: 10000,
                    gravity: 'bottom',
                    position: 'right',
                    backgroundColor: '#eb3461',
                }).showToast();

                return;
            }

            // Set the selected indicator before submitting
            $("#selectedIndicator").val($("input[name='selected_indicator']:checked").val());
            $("#moveResponseForm").submit();
        });
    });
</script>