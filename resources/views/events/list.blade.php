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

    <section class="section dashboard">
        <div class="alert alert-info alert-dismissible">
            <i class="bi bi-info-circle me-1"></i>
            Create events to schedule and manage your workflow with confidence, making it easier to track progress and timelines. Events created here will belong to your organisation and will be visible on the events calendar.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="row g-2 p-2">
            @if($action !== 'edit')
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
            @endif
            @if($action === 'edit')
            <div class="col-sm-4">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Event Details</h5>
                        <form id="updateEventForm" class="needs-validation" method="post" novalidate>
                            <input type="hidden" name="id" value="{{$eventDetails['id']}}">
                            <div class="mb-3">
                                <label for="event" class="form-label">Event</label>
                                <div id="quillEditor" class="" style="height: 200px;">{!!$eventDetails['event']!!}</div>
                                <textarea id="event" name="event" style="display:none;" required></textarea>
                                <div class="invalid-feedback" id="quillInvalidFeedback">This field is required</div>
                            </div>

                            <div class="mb-3">
                                <label for="visibility">Visible To</label><br>
                                <select name="visibility" id="visibility" required class="form-control">
                                    <option value="">Choose who sees your event</option>
                                    <option value="all" {{ $eventDetails['viewer'] == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="internal" {{ $eventDetails['viewer'] == 'internal' ? 'selected' : '' }}>Members of my organisation</option>
                                    <option value="external" {{ $eventDetails['viewer'] == 'external' ? 'selected' : '' }}>Only members of other organisations</option>
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
                                    <option value="1" {{ $eventDetails['active'] == true ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $eventDetails['active'] == false ? 'selected' : '' }}>No</option>
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input value="{{$eventDetails['start_date']}}" autocomplete="off" type="text" class="form-control" id="startDate" name="startDate" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input value="{{$eventDetails['end_date']}}" autocomplete="off" type="text" class="form-control" id="endDate" name="endDate" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Update Event Details</button>
                        </form>

                    </div>
                </div>
            </div>
            @endif
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">
                            <div>
                                Showing All Events
                            </div>
                            <div class="w-25"></div>
                            <div class="ms-3">
                                Filter By:
                            </div>
                            <div class="ms-2">
                                <select id="eventFilter" class="form-control">
                                    <option value="all">All - public</option>
                                    <option value="internal">Internal - private</option>
                                    <option value="external">External - public</option>
                                </select>
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
                            <div class="col-md-4">
                                <div class="card" id="events-card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">
                                            {{ $event['active'] ? 'Visible on calendar' : 'Not visible on calendar' }}
                                        </span>
                                        <div class="d-flex ps-1">
                                            <a href="/dashboard/manage-events/?action=edit&id={{ $event['id'] }}" class="btn btn-primary btn-sm me-1">
                                                <i class="bi bi-pencil-square"></i> <!-- Edit icon -->
                                            </a>
                                            <a href="/events/delete/{{ $event['id'] }}" id="delete-event-btn" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> <!-- Delete icon -->
                                            </a>
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

                    </div>
                </div>
            </div>

        </div>
    </section>



</main><!-- End #main -->

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="confirmDeleteModalLabel">Confirm Your Action</h5>
            </div>
            <div class="modal-body">
                <h6 class="text-dark">Are you sure you want to execute this action?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" id="cancel-btn" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger btn-sm">Yes, Delete Event</button>
            </div>
        </div>
    </div>
</div>

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

        $('#updateEventForm').on('submit', function(event) {
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
                    url: '/events/update/',
                    type: 'POST',
                    data: fromData,
                    success: function(response) {

                        Toastify({
                            text: response.message || "Record Updated Successfully",
                            duration: 4000,
                            gravity: 'bottom',
                            position: 'left',
                            backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                        }).showToast();

                        setTimeout(function() {
                            window.location.reload();
                        }, 3000)

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


        $(document).on('click', '#events-accordation #delete-event-btn', function(event) {
            event.preventDefault();
            var deleteUrl = $(this).attr('href');
            var deleteBtn = $(this);

            $('#confirmDeleteModal').modal('show');
            $('#cancel-btn').click(function() {
                $('#confirmDeleteModal').modal('hide');
            });

            $('#confirmDeleteModal').on('click', '#confirmDeleteBtn', function() {
                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    success: function(response) {
                        $('#confirmDeleteModal').modal('hide');
                        Toastify({
                            text: response.message || "Event Deleted Successfully",
                            duration: 4000,
                            gravity: 'bottom',
                            position: 'left',
                            backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                        }).showToast();

                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    },
                    error: function(jqXHR) {
                        if (jqXHR.status === 500) {
                            Toastify({
                                text: jqXHR.responseJSON.message || "An error occurred while deleting event",
                                duration: 4000,
                                gravity: 'bottom',
                                position: 'left',
                                backgroundColor: 'linear-gradient(to right, #ff416c, #ff4b2b)',
                            }).showToast();
                        }
                    }
                });
            });
        });
    });

    $(document).ajaxStart(function() {
        $("#loader").removeClass('d-none');
    });

    $(document).ajaxStop(function() {
        $("#loader").addClass('d-none');
    });


    $('#eventFilter').change(function() {
        var filter = $(this).val();
        var appName = 'yourAppName'; // Replace with your actual app name

        $.ajax({
            url: `/events/get-my-organisation-events/?visibility=${filter}`,
            method: 'GET',
            processData: false,
            contentType: false,
            success: function(data) {
                $('#accordionExample').empty();
                var eventData = data.response;
                console.log(eventData);


                if (eventData.length > 0) {
                    // Iterate over the filtered events and append them to the accordion
                    eventData.forEach(function(event) {
                        var eventHtml = `
                            <div class="accordion-item" id="events-accordation">
                                <span class="badge bg-primary ms-4 mt-2 ps-2">${event.active ? 'Visible on calendar' : 'Not visible on calendar'}</span>
                                <h2 class="accordion-header" id="heading${event.id}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${event.id}" aria-expanded="false" aria-controls="collapse${event.id}">
                                        ${event.title}
                                    </button>
                                </h2>
                                <div id="collapse${event.id}" class="accordion-collapse collapse" aria-labelledby="heading${event.id}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>Start Date:</strong> ${event.start}<br>
                                        <strong>End Date:</strong> ${event.end}<br>
                                        <strong>Visible To:</strong> ${event.viewer} members<br>
                                        <strong>Created At:</strong> ${event.created_at}<br>
                                    </div>
                                    <div class="button-group ms-3 ps-1 mb-3">
                                        <a href="/dashboard/manage-events/?action=edit&id=${event.id}" class="btn btn-primary btn-sm">Edit Event Details</a>
                                        <a href="/events/delete/${event.id}" id="delete-event-btn" class="btn btn-danger btn-sm">Delete Event</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#accordionExample').append(eventHtml);
                    });
                } else {
                    // If no events are available, show a warning message
                    $('#accordionExample').append(`
                        <div class="alert alert-warning" role="alert">
                            No events available to show.
                        </div>
                    `);
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
</script>