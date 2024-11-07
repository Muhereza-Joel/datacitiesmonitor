@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

<main id="main" class="main">

    <div class="pagetitle">
        <div class="d-flex">
            <div class="text-start w-50">
                <h1>Users</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>

            </div>
            <div class="text-end w-50 pt-3">
                @if (str_starts_with(Auth::user()->organisation->name, 'Administrator'))
                <a href="{{ route('organisation.user.create') }}" class="btn btn-primary btn-sm">Add New Organisation User</a>
                @endif
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Add New User</a>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row p-2">

            <div class="card pt-4">
                <div class="card-body">
                    <!-- Table with stripped rows -->
                    <table id="users-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Organisation</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Prevellage</th>
                                <th>Email Verified</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)

                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="{{ isset($user->organisation->logo) ? asset($user->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle" width="35px" height="35px" style="margin-right: 8px;">
                                        <span>{{ $user->organisation->name ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td style="display: flex; align-items: center;">
                                    <img src="{{ isset($user->profile->image_url) ? asset($user->profile->image_url) : asset('assets/img/placeholder.png') }}"
                                        alt="Profile" class="rounded-circle profileImage" width="35px" height="35px"
                                        style="margin-right: 8px; cursor: pointer;">
                                    <span>{{ $user->name ?? 'N/A' }}</span>
                                </td>

                                <td>{{$user->email}}</td>
                                <td>{{$user->role}}</td>
                                <td>
                                    <span class="badge {{ $user->email_verified_at ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                    </span>
                                </td>


                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline btn-sm dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Select Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="actionDropdown">

                                            <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                <i class="bi bi-eye"></i> View User Details
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateRoleModal" data-user-id="{{ $user->id }}" data-user-role="{{ $user->role }}">
                                                <i class="bi bi-pencil-square"></i> Update User Role
                                            </a>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="bi bi-slash-circle"></i> Block User
                                            </a>
                                            <a class="dropdown-item text-danger delete-user" href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-user-id="{{ $user->id }}">
                                                <i class="bi bi-trash"></i> Delete User
                                            </a>

                                        </div>
                                    </div>
                                </td>
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                    <!-- Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img id="enlargedImage" src="" alt="Enlarged Image" class="img-fluid" style="object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Table with stripped rows -->
                    <!-- Pagination links -->
                    <div class="d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-4') }} <!-- Use Bootstrap 4 Pagination -->
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- Update Role Modal -->
    <div class="modal fade" id="updateRoleModal" tabindex="-1" aria-labelledby="updateRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateRoleModalLabel">Update User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateRoleForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="modalUserId">
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" name="role" required>
                                @if(Auth::user()->role === 'root')
                                <option value="root">Super User</option>
                                <option value="admin">Administrator</option>
                                @endif
                                <option value="user">User</option>
                                <option value="viewer">Viewer</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this user? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteUser">Delete</button>
                </div>
            </div>
        </div>
    </div>



</main><!-- End #main -->

@include('layouts.footer')

<script>
    $(document).ready(function() {
        var updateRoleModal = $('#updateRoleModal');

        updateRoleModal.on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            var userRole = button.data('user-role');

            var modalUserId = $('#modalUserId');
            var modalUserRole = $('#userRole');

            modalUserId.val(userId);
            modalUserRole.val(userRole);
        });

        $('#updateRoleForm').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var userId = $('#modalUserId').val();

            $.ajax({
                url: '{{ url("users") }}/' + userId + '/role', // Update role route
                type: 'PATCH', // Use PATCH for partial updates
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token if necessary
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#05333e',
                    }).showToast();

                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });



        let userId; // Variable to hold the user ID to be deleted

        // When the delete user link is clicked, store the user ID
        $(document).on('click', '.delete-user', function(event) {
            userId = $(this).data('user-id');
        });

        // When the confirm delete button is clicked
        $('#confirmDeleteUser').on('click', function() {
            $.ajax({
                url: '{{ url("users") }}/' + userId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#05333e',
                    }).showToast();

                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

            // Hide the modal after the delete request
            $('#deleteUserModal').modal('hide');
        });

    });
</script>

<script>
    $(document).ready(function() {
        // Use event delegation to handle clicks on images within the table
        $('#users-table').on('click', '.profileImage', function() {
            // Get the source of the clicked image
            var imgSrc = $(this).attr('src');

            // Set the source of the modal image
            $('#enlargedImage').attr('src', imgSrc);

            // Show the modal
            $('#imageModal').modal('show');
        });
    });
</script>
