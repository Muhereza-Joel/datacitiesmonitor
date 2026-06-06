@include('layouts.header')
@include('layouts.topBar')
@include('layouts.leftPane')

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-start">
                <h1 class="text-body-emphasis">Add Focus Area Details</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Manage Reports</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.show', $report->id) }}">Report Details</a></li>
                        <li class="breadcrumb-item active">Add Area Focus</li>
                    </ol>
                </nav>
            </div>
            <div>
                @can('view', $report)
                <a href="{{ route('reports.show', $report->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Cancel and Return
                </a>
                @endcan
            </div>
        </div>
    </div>
    <section class="section dashboard mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card bg-body text-body border shadow-sm">
                    <div class="card-header bg-transparent border-bottom pt-3 pb-2">
                        <h5 class="fw-bold text-body-emphasis mb-0">
                            <i class="bi bi-plus-circle text-primary me-2"></i> Focus Area Operational Form
                        </h5>
                        <p class="text-secondary small mb-0 mt-1">
                            Appending a rich text metric segment block to the master monthly report statement.
                        </p>
                    </div>

                    <div class="card-body pt-4">
                        <form id="add-report-area-form" novalidate>
                            @csrf
                            <input type="hidden" id="report-id" value="{{ $report->id }}">

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="area_of_focus_id" class="form-label fw-semibold small text-secondary">Area of Focus <span class="text-danger">*</span></label>
                                    <select class="form-select" id="area_of_focus_id" name="area_of_focus_id" required>
                                        <option value="" selected disabled>-- Select Area Focus Block --</option>
                                        @foreach($areasOfFocus as $focus)
                                        <option value="{{ $focus->id }}">{{ $focus->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a valid area of focus.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold small text-secondary">Area Status Parameter</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending">Pending</option>
                                        <option value="ongoing" selected>Ongoing / Active</option>
                                        <option value="completed">Completed</option>
                                        <option value="delayed">Delayed / Blocked</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="objective" class="form-label fw-semibold small text-secondary">Target Objective <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="objective" name="objective" rows="2" placeholder="What was the intended target outcome or key goal for this area during this tracking window?" required></textarea>
                                <div class="invalid-feedback">Objective statement is required.</div>
                            </div>

                            <hr class="my-4 text-muted">

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Activities Conducted</label>
                                    <div class="quill-editor" id="activities-editor" style="height: 400px;"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-success">Key Achievements</label>
                                    <div class="quill-editor bg-success-subtle border-success-subtle" id="achievements-editor" style="height: 400px;"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-danger">Encountered Challenges / Bottlenecks</label>
                                    <div class="quill-editor bg-danger-subtle border-danger-subtle" id="challenges-editor" style="height: 400px;"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-warning">Identified Risks & Vulnerabilities</label>
                                    <div class="quill-editor bg-warning-subtle border-warning-subtle" id="risks-editor" style="height: 400px;"></div>
                                </div>
                            </div>

                            <hr class="my-4 text-muted">

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-info">Strategic Opportunities</label>
                                    <div class="quill-editor bg-info-subtle border-info-subtle" id="opportunities-editor" style="height: 400px;"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-primary">Immediate Action Plans</label>
                                    <div class="quill-editor bg-primary-subtle border-primary-subtle" id="action-plans-editor" style="height: 400px;"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Lessons Learned</label>
                                    <div class="quill-editor" id="lessons-editor" style="height: 200px;"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Future Recommendations</label>
                                    <div class="quill-editor" id="recommendations-editor" style="height: 200px;"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-secondary">Stakeholder Feedback</label>
                                <div class="quill-editor" id="stakeholder-editor" style="height: 160px;"></div>
                            </div>

                            @can('create', \App\Models\ReportArea::class)
                            <div class="border-top pt-3 d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('reports.show', $report->id) }}" class="btn btn-outline-secondary px-4">Discard</a>
                                <button id="discardData" type="button" class="btn btn-danger px-3" style="display: none;">Discard Autosave</button>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-1"></i> Save
                                </button>
                            </div>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

@include('layouts.footer')

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    $(document).ready(function() {
        const reportId = $('#report-id').val();
        const autoSaveKey = 'report_area_autosave_' + reportId;
        const autoSaveEnabled = "{{ session('user.preferences.auto_save', 'true') }}" === "true";

        // Shared Standard Rich Text Toolkit Profile Configuration 
        const toolbarOptions = [
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
            ['link', 'clean']
        ];

        // Instantiate Quill Modules Object Instances
        const instances = {
            activities: new Quill('#activities-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            achievements: new Quill('#achievements-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            challenges: new Quill('#challenges-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            risks: new Quill('#risks-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            opportunities: new Quill('#opportunities-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            action_plans: new Quill('#action-plans-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            lessons_learned: new Quill('#lessons-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            recommendations: new Quill('#recommendations-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            }),
            stakeholder_feedback: new Quill('#stakeholder-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            })
        };

        // Helper function to evaluate valid text strings from Quill nodes
        function getCleanHtml(instance) {
            const html = instance.root.innerHTML.trim();
            return (html === "<p><br></p>" || html === "") ? "" : html;
        }

        // Save State Routine Handler
        function saveFormData() {
            let data = {
                area_of_focus_id: $('#area_of_focus_id').val(),
                status: $('#status').val(),
                objective: $('#objective').val()
            };

            // Loop through editor contexts to export html snapshots
            Object.keys(instances).forEach(key => {
                data[key] = getCleanHtml(instances[key]);
            });

            localStorage.setItem(autoSaveKey, JSON.stringify(data));
            $('#discardData').show();
        }

        // Restore State Routine Handler
        function loadFormData() {
            const saved = localStorage.getItem(autoSaveKey);
            if (!saved) return;

            try {
                const data = JSON.parse(saved);

                if (data.area_of_focus_id) $('#area_of_focus_id').val(data.area_of_focus_id);
                if (data.status) $('#status').val(data.status);
                if (data.objective) $('#objective').val(data.objective);

                Object.keys(instances).forEach(key => {
                    if (data[key]) {
                        instances[key].root.innerHTML = data[key];
                    }
                });

                $('#discardData').show();
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "Restored uncommitted operational workspace data from local storage autosave cache.",
                        duration: 5000,
                        gravity: 'top',
                        position: 'center',
                        style: {
                            background: "#0d6efd"
                        }
                    }).showToast();
                }
            } catch (e) {
                console.error("Failed to rehydrate fallback storage values", e);
            }
        }

        // Register event tracking listeners if permitted
        if (autoSaveEnabled) {
            $('#area_of_focus_id, #status, #objective').on('input change', saveFormData);
            Object.keys(instances).forEach(key => {
                instances[key].on('text-change', saveFormData);
            });
        }

        // Initialize hydration sequence
        loadFormData();

        // Discard local caching payload block handler
        $('#discardData').click(function(e) {
            e.preventDefault();
            localStorage.removeItem(autoSaveKey);
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: "Workspace changes purged.",
                    duration: 2000,
                    style: {
                        background: "#dc3545"
                    }
                }).showToast();
            }
            setTimeout(() => window.location.reload(), 1000);
        });

        // Intercept standard form submission handler context via AJAX
        $('#add-report-area-form').submit(function(event) {
            event.preventDefault();

            // Clear state styling flags
            $('.is-invalid').removeClass('is-invalid');

            // Client Validation Checkpoints
            let targetFocus = $('#area_of_focus_id').val();
            let objectiveVal = $('#objective').val().trim();
            let isValid = true;

            if (!targetFocus) {
                $('#area_of_focus_id').addClass('is-invalid');
                isValid = false;
            }
            if (!objectiveVal) {
                $('#objective').addClass('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "Please complete mandatory validation requirements before saving.",
                        style: {
                            background: "#dc3545"
                        }
                    }).showToast();
                }
                return;
            }

            // Build request payload data dictionary explicitly
            let payload = {
                _token: $('input[name="_token"]').val(),
                area_of_focus_id: targetFocus,
                status: $('#status').val(),
                objective: objectiveVal,
                activities_conducted: getCleanHtml(instances.activities),
                achievements: getCleanHtml(instances.achievements),
                challenges: getCleanHtml(instances.challenges),
                risks: getCleanHtml(instances.risks),
                opportunities: getCleanHtml(instances.opportunities),
                action_plans: getCleanHtml(instances.action_plans),
                lessons_learned: getCleanHtml(instances.lessons_learned),
                recommendations: getCleanHtml(instances.recommendations),
                stakeholder_feedback: getCleanHtml(instances.stakeholder_feedback)
            };

            $.ajax({
                url: '/reports/' + reportId + '/areas',
                method: 'POST',
                data: payload,
                dataType: 'json',
                success: function(response) {
                    localStorage.removeItem(autoSaveKey);
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: "Focus block saved successfully!",
                            duration: 1500,
                            style: {
                                background: "#198754"
                            }
                        }).showToast();
                    }
                    setTimeout(function() {
                        window.location.href = '/reports/' + reportId;
                    }, 1500);
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let backendErrors = xhr.responseJSON.errors;
                        Object.keys(backendErrors).forEach(field => {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            if (typeof Toastify !== 'undefined') {
                                Toastify({
                                    text: backendErrors[field][0],
                                    style: {
                                        background: "#dc3545"
                                    }
                                }).showToast();
                            }
                        });
                    } else {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: "An error occurred handling database storage logic.",
                                style: {
                                    background: "#dc3545"
                                }
                            }).showToast();
                        }
                    }
                }
            });
        });
    });
</script>