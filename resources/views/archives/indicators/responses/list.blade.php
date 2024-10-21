@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<style>
  .table-responsive {
    width: 100%;
    overflow-x: auto;
  }

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

  .drag-active {
    border: 2px dashed #28a745;
    /* Change border color */
    background-color: #f0f0f0;
    /* Light background */
    /* Add more styles as needed */
  }
</style>

<main id="main" class="main">

  <div class="pagetitle mt-3">
    <h1>All Responses</h1>
    <div class="d-flex align-items-center">
      <nav class="d-flex align-self-center w-50">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard' )}}">Home</a></li>
          <li class="breadcrumb-item active">Responses</li>
        </ol>
      </nav>

      <div style="border-radius: 50%;" class="d-flex align-self-center justify-content-end w-50">
        @if($indicatorId != null)
        <div class="btn-group" role="group" aria-label="Administrator Actions">



        </div>
        @endif
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
    <div class="row p-2">

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

      <div id="responses-table">
        @foreach($responses as $response)
        <div class="card mb-3 status-{{ strtolower($response['status']) }}">
          <div class="card-header">
            <div class="d-flex">
              <div class="text-start w-50">
                <span class="badge bg-success">{{ $response['response_tag_label'] }} from <br></span>
                {{ $response->user['name'] }}
  
              </div>
              <div class="text-end w-50">
                <div class="dropdown">
                  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select Action
                  </button>
                  <div class="dropdown-menu" aria-labelledby="actionDropdown">
                    <a href="{{ route('archives.show', $response->indicator->archive_id) }}" class="dropdown-item">
                      <i class="bi bi-arrow-left-circle"></i> Go Back To Archive
                    </a>
                    <a href="{{ route('archives.indicator.details', $indicatorId) }}" class="dropdown-item">
                      <i class="bi bi-info-circle"></i> Indicator Details
                    </a>
                    <a href="#response-files" id="view-files" class="dropdown-item" data-response-id="{{$response['id']}}">
                      <i class="bi bi-file-earmark"></i> Response Files
                    </a>
  
  
                  </div>
                </div>
  
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="accordion mt-2" id="accordionExample{{$response['id']}}">
              <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{$response['id']}}">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$response['id']}}" aria-expanded="false" aria-controls="collapse{{$response['id']}}">
                    <strong class="h5">Indicator title: {{ $response->indicator['indicator_title'] }}</strong>
                  </button>
                </h2>
                <div id="collapse{{$response['id']}}" class="accordion-collapse collapse" aria-labelledby="heading{{$response['id']}}" data-bs-parent="#accordionExample{{$response['id']}}">
                  <div class="accordion-body">
                    @php
                    $notesContent = trim(strip_tags($response['notes'], '<p><br>'));
                      $lessonsContent = trim(strip_tags($response['lessons'], '
                    <p><br>'));
                      $recommendationsContent = trim(strip_tags($response['recommendations'], '
                    <p><br>'));
                      @endphp
  
                      @if(!empty($notesContent) && $notesContent !== '
                    <p><br></p>')
                    <h5 class="text-success">Notes Taken</h5>
                    <p class="text-success">{!! $response['notes'] !!}</p>
                    <hr>
                    @endif
  
                    @if(!empty($lessonsContent) && $lessonsContent !== '<p><br></p>')
                    <h5 class="text-success">Lessons Learnt</h5>
                    <p class="text-success">{!! $response['lessons'] !!}</p>
                    <hr>
                    @endif
  
                    @if(!empty($recommendationsContent) && $recommendationsContent !== '<p><br></p>')
                    <h5 class="text-success">Recommendations</h5>
                    <p class="text-success">{!! $response['recommendations'] !!}</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
  
            <div class="mt-3">
              <h6>Progress Towards Target for Indicator</h6>
              <strong>Progress from baseline:</strong> {{ $response['progress'] }}%
  
              @include('layouts.rullerTwo')
            </div>
          </div>
  
        </div>
        <div class="modal fade" id="deleteModal{{ $response->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $response->id }}" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $response->id }}">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Are you sure you want to delete this response?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('indicators.response.destroy', $response->id) }}" method="POST" id="delete-form{{ $response->id }}">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form{{ $response->id }}').submit();">Delete</button>
                </form>
              </div>
  
            </div>
          </div>
        </div>
        @endforeach

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
        <div class="alert alert-warning p-2 mt-2">Note that this action will delete this response. Please continue with caution because the action is undoable</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" id="cancel-btn" data-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger btn-sm">Yes, Delete Response</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade lg" id="fileUploadModal" size="md" tabindex="-1" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileUploadModalLabel">Upload Files</h5>
        <button type="button" class="close btn btn-light btn-sm" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-muted">You can choose files or drag and drop them into the area below:</p>
        <!-- File Upload Input -->
        <input type="file" multiple class="form-control" id="fileInput">
        <!-- Drag and Drop Area -->
        <div id="dropArea" style="height: 40vh; border: 2px dashed #ccc; padding: 20px; margin-top: 15px; text-align: center;">
          Drag and drop files here
        </div>
        <!-- Selected Files Container -->
        <div id="selectedFilesContainer" class="mt-3"></div>
        <!-- Hidden Input for File Links -->
        <input type="hidden" id="fileLinksInput" name="fileLinks">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm" id="uploadButton">Upload</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="responseFilesModal" tabindex="-1" aria-labelledby="responseFilesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="responseFilesModalLabel">Response Files</h5>
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="files-section">
        <!-- Files for selected response will be displayed here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@include('layouts.footer')

<script>
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });


    $('#responses-table').on('click', '#delete-btn', function(event) {
      event.preventDefault();
      var removeUrl = $(this).attr('href');
      $('#confirmDeleteModal').modal('show');
      $('#cancel-btn').click(function() {
        $('#confirmDeleteModal').modal('hide');
      });
      $('#confirmDeleteModal').on('click', '#confirmDeleteBtn', function() {
        $.post(removeUrl, function(response) {
          Toastify({
            text: response.message || 'Record Deleted Successfully',
            duration: 2000,
            gravity: 'bottom',
            backgroundColor: 'green',
          }).showToast();
          setTimeout(function() {
            window.location.reload();
          }, 3000);
        });
      });
    });

    $('#responses-table').on('click', '#view-files', function() {
      const responseId = $(this).data('response-id');
      $('#responseFilesModal').modal('show');

      // Make an AJAX request to fetch files for the given response ID
      $.ajax({
        url: `/response/files/${responseId}`,
        method: 'GET',
        success: function(data) {
          const filesSection = $('#files-section');
          filesSection.empty(); // Clear previous files

          const panel = $('<div></div>')
            .addClass('alert alert-light')
            .css('background-color', '#f8f9fa') // Set background color
            .append(
              $('<div></div>')
              .addClass('panel-body')
              .append(
                $('<div></div>')
                .addClass('list-group')
              )
            );

          data.files.forEach(file => {
            const fileNameWithoutExtension = file.original_name.split('.').slice(0, -1).join('.');
            const fileExtension = file.original_name.split('.').pop();
            const cleanedUrl = `{{asset('uploads/files/${file.name}')}}`;
            const fileSize = (file.size / 1024).toFixed(2) + ' KB'; // Convert size to KB

            // Create the file link
            const fileLink = $('<a></a>')
              .attr('href', cleanedUrl)
              .text(file.original_name)
              .addClass('alert-link text-primary')
              .on('click', function(event) {
                event.preventDefault(); // Prevent navigation
                // Trigger download via JavaScript
                downloadFile(cleanedUrl, file.original_name);
              });

            // Create the file info (size and added time)
            const fileInfo = $('<div></div>')
              .addClass('file-info')
              .html(`
            <small class="text-muted">Size: ${fileSize}</small><br>
            <small class="text-muted">Added on: ${moment(file.created_at).format('MMMM Do YYYY')} at ${moment(file.created_at).format('h:mm A')}</small>
        `);

            // Create the delete button
            const deleteUrl = `{{ route('files.destroy', ':id') }}`.replace(':id', file.id);
            const removeButton = $('<button></button>')
              .text('Delete')
              .addClass('btn btn-danger btn-sm')
              .on('click', function() {
                // Remove file logic here
                $.ajax({
                  url: deleteUrl,
                  type: 'DELETE',
                  success: function(response) {
                    // Remove the file link from the list
                    listItem.remove();
                    Toastify({
                      text: response.message || 'File Removed Successfully.',
                      duration: 5000,
                      gravity: 'bottom',
                      position: 'left',
                      backgroundColor: '#28a745',
                    }).showToast();
                  },
                  error: function(xhr, status, error) {
                    console.error('Failed to remove file:', error);
                  }
                });
              });

            // Create a container for file details (link and info)
            const fileDetailsContainer = $('<div></div>')
              .addClass('d-flex flex-column')
              .append(fileLink)
              .append(fileInfo);

            // Create the list item and append file details on the left and delete button on the right
            const listItem = $('<div></div>')
              .addClass('list-group-item d-flex justify-content-between align-items-center')
              .append(fileDetailsContainer)
              .append(removeButton);

            // Append the list item to the list
            panel.find('.list-group').append(listItem);
          });

          filesSection.append(panel);
        },
        error: function(xhr, status, error) {
          console.error('Error fetching files:', error);
        }
      });

      function downloadFile(url, fileName) {
        const link = document.createElement('a');
        link.href = url;
        link.download = fileName;
        console.log(link);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }
    });


  });
</script>