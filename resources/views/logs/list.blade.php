@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<main id="main" class="main">

    <div class="pagetitle mt-3">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Showing All Logs</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Logs</li>
                    </ol>
                </nav>
            </div>
            <div class="text-end w-50">
                <div class="d-flex align-items-center justify-content-end">
                    <h5 class="me-3 mb-0">Filter By:</h5>
                    <form method="GET" action="{{ route('logs.index') }}" class="d-flex">
                        <select name="filter" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="last_three_days" {{ request('filter') == 'last_three_days' ? 'selected' : '' }}>Last Three Days</option>
                            <option value="last_week" {{ request('filter') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="last_month" {{ request('filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </form>
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
            @if($logs->isEmpty())
            <span>No logs found for the selected period.</span>
            @else
            @foreach($logs as $log)
            <div class="col-sm-6">
                <div class="card p-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ $log->user->profile->image_url ?? asset('assets/img/placeholder.png') }}"
                                    alt="User Image"
                                    class="rounded-circle"
                                    width="80"
                                    height="80"
                                    style="margin-right: 15px;">
                                <div>
                                    <strong>Username:</strong> {{ $log->user->name ?? 'Unknown User' }} <br>
                                    <strong>Action Done:</strong> {{ $log->action }} <br>
                                    <strong>Resource:</strong> {{ $log->resource_type }} (ID: {{ $log->resource_id }}) <br>
                                    <strong>Associated IP Address:</strong> {{ $log->ip_address }} <br>
                                    <strong>Browser:</strong> {{ $log->device_browser ? $log->device_browser : 'N/A' }} <br>
                                    <strong>Logged On:</strong> {{ $log->created_at->format('M d, Y \a\t g:ia') }} <br>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="#" class="icon" title="View Details" data-bs-toggle="modal" data-bs-target="#viewLogModal{{ $log->id }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal for viewing log details -->
                <div class="modal fade" id="viewLogModal{{ $log->id }}" tabindex="-1" aria-labelledby="viewLogModalLabel{{ $log->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewLogModalLabel{{ $log->id }}">Log Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <img src="{{ $log->user->profile->image_url ?? asset('assets/img/placeholder.png') }}"
                                        alt="User Image"
                                        class="rounded-circle"
                                        width="200"
                                        height="200"
                                        style="margin-right: 15px;">

                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Username:</strong> {{ $log->user->name ?? 'Unknown User' }} <br>
                                        <strong>Action:</strong> {{ $log->action }} <br>
                                        <strong>IP Address:</strong> {{ $log->ip_address }} <br>
                                        <strong>Hostname:</strong> {{ $log->hostname ?? 'N/A' }} <br>
                                        <strong>Country:</strong> {{ $log->country ?? 'N/A' }} <br>
                                        <strong>City:</strong> {{ $log->city ?? 'N/A' }} <br>
                                        <strong>Region:</strong> {{ $log->region ?? 'N/A' }} <br>
                                        <strong>Coordinates:</strong> {{ $log->loc ?? 'N/A' }} <br>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Organization:</strong> {{ $log->org ?? 'N/A' }} <br>
                                        <strong>Timezone:</strong> {{ $log->timezone ?? 'N/A' }} <br>
                                        <strong>Operating System:</strong> {{ $log->device_os ?? 'N/A' }} <br>
                                        <strong>Architecture:</strong> {{ $log->device_architecture ?? 'N/A' }} <br>
                                        <strong>Browser:</strong> {{ $log->device_browser ?? 'N/A' }} <br>
                                        <strong>Logged On:</strong> {{ $log->created_at->format('M d, Y \a\t g:ia') }}
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif

            <!-- Pagination links -->
            <div class="d-flex justify-content-center">
                {{ $logs->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>

</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {
        // You can add custom JavaScript or jQuery here if needed
    });
</script>