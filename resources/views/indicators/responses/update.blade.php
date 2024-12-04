@include('layouts.header')
@include('layouts.topBar');
@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Edit Response details</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Response</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 mt-3">
                <a href="{{ route('indicator.responses', $response->indicator->id) }}" class="btn btn-primary btn-sm">Go back to responses</a>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section dashboard">

        <div class="row">
            <div class="col-sm-10">
                <div class="card p-2">

                    <div class="card-body">
                        <form action="" class="needs-validation" novalidate id="add-response-form" method="PUT">

                            <input type="hidden" id="response-id" value="{{ $response->id}}" name="id">

                            @method('PUT')

                            @if(isset($lastCurrentState['last_current_state']) && $lastCurrentState['last_current_state'] == $response->indicator['target'])
                            <div class="alert alert-warning">The target for this indicator was achieved..</div>
                            @else
                            <div class="alert alert-secondary">
                                <h4>You are editing a response for</h4>
                                <i class="fw-bold">>>>> {{$response->indicator['indicator_title']}} indicator <<<< </i>
                            </div>


                            <div class="form-group my-2">
                                <label for="">Indicator Baseline</label>
                                <input id="indicator-id" type="hidden" name="indicator-id" value="{{$response->indicator['id']}}">
                                <input type="hidden" name="last_current_state" value="{{ isset($lastCurrentState['last_current_state']) ? $lastCurrentState['last_current_state'] : '' }}">
                                <input id="baseline" name="baseline" required readonly type="number" value="{{$response->indicator['baseline']}}" class="form-control">
                            </div>
                            <div class="form-group my-2 alert alert-info">
                                <label for="">Previous state entered for last response</label>
                                <input type="text" class="form-control" readonly value="{{ isset($lastCurrentState['last_current_state']) ? $lastCurrentState['last_current_state'] : 'No response added yet' }}">
                            </div>

                            <div class="form-group my-2">
                                <label for="">Enter current state</label>
                                <small class="text-danger"> (Must be a whole number)</small>
                                <input id="current" name="current" type="number" class="form-control" value="{{ $response->current }}">
                                <div class="invalid-feedback">This value is required</div>
                            </div>
                            <div class="form-group my-2">
                                <label for="">Current Progress From Baseline</label>
                                <input required id="progress" name="progress" readonly type="number" class="form-control">
                                <div class="invalid-feedback">This value is required</div>
                            </div>

                            <div class="form-group my-2">
                                <label for="">Target For Indicator</label>
                                <input id="target" name="target" required readonly type="number" value="{{$response->indicator['target']}}" class="form-control">
                            </div>
                            <input type="hidden" id="direction" value="{{ $response->indicator['direction']}}">

                            <!-- Progress Bar Section -->
                            <div class="form-group mb-4">
                                <label for="progress-bar">Progress</label>
                                <div class="progress" style="height: 30px;">
                                    <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>

                            <!-- Add this in your custom CSS for additional styling -->
                            <style>
                                .form-control[readonly] {
                                    background-color: #e9ecef;
                                    opacity: 1;
                                }

                                .progress-bar {
                                    transition: width 0.6s ease;
                                    font-weight: bold;
                                }

                                .invalid-feedback {
                                    display: none;
                                }

                                input.is-invalid+.invalid-feedback {
                                    display: block;
                                }

                                .progress-bar.bg-success {
                                    background-color: #28a745 !important;
                                }
                            </style>




                            <div class="accordion" id="responseAccordion">

                                <!-- Notes Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingNotes">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNotes" aria-expanded="true" aria-controls="collapseNotes">
                                            Notes
                                        </button>
                                    </h2>
                                    <div id="collapseNotes" class="accordion-collapse collapse show" aria-labelledby="headingNotes" data-bs-parent="#responseAccordion">
                                        <div class="accordion-body">
                                            <small class="text-success">Please use the editor to add notes to this response. You can bold, create lists and even add external links to other resources in case you need them.</small>
                                            <hr>
                                            <div class="quill-editor" id="notes-editor-container" style="height: 300px;">{!! $response->notes !!}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lessons Learnt Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingLessons">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLessons" aria-expanded="false" aria-controls="collapseLessons">
                                            Lessons Learnt
                                        </button>
                                    </h2>
                                    <div id="collapseLessons" class="accordion-collapse collapse" aria-labelledby="headingLessons" data-bs-parent="#responseAccordion">
                                        <div class="accordion-body">
                                            <small class="text-success">Please use the editor to add lessons learnt to this response. You can bold, create lists and even add external links to other resources in case you need them.</small>
                                            <hr>
                                            <div class="quill-editor" id="editor-container" style="height: 300px;">{!! $response->lessons !!}</div>
                                            <div class="invalid-feedback d-block text-dark fw-bold" id="editor-feedback" style="display: none;">Please note that lessons are required to add this response</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recommendations Section -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingRecommendations">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRecommendations" aria-expanded="false" aria-controls="collapseRecommendations">
                                            Recommendations
                                        </button>
                                    </h2>
                                    <div id="collapseRecommendations" class="accordion-collapse collapse" aria-labelledby="headingRecommendations" data-bs-parent="#responseAccordion">
                                        <div class="accordion-body">
                                            <small class="text-success">Please use the editor to add recommendations to this response. You can bold, create lists and even add external links to other resources in case you need them.</small>
                                            <hr>
                                            <div class="quill-editor" id="recommendations-editor-container" style="height: 300px;">{!! $response->recommendations !!}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <br>
                            <div class="text-start">
                                <button id="create-response-btn" type="submit" class="btn btn-sm btn-primary">Update Response Details</button>
                                <button id="discardData" type="button" class="btn btn-danger btn-sm">Discard Auto Saved Data</button>
                            </div>
                            @endif
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

        var baseline = parseFloat($('input[name="baseline"]').val()) || 0;
        var target = parseFloat($('input[name="target"]').val()) || 100;
        var lastCurrentState = parseFloat($('input[name="last_current_state"]').val())|| baseline;
        var direction = $('#direction').val();
        var currentProgress = 0; // Store the progress to prevent recalculation on submit

        // Initial progress calculation
        var initialProgress = calculateProgress(lastCurrentState, baseline, target, direction);
        updateProgressUI(initialProgress);

        // Handle current state input
        $('#current').on('input', function() {
            var current = parseFloat($(this).val());

            // Ensure baseline and target are valid numbers
            if (isNaN(baseline) || isNaN(target)) {
                return; // Abort if baseline or target are invalid
            }

            // Determine the valid range based on the direction
            var isIncreasing = direction === 'increasing';
            var validMin, validMax;

            if (isIncreasing) {
                validMin = Math.min(baseline, target);
                validMax = Math.max(baseline, target);
            } else {
                validMin = Math.min(target, baseline);
                validMax = Math.max(target, baseline);
            }

            // Validate if the current value is in the valid range
            if (isNaN(current) || current < validMin || current > validMax) {
                $('#current').addClass('is-invalid');
                updateProgressUI(0); // Reset progress bar to 0%
                showToast("Current state must be between " + validMin + " and " + validMax, '#ff8282');
            } else {
                $('#current').removeClass('is-invalid');

                // Calculate progress based on the direction
                currentProgress = calculateProgress(current, baseline, target, direction);
                updateProgressUI(currentProgress);
            }
        });

        // Function to calculate progress based on direction
        function calculateProgress(current, baseline, target, direction) {
            var progress;

            if (direction === 'increasing') {
                progress = ((current - baseline) / (target - baseline)) * 100;
            } else {
                progress = ((baseline - current) / (baseline - target)) * 100;
            }

            // Clamp progress between 0 and 100
            return Math.min(Math.max(progress, 0), 100);
        }

        // Function to update the progress bar UI
        function updateProgressUI(progress) {
            $('#progress').val(progress.toFixed(1)); // Update the progress input field
            $('#progress-bar').css('width', progress.toFixed(1) + '%'); // Update progress bar width
            $('#progress-bar').attr('aria-valuenow', progress.toFixed(1)); // Update ARIA attribute
            $('#progress-bar').text(progress.toFixed(1) + '%'); // Update progress bar text
        }

        // Function to show toast notifications for errors
        var toastInstance = null; // Track the toast instance

        function showToast(message, color) {
            if (toastInstance) {
                toastInstance.hideToast(); // Close any existing toast before showing a new one
            }
            toastInstance = Toastify({
                text: message,
                duration: 3000,
                gravity: 'bottom',
                position: 'left',
                backgroundColor: color,
            }).showToast();
        }


        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'font': []
                    }, {
                        'size': []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'align': []
                    }],
                    ['link'],
                ]
            }
        });

        var notesQuill = new Quill('#notes-editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'font': []
                    }, {
                        'size': []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'align': []
                    }],
                    ['link'],
                ]
            }
        });

        var recommendationsQuill = new Quill('#recommendations-editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'font': []
                    }, {
                        'size': []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'align': []
                    }],
                    ['link'],
                ]
            }
        });

        var indicatorId = $('input[name="indicator-id"]').val();

        $('#add-response-form').submit(function(event) {
            event.preventDefault();

             // Check if the current value is valid before submitting
             if ($('#current').hasClass('is-invalid')) {
                showToast("Please correct the current state value before submitting.", '#ff8282');
                return; // Prevent submission if the current value is invalid
            }

            if (this.checkValidity() === true) {
                var lessons = quill.root.innerHTML.trim();
                var notes = notesQuill.root.innerHTML.trim();
                var recommendations = recommendationsQuill.root.innerHTML.trim();

                if (lessons === "" || lessons === "<p><br></p>") {
                    $('#editor-feedback').show();
                    Toastify({
                        text: "Lessons learnt cannot be empty.",
                        duration: 3000,
                        gravity: 'bottom', // Position the toast at the bottom
                        position: 'left', // Align toast to the left
                        backgroundColor: '#ff8282',
                    }).showToast();
                } else {
                    $('#editor-feedback').hide();

                    $.ajax({
                        url: '/indicators/response/update',
                        method: 'PUT',
                        data: {
                            indicator_id: $('input[name="indicator-id"]').val(),
                            id: $('#response-id').val(),
                            baseline: baseline,
                            current: parseFloat($('#current').val()),
                            target: target,
                            progress: $('#progress').val(),
                            lessons: lessons,
                            notes: notes,
                            recommendations: recommendations,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token if necessary
                        },
                        success: function(response) {
                            Toastify({
                                text: "Response updated successfully!",
                                duration: 3000,
                                gravity: 'bottom', // Position the toast at the bottom
                                position: 'left', // Align toast to the left
                                backgroundColor: '#28a745',
                            }).showToast();

                            localStorage.removeItem('monitorresponsedata' + indicatorId);

                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        },
                        error: function(response) {
                            Toastify({
                                text: "An error occurred. Please try again.",
                                duration: 3000,
                                gravity: 'bottom', // Position the toast at the bottom
                                position: 'left', // Align toast to the left
                                backgroundColor: '#ff8282',
                            }).showToast();
                        }
                    });
                }
            } else {
                this.classList.add('was-validated');
            }
        });

        loadFormData(indicatorId);

        // Save form data on input change
        $('form input, form select, form textarea').on('change', function() {
            saveFormData();
        });

        var editors = [quill, notesQuill, recommendationsQuill];
        editors.forEach(function(editor) {
            editor.on('text-change', function(delta, oldDelta, source) {
                saveFormData();
            });
        });


        function saveFormData() {

            var formData = {};
            $('form').find('input[name], select[name], textarea[name]').each(function() {
                formData[$(this).attr('name')] = $(this).val();
            });

            var lessons = quill.root.innerHTML.trim();
            var notes = notesQuill.root.innerHTML.trim();
            var recommendations = recommendationsQuill.root.innerHTML.trim()

            formData['lessons'] = lessons;
            formData['notes'] = notes;
            formData['recommendations'] = recommendations;

            localStorage.setItem('monitorresponsedata' + indicatorId, JSON.stringify(formData));
        }

        function loadFormData(indicatorId) {
            var savedData = localStorage.getItem('monitorresponsedata' + indicatorId);

            if (savedData) {
                $('#discardData').show();
            } else {
                $('#discardData').hide();
            }

            if (savedData) {
                savedData = JSON.parse(savedData);

                if (savedData['indicator-id'] && savedData['indicator-id'] === indicatorId) {

                    Toastify({
                        text: "You have unsaved response data for this indicator, it has been restored for you.",
                        duration: 90000,
                        gravity: 'top',
                        position: 'center',
                        close: true,
                    }).showToast();

                    for (var key in savedData) {
                        if (key !== 'lessons' && key !== 'notes' && key !== 'recommendations' && key !== 'indicator-id') {
                            var $field = $('form').find('[name="' + key + '"]');
                            if ($field.length > 0) {
                                $field.val(savedData[key]);
                            }
                        }
                    }


                    // Restore progress bar value
                    $('#progress-bar').css('width', savedData['progress'].toString() + '%');
                    $('#progress-bar').attr('aria-valuenow', savedData['progress']);
                    $('#progress-bar').text(savedData['progress'] + '%');

                    // Restore Quill editor content
                    var quillEditors = {
                        'lessons': quill,
                        'notes': notesQuill,
                        'recommendations': recommendationsQuill
                    };

                    for (var key in quillEditors) {
                        if (savedData.hasOwnProperty(key)) {
                            var quillEditor = quillEditors[key];
                            if (quillEditor) {
                                quillEditor.root.innerHTML = savedData[key];
                            }
                        }
                    }
                }
            }
        }


        $('#discardData').click(function() {
            event.preventDefault();

            localStorage.removeItem('monitorresponsedata' + indicatorId);
            Toastify({
                text: "Response data discarded successfully!",
                duration: 3000,
                gravity: 'bottom',
                position: 'left',
                backgroundColor: '#ff8282',
            }).showToast();

            setTimeout(function() {
                window.location.reload();
            }, 3000);
        });

    });
</script>