@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<style>
    .tippy-box {
        z-index: 9999;
        /* Make sure tooltip appears above other elements */
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Events</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Calender</li>
                    </ol>
                </nav>

            </div>
            <div class="dropdown text-end w-50 pt-3">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="userActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    On This Page
                </button>
                <ul class="dropdown-menu" aria-labelledby="userActionsDropdown">

                </ul>
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
        <div class="row g-0">
            <div class="col">

                <div class="card info-card calendar-card">
                    <div class="card-body">

                        <div class="card-title">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                <h5 class="mb-0">Events Calendar</h5>
                                <div class="d-flex align-items-center">

                                    <div class="d-flex align-items-center ml-4">
                                        <span class="mb-0 mx-2">Key:</span>
                                        <div class="d-flex align-items-center mr-3">
                                            <div style="width: 20px; height: 20px; background-color: #5e1119; margin-right: 5px;"></div>
                                            <span class="me-3">All</span>
                                        </div>
                                        <div class="d-flex align-items-center mr-3">
                                            <div style="width: 20px; height: 20px; background-color: #911180; margin-right: 5px;"></div>
                                            <span class="me-3">Internal</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 20px; height: 20px; background-color: #0a4663; margin-right: 5px;"></div>
                                            <span class="me-3">External</span>
                                        </div>
                                    </div>

                                    <span class="mr-2 mb-0">Filter By</span>
                                    <div class="d-flex align-items-center mx-3 form-group mb-0 ml-3">
                                        <select class="form-control" id="visibilityFilter">
                                            <option value="all">All - public</option>

                                            <option value="internal">Internal - private</option>

                                            <option value="external">External - public</option>
                                        </select>
                                    </div>


                                </div>
                            </div>

                        </div>

                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {
        var calendarEl = document.getElementById('calendar');
        var visibilityFilter = $('#visibilityFilter');
        var events = [];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth,dayGridYear'
            },
            views: {
                dayGridMonth: {
                    buttonText: 'Month'
                },
                timeGridWeek: {
                    buttonText: 'Week'
                },
                timeGridDay: {
                    buttonText: 'Day'
                },
                listMonth: {
                    buttonText: 'List'
                },
                dayGridYear: {
                    buttonText: 'Year'
                }
            },
            events: events,
            selectable: true,
            eventContent: function(arg) {
                let container = document.createElement('div');
                container.classList.add('fc-event-content');

                let logoWrapper = document.createElement('div');
                logoWrapper.style.backgroundColor = 'white';
                logoWrapper.style.padding = '2px';
                logoWrapper.style.display = 'inline-block';

                let logoImg = document.createElement('img');
                logoImg.src = arg.event.extendedProps.logo;
                logoImg.style.width = '20px';
                logoImg.style.height = '20px';
                logoImg.style.verticalAlign = 'middle';

                logoWrapper.appendChild(logoImg);

                let titleSpan = document.createElement('span');
                titleSpan.innerText = arg.event.title;
                titleSpan.style.marginLeft = '5px';

                container.appendChild(logoWrapper);
                container.appendChild(titleSpan);

                return {
                    domNodes: [container]
                };
            },
            eventDidMount: function(info) {
                let startDate = moment(info.event.start);
                let endDate = info.event.end ? moment(info.event.end) : moment(info.event.start); // Use start date if no end date
                let now = moment();

                let daysRemaining = endDate.diff(now, 'days');
                let daysPassed = now.diff(endDate, 'days'); // Difference from now to end date

                let formattedStartDate = startDate.format('MMMM Do YYYY, h:mm a');
                let formattedEndDate = info.event.end ?
                    endDate.format('MMMM Do YYYY, h:mm a') :
                    'No End Date';

                let content = `
            <strong>${info.event.title}</strong><br><hr>
            <small><strong>Start Time:</strong> ${formattedStartDate}</small><br>
            <small><strong>End Time:</strong> ${formattedEndDate}</small><br>
        `;

                if (daysRemaining > 0) {
                    content += `<strong>Days Remaining:</strong> ${daysRemaining} days<br>`;
                } else if (daysPassed > 0) {
                    content += `<strong>Event has passed.</strong><br>`;
                } else {
                    content += `<strong>Event is ongoing.</strong><br>`;
                }

                tippy(info.el, {
                    content: content,
                    allowHTML: true,
                    placement: 'bottom',
                    theme: 'Materia',
                    interactive: true,
                    trigger: 'mouseenter focus',
                    hideOnClick: false,
                });

                let viewer = info.event.extendedProps.viewer;
                if (viewer === 'all') {
                    info.el.style.backgroundColor = '#5e1119';
                } else if (viewer === 'internal') {
                    info.el.style.backgroundColor = '#911180';
                } else if (viewer === 'external') {
                    info.el.style.backgroundColor = '#0a4663';
                }

                // Add a style for past events
                if (now.isAfter(endDate)) {
                    info.el.style.opacity = '0.5'; // Dim past events
                    info.el.style.textDecoration = 'line-through'; // Strike-through text
                }
            }
        });


        fetchEvents('all');

        function fetchEvents(visibility) {
            var url = '/events/display/';
            if (visibility && visibility !== '') {
                url += visibility;
            }

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    events = response.map(event => ({
                        title: stripHtml(event.title),
                        start: event.start_date,
                        end: event.end_date,
                        logo: event.logo,
                        viewer: event.visibility
                    }));

                    if (events.length === 0) {
                        Toastify({
                            text: "There are no events to display on the calendar!",
                            duration: 8000,
                            gravity: 'bottom',
                            position: 'right',
                            backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                        }).showToast();
                    }

                    calendar.getEventSources().forEach(function(eventSource) {
                        eventSource.remove();
                    });

                    calendar.addEventSource(events);
                }
            });
        }

        function stripHtml(html) {
            var tmp = document.createElement("div");
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || "";
        }

        visibilityFilter.on('change', function() {
            var selectedValue = $(this).val();
            fetchEvents(selectedValue);
        });

        setTimeout(() => {
            calendar.render();
        }, 500);
    });
</script>