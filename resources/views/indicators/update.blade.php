@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Manage Indicators</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Indicators</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-2">
                <div>
                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Add Responses To This Indicator." class="btn btn-primary btn-sm" href="{{ route('indicators.response.create', $indicator->id) }}"><i class="bi bi-plus-circle"></i> Add Responses</a>
                    <a href="{{ route('indicators.show', $indicator->id) }}" class="btn btn-primary btn-sm px-3">Indicator Details</a>
                    <a href="{{ route('indicator.responses', $indicator->id) }}" class="btn btn-primary btn-sm px-3">View Indicator Responses</a>
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

        <div class="row g-1">
            <div class="col-sm-8">
                <div class="card p-2">
                    <div class="card-title">Update Indicator Details</div>
                    <div class="alert alert-warning p-2">This indicator belongs to <span class="badge bg-primary">{{$myOrganisation['name']}}</span></div>
                    <div class="card-body">
                        <form action="{{ route('indicators.update', $indicator->id) }}" class="needs-validation" novalidate id="create-indicator-form" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group my-2">
                                <label for="indicator-title">Indicator Category</label>
                                <!-- <small class="text-success">This value is optional, because you can add  it later</small> -->
                                <select name="category" id="category" class="form-control" required>
                                    <option value="">Select category</option>
                                    <option value="Outcome" {{ $indicator->category == 'Outcome' ? 'selected' : '' }}>Outcome Indicator</option>
                                    <option value="Output" {{ $indicator->category == 'Output' ? 'selected' : '' }}>Output Indicator</option>
                                    <option value="Input" {{ $indicator->category == 'Input' ? 'selected' : '' }}>Input Indicator</option>
                                    <option value="Activity" {{ $indicator->category == 'Activity' ? 'selected' : '' }}>Process/Activity Indicator</option>
                                    <option value="Impact" {{ $indicator->category == 'Impact' ? 'selected' : '' }}>Impact Indicator</option>
                                    <option value="Efficiency" {{ $indicator->category == 'Efficiency' ? 'selected' : '' }}>Efficiency Indicator</option>
                                    <option value="Effectiveness" {{ $indicator->category == 'Effectiveness' ? 'selected' : '' }}>Effectiveness Indicator</option>
                                    <option value="Sustainability" {{ $indicator->category == 'Sustainability' ? 'selected' : '' }}>Sustainability Indicators</option>
                                    <option value="Equity" {{ $indicator->category == 'Equity' ? 'selected' : '' }}>Equity Indicators</option>
                                    <option value="Context" {{ $indicator->category == 'Context' ? 'selected' : '' }}>Cross-cutting or Context Indicators</option>
                                    <option value="None" {{ $indicator->category == 'None' ? 'selected' : '' }}>Un Categorised</option>
                                </select>

                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="indicator-title">Theory Of Change</label>
                                <select name="theory_of_change_id" id="" class="form-control" required>
                                    <option value="">Select Theory Of Change</option>
                                    @foreach($theories as $theory)
                                    <option value="{{ $theory->id }}"
                                        {{ $indicator->theory_of_change_id == $theory->id ? 'selected' : '' }}>
                                        {{ $theory->title }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="direction">Progress Direction</label>
                                <select id="direction" name="direction" class="form-control" required>
                                    <option value="increasing" {{ $indicator->direction  === 'increasing' ? 'selected' : '' }}>Increasing</option>
                                    <option value="decreasing" {{ $indicator->direction === 'decreasing' ? 'selected' : '' }}>Decreasing</option>
                                </select>
                                <div class="invalid-feedback">Please select the direction of progress.</div>
                            </div>


                            <div class="form-group my-2">
                                <label for="indicator-title">Name of Indicator</label>
                                <textarea placeholder="Indicator name goes here..." type="text" name="name" required class="form-control">{{ $indicator->name}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="indicator-title">Indicator Description</label>
                                <textarea placeholder="Indicator description goes here..." type="text" name="indicator_title" required class="form-control">{{ $indicator->indicator_title }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="definition">Definition</label><br>
                                <small class="text-success">How it is calculated</small>
                                <textarea placeholder="Indicator definition goes here" type="text" name="definition" required class="form-control">{{ $indicator->definition }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="baseline">Baseline</label><br>
                                <small class="text-success">What is the current value?</small>
                                <input placeholder="Enter baseline here..." type="number" step=".1" name="baseline" value="{{ $indicator->baseline }}" required class="form-control">
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="target">Target</label><br>
                                <small class="text-success">What is the target value? Must be greater than the baseline.</small>
                                <input placeholder="Enter target here..." type="number" step=".1" name="target" value="{{ $indicator->target }}" required class="form-control">
                                <div class="invalid-feedback">This field is required and must be greater than the baseline</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="data-source">Data Source</label><br>
                                <small class="text-success">How will it be measured?</small>
                                <textarea placeholder="Enter target here..." type="text" name="data_source" required class="form-control">{{ $indicator->data_source }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="frequency">Frequency</label><br>
                                <small class="text-success">How often will it be measured?</small>
                                <input placeholder="Enter frequency here..." type="text" name="frequency" value="{{ $indicator->frequency }}" required class="form-control">
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="responsible">Responsible</label><br>
                                <small class="text-success">Who will measure it?</small>
                                <textarea placeholder="Who is reponsible to measure this indicator" type="text" name="responsible" required class="form-control">{{ $indicator->responsible }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="reporting">Reporting</label><br>
                                <small class="text-success">Where will it be reported?</small>
                                <textarea placeholder="Where will you submit the reports" type="text" name="reporting" required class="form-control">{{ $indicator->reporting }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <input type="hidden" value="{{$myOrganisation->id}}" name="organisation_id">

                            <div class="text-start">
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card px-3 py-1 mb-2">
                    <div class="card-title">
                        Indicator Status
                        <span class="badge bg-info text-light">{{ $indicator->status }}</span>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('indicators.status.update', $indicator->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="draft" {{ $indicator->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="review" {{ $indicator->status == 'review' ? 'selected' : '' }}>Review</option>
                                    <option value="public" {{ $indicator->status == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="archived" {{ $indicator->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="qualitative_progress" class="form-label">Update Qualitative Progress</label>
                                <select class="form-select" id="qualitative_progress" name="qualitative_progress">
                                    <option value="on track" {{ $indicator->qualitative_progress == 'on track' ? 'selected' : '' }}>On Track</option>
                                    <option value="at risk" {{ $indicator->qualitative_progress == 'at risk' ? 'selected' : '' }}>At Risk</option>
                                    <option value="off track" {{ $indicator->qualitative_progress == 'off track' ? 'selected' : '' }}>Off Track</option>
                                    <option value="completed" {{ $indicator->qualitative_progress == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="not started" {{ $indicator->qualitative_progress == 'not started' ? 'selected' : '' }}>Not Started</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Update Indicator</button>
                        </form>
                    </div>
                </div>

                <div class="card p-2">
                    <div class="card-title ps-4">Guide For Creating Indicators</div>
                    <div class="card-body">
                        <ul>
                            <li><strong>Indicator Description:</strong> Provide a brief and descriptive title for the indicator.</li>
                            <li><strong>Definition:</strong> Explain how the indicator is calculated.</li>
                            <li><strong>Progress Direction:</strong> Choose increasing if the baseline is less than the target else choose decreasing if baseline is greater than target</li>
                            <li><strong>Baseline:</strong> Enter the current value of the indicator.</li>
                            <li><strong>Target:</strong> Set the target value. It must be greater than the baseline.</li>
                            <li><strong>Data Source:</strong> Specify how the data will be collected.</li>
                            <li><strong>Frequency:</strong> Indicate how often the data will be collected (e.g., monthly, quarterly).</li>
                            <li><strong>Responsible:</strong> Identify who is responsible for measuring the indicator.</li>
                            <li><strong>Reporting:</strong> Mention where the results will be reported.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {
        // Function to validate baseline and target based on the selected direction
        function validateBaselineTarget() {
            var direction = $('#direction').val();
            var baseline = parseFloat($('input[name="baseline"]').val());
            var target = parseFloat($('input[name="target"]').val());

            if (baseline === target) {
                // Allow the scenario where baseline and target are equal
                return true;
            }

            if (direction === 'increasing' && baseline >= target) {
                Toastify({
                    text: "For an increasing direction, the target must be greater than the baseline.",
                    duration: 10000,
                    gravity: 'bottom',
                    position: 'left',
                    backgroundColor: '#28a745',
                }).showToast();

                return false;
            }

            if (direction === 'decreasing' && baseline <= target) {
                Toastify({
                    text: "For a decreasing direction, the baseline must be greater than the target.",
                    duration: 10000,
                    gravity: 'bottom',
                    position: 'left',
                    backgroundColor: '#28a745',
                }).showToast();

                return false;
            }

            return true;
        }

        // Validate the form on submission
        $('#create-indicator-form').on('submit', function(e) {
            if (!validateBaselineTarget()) {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });

        // Revalidate when direction changes
        $('#direction').on('change', function() {
            validateBaselineTarget();
        });
    });
</script>