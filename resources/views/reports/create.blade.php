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
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Create Report</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item active">Create Report</li>
                    </ol>
                </nav>
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
                    <div class="card-title">Create New Report</div>
                    <div class="alert alert-warning p-2">This report you are about to create will belong to <span class="badge bg-primary">{{$myOrganisation['name']}}</span></div>

                    <div class="card-body">
                        <form action="{{ route('reports.store') }}" class="needs-validation" novalidate id="createReportForm" method="post">
                            @csrf
                            <div class="form-group my-2">
                                <label for="description">Report Description</label>
                                <textarea placeholder="Report description goes here..." type="text" rows="8" name="description" required class="form-control">{{ old('description')}}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="project_id">Project</label>
                                <select name="project_id" id="project_id" class="form-control" required>
                                    <option value="">Select Project</option>
                                    @foreach($myOrganisation->projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label>Reporting Date (Day/Month/Year)</label>
                                <div class="d-flex gap-2">
                                    <!-- Day -->
                                    <select id="day_select" class="form-control" required>
                                        <option value="">Day</option>
                                        @for($d = 1; $d <= 31; $d++)
                                            <option value="{{ sprintf('%02d', $d) }}">{{ $d }}</option>
                                            @endfor
                                    </select>

                                    <!-- Month -->
                                    <select id="month_select" class="form-control" required>
                                        <option value="">Month</option>
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ sprintf('%02d', $m) }}">
                                            {{ Carbon::create()->month($m)->format('F') }}
                                            </option>
                                            @endfor
                                    </select>

                                    <!-- Year -->
                                    <select id="year_select" class="form-control" required>
                                        <option value="">Year</option>
                                        @for($y = Carbon::now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="invalid-feedback">Please select the full date.</div>
                                <!-- Hidden field to store the combined string -->
                                <input type="hidden" name="reporting_month" id="final_reporting_date">
                            </div>

                            <div class="form-group my-2">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                </select>
                                <div class="invalid-feedback">Please select the status.</div>
                            </div>

                            <input type="hidden" value="{{$myOrganisation->id}}" name="organisation_id">

                            <div class="text-start">
                                @can('create', \App\Models\Report::class)
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card p-2">
                    <div class="card-title">Submission Guide</div>
                    <div class="card-body">
                        <ul>
                            <li><strong>Report Description:</strong> Provide a detailed description of the report.</li>
                            <li><strong>Project:</strong> Select the specific project this report applies to.</li>
                            <li><strong>Reporting Date:</strong> Select the exact day, month, and year for the record. This is saved as a complete date string.</li>
                            <li><strong>Status:</strong>Reports are by default draft until submission is added</li>
                            <li><strong>Auto-save:</strong> Ensure all required fields are filled before clicking Save.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    // Handle Datepicker if needed for other fields
    var today = new Date();
    if ($("#startDate, #endDate").length > 0) {
        $("#startDate, #endDate").datepicker({
            minDate: today,
            dateFormat: 'yy-mm-dd'
        });
    }

    $('#createReportForm').on('submit', function(event) {
        event.preventDefault();

        // 1. Combine Day, Month, Year into the hidden input string (Format: YYYY-MM-DD)
        const day = $('#day_select').val();
        const month = $('#month_select').val();
        const year = $('#year_select').val();

        if (day && month && year) {
            $('#final_reporting_date').val(`${year}-${month}-${day}`);
        }

        // 2. Standard Bootstrap Validation
        if (this.checkValidity() === true) {
            let fromData = $(this).serialize();

            $.ajax({
                url: '{{ route("reports.store") }}',
                type: 'POST',
                data: fromData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toastify({
                        text: response.message || "Report Created Successfully",
                        duration: 4000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                    }).showToast();

                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function(jqXHR) { // Added jqXHR parameter
                    let errorMessage = "An error occurred while creating report";
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    }

                    Toastify({
                        text: errorMessage,
                        duration: 4000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: 'linear-gradient(to right, #ff416c, #ff4b2b)',
                    }).showToast();
                }
            });
        } else {
            $(this).addClass('was-validated');
        }
    });

    $(document).ajaxStart(function() {
        $("#loader").removeClass('d-none');
    });

    $(document).ajaxStop(function() {
        $("#loader").addClass('d-none');
    });
</script>