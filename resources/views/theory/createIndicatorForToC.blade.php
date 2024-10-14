@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <h1>Create Indicator For ToC</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Manage Indicators</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
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
                    <div class="card-title">Create New Indicator</div>
                    <div class="alert alert-warning p-2">This indicator you are about to create will belong to <span class="badge bg-primary">{{$myOrganisation['name']}}</span></div>
                    <div class="card-body">
                        <form action="{{ route('indicators.store') }}" class="needs-validation" novalidate id="create-indicator-form" method="post">
                            @csrf
                            <div class="form-group my-2">
                                <label for="indicator-title">Indicator Category</label>
                                <!-- <small class="text-success">This value is optional, because you can add  it later</small> -->
                                <select name="category" id="" class="form-control" required>
                                    <option value="">Select category</option>
                                    <option value="Outcome">Outcome Indicator</option>
                                    <option value="Output">Output Indicator</option>
                                    <option value="Input">Input Indicator</option>
                                    <option value="Activity">Process/Activity Indicator</option>
                                    <option value="Impact">Impact Indicator</option>
                                    <option value="Efficiency">Efficiency Indicator</option>
                                    <option value="Effectiveness">Effectiveness Indicator</option>
                                    <option value="Sustainability">Sustainability Indicators</option>
                                    <option value="Equity">Equity Indicators</option>
                                    <option value="Context">Cross-cutting or Context Indicators</option>
                                    <option value="None">Un Categorised</option>

                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <input type="hidden" name="theory_of_change_id" value="{{$toc_id}}">

                            <div class="form-group my-2">
                                <label for="direction">Progress Direction</label>
                                <select id="direction" name="direction" class="form-control" required>
                                    <option value="increasing" {{ old('direction') === 'increasing' ? 'selected' : '' }}>Increasing</option>
                                    <option value="decreasing" {{ old('direction') === 'decreasing' ? 'selected' : '' }}>Decreasing</option>
                                </select>
                                <div class="invalid-feedback">Please select the direction of progress.</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="indicator-title">Name of Indicator</label>
                                <textarea placeholder="Indicator name goes here..." type="text" name="name" required class="form-control">{{ old('name')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="indicator-title">Indicator Description</label>
                                <textarea placeholder="Indicator description goes here..." type="text" name="indicator_title" required class="form-control">{{ old('indicator_title')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="definition">Definition</label><br>
                                <small class="text-success">How it is calculated</small>
                                <textarea placeholder="Indicator definition goes here" type="text" name="definition" required class="form-control">{{ old('definition')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="baseline">Baseline</label><br>
                                <small class="text-success">What is the current value?</small>
                                <input placeholder="Enter baseline here..." type="number" step=".1" name="baseline" value="{{ old('baseline')}}" required class="form-control">
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="target">Target</label><br>
                                <small class="text-success">What is the target value? Must be greater than the baseline.</small>
                                <input placeholder="Enter target here..." type="number" step=".1" name="target" value="{{ old('target')}}" required class="form-control">
                                <div class="invalid-feedback">This field is required and must be greater than the baseline</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="data-source">Data Source</label><br>
                                <small class="text-success">How will it be measured?</small>
                                <textarea placeholder="Enter target here..." type="text" name="data_source" required class="form-control">{{ old('data_source')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="frequency">Frequency</label><br>
                                <small class="text-success">How often will it be measured?</small>
                                <input placeholder="Enter frequency here..." type="text" name="frequency" value="{{ old('frequency')}}" required class="form-control">
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="responsible">Responsible</label><br>
                                <small class="text-success">Who will measure it?</small>
                                <textarea placeholder="Who is reponsible to measure this indicator" type="text" name="responsible" required class="form-control">{{ old('responsible')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="reporting">Reporting</label><br>
                                <small class="text-success">Where will it be reported?</small>
                                <textarea placeholder="Where will you submit the reports" type="text" name="reporting" required class="form-control">{{ old('reporting')}}</textarea>
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
                <div class="card p-2">
                    <div class="my-2">
                        <a href="{{ route('theory.index')}}" class="btn btn-link">Go back to Theories of Change <i class="bi bi-box-arrow-in-up-right ms-2"></i></a>
                        <a href="{{ route('theory.indicators', $toc_id)}}" class="btn btn-link">View Indicators for this ToC <i class="bi bi-box-arrow-in-up-right ms-2"></i></a>
                    </div>
                    <hr>
                    <div class="card-title ms-4">Instructions</div>
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