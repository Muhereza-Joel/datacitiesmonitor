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
        <h1>Edit Event</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Event</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row g-2 p-2">
            <div class="col-sm-4">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Event Details</h5>
                        <form id="createEventForm" class="needs-validation" method="post" novalidate>
                            @method('PUT')
                            <div class="mb-3">
                                <label for="event" class="form-label">Event</label>
                                <div id="quillEditor" class="" style="height: 200px;">{!! $event->event !!}</div>
                                <textarea id="event" name="event" style="display:none;" required></textarea>
                                <div class="invalid-feedback" id="quillInvalidFeedback">This field is required</div>
                            </div>

                            <div class="mb-3">
                                <label for="visibility">Visible To</label><br>
                                <select name="visibility" id="visibility" required class="form-control">
                                    <option value="">Choose who sees your event</option>
                                    <option value="all" {{ $event->visibility == 'all' ? 'selected' : '' }} >All Users</option>
                                    <option value="internal" {{ $event->visibility == 'internal' ? 'selected' : '' }} >Members of my organisation</option>
                                    <option value="external" {{ $event->visibility == 'external' ? 'selected' : '' }} >Only members of other organisations</option>
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
                                    <option value="1" {{ $event->active == 1 ? 'selected' : '' }} >Yes</option>
                                    <option value="0" {{ $event->active == 0 ? 'selected' : '' }} >No</option>
                                </select>
                                <div class="invalid-feedback">This field is required</div>
                            </div>

                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input autocomplete="off" type="text" class="form-control" id="startDate" value="{{ $event->start_date }}" name="start_date" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input autocomplete="off" type="text" class="form-control" id="endDate" value="{{ $event->end_date }}" name="end_date" required>
                                <div class="invalid-feedback">This field is required</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>

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
                    url: '{{ route("events.update", $event->id) }}',
                    type: 'POST',
                    data: fromData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        Toastify({
                            text: response.message || "Event Updated Successfully",
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