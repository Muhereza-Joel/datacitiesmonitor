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
        <h1>Manage Events</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                <li class="breadcrumb-item active">Manage Events</li>
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
        <div class="alert alert-info alert-dismissible p-1">
            <i class="bi bi-info-circle me-1"></i>
            Create events to schedule and manage your workflow with confidence, making it easier to track progress and timelines. Events created here will belong to your organisation and will be visible on the events calendar.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="row g-2 p-2">

            <div class="col-sm-4">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create New Event</h5>
                        <form id="createEventForm" class="needs-validation" method="post" novalidate>
                            <div class="mb-3">
                                <label for="event" class="form-label">Event</label>
                                <div id="quillEditor" class="" style="height: 200px;"></div>
                                <textarea id="event" name="event" style="display:none;" required></textarea>
                                <div class="invalid-feedback" id="quillInvalidFeedback">This field is required</div>
                            </div>

                            <div class="mb-3">
                                <label for="visibility">Visible To</label><br>
                                <select name="visibility" id="visibility" required class="form-control">
                                    <option value="">Choose who sees your event</option>
                                    <option value="all">All Users</option>
                                    <option value="internal">Members of my organisation</option>
                                    <option value="external">Only members of other organisations</option>
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                                <hr>
                                <div class="accordion" id="eventVisibilityAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingVisibility">
                                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisibility" aria-expanded="false" aria-controls="collapseVisibility">
                                                Click, to read more about event visibility.
                                            </button>
                                        </h2>
                                        <div id="collapseVisibility" class="accordion-collapse collapse" aria-labelledby="headingVisibility" data-bs-parent="#eventVisibilityAccordion">
                                            <div class="accordion-body">
                                                <div class="event-visibility">
                                                    <h6 class="font-weight-bold">Event Visibility Options</h6>
                                                    <p>Choosing who sees your event is crucial for effective communication and engagement. Please select an appropriate visibility option:</p>
                                                    <ul>
                                                        <li><strong>All Users</strong>: Visible to everyone, including both your organization members and non-members.</li>
                                                        <li><strong>Members of My Organization</strong>: Only visible to members within your organization.</li>
                                                        <li><strong>Only Members of Other Organizations</strong>: Exclusively visible to members of other organizations.</li>
                                                    </ul>
                                                    <p>This ensures that your events are targeted to the right audience, maximizing their impact and relevance.</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="mb-3">
                                <label for="active">Show Event On Calendar</label>
                                <select name="active" id="active" class="form-control" required>
                                    <option value="">Select Activation Status.</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input autocomplete="off" type="text" class="form-control" id="startDate" name="start_date" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input autocomplete="off" type="text" class="form-control" id="endDate" name="end_date" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">
                            <div>
                                Showing All Events
                            </div>
                        </h5>

                        <div id="loader" class="d-none text-center">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <!-- Default Card Layout -->
                        <div class="row g-1" id="events-cards">
                            @if(count($events) > 0)
                            @foreach($events as $event)
                            <div class="col-sm-12">
                                <div class="card" id="events-card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $event['active'] ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $event['active'] ? 'Visible on calendar' : 'Not visible on calendar' }}
                                        </span>

                                        <div class="d-flex ps-1">
                                            <a href="{{ route('events.edit', $event->id) }}" class="me-1">
                                                <i class="bi bi-pencil-square"></i> <!-- Edit icon -->
                                            </a>
                                            <a href="" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $event->id }}"><i class="bi bi-trash text-danger"></i></a>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <p>{!! $event['event'] !!}</p>
                                        <hr>
                                        <small>
                                            <i class="bi bi-clock"></i> <!-- Clock icon -->
                                            <strong>Added On:</strong> {{ Carbon::parse($event['created_at'])->format('F j, Y, g:i A') }}
                                        </small><br>
                                        <small>
                                            <i class="bi bi-calendar-event"></i> <!-- Calendar icon -->
                                            <strong>Start Date:</strong> {{ Carbon::parse($event['start_date'])->format('F j, Y') }}
                                        </small><br>
                                        <small>
                                            <i class="bi bi-calendar-event"></i> <!-- Calendar icon -->
                                            <strong>End Date:</strong> {{ Carbon::parse($event['end_date'])->format('F j, Y') }}
                                        </small>
                                        <p>
                                            <i class="bi bi-eye"></i> <!-- Eye icon -->
                                            <strong>Visible To:</strong> {{ $event['visibility'] }} members
                                        </p>

                                    </div>

                                </div>

                                <div class="modal fade" id="deleteModal{{ $event->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $event->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $event->id }}">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this event?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" id="delete-form{{ $event->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form{{ $event->id }}').submit();">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    No events available to show.
                                </div>
                            </div>
                            @endif
                        </div><!-- End Default Card Layout -->

                        <!-- Pagination links -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $events->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>
    $(document).ready(function() {
        var quill = new Quill('#quillEditor', {
            theme: 'snow'
        });

        $('#createEventForm').on('submit', function(event) {
            event.preventDefault();
            var quillContent = quill.root.innerHTML.trim();
            $('#event').val(quillContent);

            // Custom validation for Quill editor
            if (quillContent === '' || quillContent === '<p><br></p>') {
                $('#quillInvalidFeedback').show();
                $('#quillEditor').addClass('is-invalid');
                event.preventDefault();
            } else {
                $('#quillInvalidFeedback').hide();
                $('#quillEditor').removeClass('is-invalid');
            }

            if (this.checkValidity() === true) {
                let fromData = $(this).serialize();

                $.ajax({
                    url: '{{ route("events.store") }}',
                    type: 'POST',
                    data: fromData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        Toastify({
                            text: response.message || "Row Created Successfully",
                            duration: 4000,
                            gravity: 'bottom',
                            position: 'left',
                            backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                        }).showToast();

                        setTimeout(function() {
                            window.location.reload();
                        }, 2000)

                    },
                    error: function() {

                        if (jqXHR.status === 500) {
                            Toastify({
                                text: jqXHR.responseJSON.message || "An error occurred while creating event",
                                duration: 4000,
                                gravity: 'bottom',
                                position: 'left',
                                backgroundColor: 'linear-gradient(to right, #ff416c, #ff4b2b)',
                            }).showToast();


                        }
                    }
                });
            }
        });

        var today = new Date();
        $("#startDate, #endDate").datepicker({
            minDate: today,
            dateFormat: 'yy-mm-dd'
        });

    });

    $(document).ajaxStart(function() {
        $("#loader").removeClass('d-none');
    });

    $(document).ajaxStop(function() {
        $("#loader").addClass('d-none');
    });
</script>