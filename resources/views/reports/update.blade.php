@include('layouts.header')
@include('layouts.topBar')
@include('layouts.leftPane')

@php
use \Carbon\Carbon;
// Parse the existing date from the report model so we can break it down into select options
$existingDate = $report->reporting_month ? Carbon::parse($report->reporting_month) : null;
$existingDay = old('day', $existingDate ? $existingDate->format('d') : '');
$existingMonth = old('month', $existingDate ? $existingDate->format('m') : '');
$existingYear = old('year', $existingDate ? $existingDate->format('Y') : '');
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

    <div class="pagetitle mt-4">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1 class="text-body-emphasis">Edit Report</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Manage Reports</a></li>
                        <li class="breadcrumb-item active">Edit Report</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>@if(session('success'))
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
        <div class="row g-3">
            <div class="col-sm-8">
                <div class="card p-2 bg-body text-body border">
                    <div class="card-body">
                        <form action="{{ route('reports.update', $report->id) }}" class="needs-validation" novalidate id="editReportForm" method="post">
                            @csrf
                            @method('PUT')

                            <div class="form-group my-3">
                                <label for="description" class="form-label fw-semibold text-body-emphasis">Report Description</label>
                                <textarea placeholder="Report description goes here..." rows="8" name="description" id="description" required class="form-control bg-body text-body">{{ old('description', $report->description) }}</textarea>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-3">
                                <label for="project_id" class="form-label fw-semibold text-body-emphasis">Project</label>
                                <select name="project_id" id="project_id" class="form-select bg-body text-body" required>
                                    <option value="">Select Project</option>
                                    @foreach($myOrganisation->projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $report->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="form-group my-3">
                                <label class="form-label fw-semibold text-body-emphasis">Reporting Date (Day/Month/Year)</label>
                                <div class="d-flex gap-2">
                                    <select id="day_select" class="form-select bg-body text-body" required>
                                        <option value="">Day</option>
                                        @for($d = 1; $d <= 31; $d++)
                                            @php $paddedDay=sprintf('%02d', $d); @endphp
                                            <option value="{{ $paddedDay }}" {{ $existingDay == $paddedDay ? 'selected' : '' }}>{{ $d }}</option>
                                            @endfor
                                    </select>

                                    <select id="month_select" class="form-select bg-body text-body" required>
                                        <option value="">Month</option>
                                        @for($m = 1; $m <= 12; $m++)
                                            @php $paddedMonth=sprintf('%02d', $m); @endphp
                                            <option value="{{ $paddedMonth }}" {{ $existingMonth == $paddedMonth ? 'selected' : '' }}>
                                            {{ Carbon::create()->month($m)->format('F') }}
                                            </option>
                                            @endfor
                                    </select>

                                    <select id="year_select" class="form-select bg-body text-body" required>
                                        <option value="">Year</option>
                                        @for($y = Carbon::now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $existingYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="invalid-feedback">Please select the full date.</div>
                                <input type="hidden" name="reporting_month" id="final_reporting_date" value="{{ old('reporting_month', $report->reporting_month) }}">
                            </div>

                            <div class="form-group my-3">
                                <label for="status" class="form-label fw-semibold text-body-emphasis">Status</label>
                                <select id="status" name="status" class="form-select bg-body text-body" required>
                                    <option value="draft" {{ old('status', $report->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ old('status', $report->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                </select>
                                <div class="invalid-feedback">Please select the status.</div>
                            </div>

                            <input type="hidden" value="{{ $myOrganisation->id }}" name="organisation_id">

                            <div class="text-start mt-4">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary px-3 ms-1">Cancel</a>
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

</main>@include('layouts.footer')

<script>
    $('#editReportForm').on('submit', function(event) {
        event.preventDefault();

        // 1. Re-combine split select values into a valid database layout (YYYY-MM-DD)
        const day = $('#day_select').val();
        const month = $('#month_select').val();
        const year = $('#year_select').val();

        if (day && month && year) {
            $('#final_reporting_date').val(`${year}-${month}-${day}`);
        } else {
            $('#final_reporting_date').val(''); // Empty string drops into invalid states gracefully
        }

        // 2. Form Request Verification
        if (this.checkValidity() === true) {
            let formData = $(this).serialize();

            $.ajax({
                url: '{{ route("reports.update", $report->id) }}',
                type: 'POST', // Handled as POST with @method('PUT') inside form serialization strings
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toastify({
                        text: response.message || "Report Updated Successfully",
                        duration: 4000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                    }).showToast();

                    // setTimeout(function() {
                    //     window.location.href = '{{ route("reports.index") }}';
                    // }, 2000);
                },
                error: function(jqXHR) {
                    let errorMessage = "An error occurred while updating the report";
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