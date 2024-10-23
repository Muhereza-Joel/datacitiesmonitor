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
            <div class="text-start w-25">
                <h1>Indicator Details</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Indicators</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-75 mt-2">
                <div class="btn-group g-1" role="group" aria-label="Administrator Actions">
                    @if(Gate::allows('create', App\Models\Response::class))
                    <a href="{{ route('indicators.export.single', $indicator->id) }}" class="btn btn-primary btn-sm">Export As Excel</a>
                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Add Responses To This Indicator." class="btn btn-primary btn-sm mx-2" href="{{ route('indicators.response.create', $indicator->id) }}"><i class="bi bi-plus-circle"></i> Add Responses</a>
                    @endif

                    @if(Gate::allows('update', Auth::user(), $indicator))
                    <a class="btn btn-primary btn-sm mx-2" href="{{ route('indicators.edit', $indicator->id) }}"><i class="bi bi-pencil px-1"></i>Edit Indicator</a>
                    @endif

                    <a class="btn btn-primary btn-sm" href="{{ route('indicator.responses', $indicator->id) }}">Indicator Responses</a>

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
                        <input type="hidden" id="currentIndicator" value="{{ $indicator->id}}">
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

                <div class="card mt-2">
                    <div class="card-header">Indicator Progress Over Time</div>
                    <div class="card-body">
                        <canvas id="progressChart"></canvas>
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

                <div class="card mt-2">
                    <div class="card-header">Indicator Status Distribution</div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>


                @if(Gate::allows('create', App\Models\Archive::class))
                <div class="card my-2">
                    <div class="card-body">
                        <button class="btn btn-primary btn-sm mt-2" {{ $indicator->status !== 'archived' ? 'disabled' : ''}} id="archiveIndicatorBtn">Move Indicator To Archive</button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveModalLabel">Move Indicator to Archive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="archiveForm">
                        @csrf
                        <div class="mb-3">
                            <label for="archiveSelect" class="form-label">Select Archive</label>
                            <select class="form-select" id="archiveSelect" name="archive_id" required>
                                <option value="" disabled selected>Select an archive</option>
                                <!-- Archive options will be populated here via AJAX -->
                            </select>
                        </div>
                        <input type="hidden" name="indicator_id" id="indicatorId">
                        <button type="submit" class="btn btn-primary btn-sm">Move to Archive</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {
        const $archiveModal = $('#archiveModal');
        const $archiveSelect = $('#archiveSelect');
        const $indicatorId = $('#indicatorId');

        // Fetch archives when the button is clicked
        $('#archiveIndicatorBtn').click(function() {
            // Replace this URL with your endpoint that returns archives
            $.ajax({
                url: '/organisation/archives', // Ensure this endpoint returns a JSON array of archives
                method: 'GET',
                success: function(data) {
                    // Clear existing options
                    $archiveSelect.empty().append('<option value="" disabled selected>Select an archive</option>');

                    // Populate the select box with archive options
                    $.each(data, function(index, archive) {
                        $archiveSelect.append(new Option(archive.title, archive.id)); // Assuming 'id' and 'title' exist
                    });

                    // Set the indicator ID (you may need to set this value before opening the modal)
                    $indicatorId.val("{{ $indicator->id }}"); // Set the indicator ID here
                    $archiveModal.modal('show'); // Show the modal
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching archives:', error);
                }
            });
        });

        // Handle form submission
        $('#archiveForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            const formData = $(this).serialize();
            const archiveId = $archiveSelect.val();
            const indicatorId = $indicatorId.val();
            const url = `/archives/${archiveId}/move-indicator/${indicatorId}`; // Adjust URL based on your routes

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(data) {
                    Toastify({
                        text: "Indicator Archived Successfully",
                        duration: 3000,
                        gravity: 'bottom', // Position the toast at the bottom
                        position: 'left', // Align toast to the left
                        backgroundColor: '#28a745',
                    }).showToast();

                    setTimeout(function() {
                        window.location.replace('/archives');
                    }, 1500);
                },
                error: function(xhr, status, error) {
                    console.error('Error moving indicator to archive:', error);
                    alert('Failed to move indicator: ' + error.responseText);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        var formattedBaselineValue = 0;
        var formattedTargetValue = 100;

        // Initialize the progress chart variable
        let progressChart;

        // Fetch and update the chart data dynamically
        const indicatorId = $('#currentIndicator').val();
        const lineGraphUrl = `/indicator/${indicatorId}/graph/line`;

        $.ajax({
            url: lineGraphUrl,
            method: 'GET',
            success: function(response) {
                if (response && response.labels && response.data && response.baseline !== undefined && response.target !== undefined) {
                    // Ensure baseline and target are numbers
                    const baselineValue = Number(response.baseline);
                    const targetValue = Number(response.target);

                    // Check if they are valid numbers
                    if (!isNaN(baselineValue) && !isNaN(targetValue)) {
                        // Format baseline and target to 1 decimal place
                        formattedBaselineValue = baselineValue.toFixed(1);
                        formattedTargetValue = targetValue.toFixed(1);

                        // Create the line chart for Progress Over Time
                        const progressData = {
                            labels: response.labels,
                            datasets: [{
                                label: "Progress",
                                data: response.data,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                fill: true,
                                tension: 0.3,
                            }]
                        };

                        // Determine min and max for y-axis
                        const yMin = Math.min(baselineValue, targetValue); // Ensure minimum is the lesser of baseline or target
                        const yMax = Math.max(baselineValue, targetValue); // Ensure maximum is the greater of baseline or target

                        progressChart = new Chart(document.getElementById('progressChart'), {
                            type: 'line',
                            data: progressData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Time Period',
                                        }
                                    },
                                    y: {
                                        beginAtZero: false,
                                        min: yMin, // Set minimum value to the lesser of baseline or target
                                        max: yMax, // Set maximum value to the greater of baseline or target
                                        title: {
                                            display: true,
                                            text: 'Current State',
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                if (value === parseFloat(formattedBaselineValue)) {
                                                    return `Baseline: ${formattedBaselineValue}`;
                                                }
                                                if (value === parseFloat(formattedTargetValue)) {
                                                    return `Target: ${formattedTargetValue}`;
                                                }
                                                return value.toFixed(1);
                                            },
                                        },
                                    }
                                }
                            }
                        });

                        // Calculate covered and remaining values for pie chart
                        const currentState = response.data[response.data.length - 1] || 0; // Get the latest progress value
                        let coveredValue, remainingValue;

                        if (baselineValue > targetValue) {
                            // If baseline is greater than target, we need to adjust the calculation logic
                            coveredValue = Math.max(baselineValue - currentState, 0); // Ensure covered is not negative
                            remainingValue = Math.max(baselineValue - targetValue - coveredValue, 0); // Ensure remaining is not negative
                        } else {
                            // Normal case when target is greater than baseline
                            coveredValue = Math.min(currentState, targetValue);
                            remainingValue = Math.max(targetValue - coveredValue, 0); // Ensure remaining is not negative
                        }

                        // Update the pie chart data based on the calculated values
                        const statusData = {
                            labels: ["Covered", "Remaining"],
                            datasets: [{
                                data: [coveredValue, remainingValue],
                                backgroundColor: ['#a2e8b5', '#dc3545'], // Light green for covered, red for remaining
                            }]
                        };

                        // Pie Chart for Indicator Status Distribution
                        const statusChart = new Chart(document.getElementById('statusChart'), {
                            type: 'pie',
                            data: statusData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                }
                            }
                        });

                    } else {
                        console.error('Baseline or target is not a valid number:', response.baseline, response.target);
                    }
                } else {
                    console.error('Invalid response format:', response);
                }

            },
            error: function(xhr, status, error) {
                console.error('Error fetching progress data:', error);
            }
        });

    });
</script>