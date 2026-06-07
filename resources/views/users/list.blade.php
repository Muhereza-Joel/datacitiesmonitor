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
            <div class="dropdown text-end w-50 pt-3">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="userActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    On This Page
                </button>
                <ul class="dropdown-menu" aria-labelledby="userActionsDropdown">
                    @if (Auth::user()->hasRole('super-admin'))
                    <li>
                        <a class="dropdown-item" href="{{ route('organisation.user.create') }}">
                            <i class="bi bi-person-plus"></i> Add New Organisation User
                        </a>
                    </li>
                    @endif

                    @can('create', \App\Models\Project::class)
                    <li>
                        <a class="dropdown-item" href="{{ route('users.create') }}">
                            <i class="bi bi-person-plus-fill"></i> Add New User
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
            <div class="card pt-4">
                <div class="card-body">
                    <table id="users-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Organisation</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Privilege</th>
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
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->pluck('name')->first() ?? 'No role' }}</td>
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
                                            @can('update', $user) <!-- or Gate::allows, adjust as needed -->
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateRoleModal" data-user-id="{{ $user->id }}" data-user-role="{{ $user->roles->pluck('name')->first() }}">
                                                <i class="bi bi-pencil-square mr-2"></i> Update User Role
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateEmailModal" data-user-id="{{ $user->id }}" data-user-email="{{ $user->email }}">
                                                <i class="bi bi-pencil-square mr-2"></i> Update User Email
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateOrganisationModal" data-user-id="{{ $user->id }}" data-user-organisation="{{ $user->organisation }}">
                                                <i class="bi bi-pencil-square mr-2"></i> Update User Organisation
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#resetPasswordModal" data-user-id="{{ $user->id }}">
                                                <i class="bi bi-key mr-2"></i> Reset User Password
                                            </a>
                                            <a class="dropdown-item text-danger delete-user" href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-user-id="{{ $user->id }}">
                                                <i class="bi bi-trash"></i> Delete User
                                            </a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Image Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img id="enlargedImage" src="" alt="Enlarged Image" class="img-fluid" style="object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-4') }}
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
                                <!-- Options will be loaded dynamically via AJAX -->
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

    <!-- Update Organisation Modal -->
    <div class="modal fade" id="updateOrganisationModal" tabindex="-1" aria-labelledby="updateOrganisationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateOrganisationModalLabel">Update User Organisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateOrganisationForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="modalUserId">
                        <div class="mb-3">
                            <label for="userOrganisation" class="form-label">Organisation</label>
                            <select class="form-select" id="userOrganisation" name="organisation_id" required>
                                <option value="">Select Organisation</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update Organisation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Email Modal -->
    <div class="modal fade" id="updateEmailModal" tabindex="-1" aria-labelledby="updateEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateEmailModalLabel">Update User Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateEmailForm" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="modalEmailUserId">
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset User Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="resetPasswordForm" action="{{ route('user.resetPassword') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="modalResetPasswordUserId">
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="userPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="userConfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="userConfirmPassword" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Reset Password</button>
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

</main>

@include('layouts.footer')

<script>
    $(document).ready(function() {
        // Fetch all roles once and cache them
        let rolesList = [];
        $.ajax({
            url: '{{ route("roles.all") }}', // You need to create this route or use an existing one
            method: 'GET',
            async: false,
            success: function(data) {
                rolesList = data.roles; // expects [{id:1, name:'root'}, ...]
            },
            error: function() {
                console.error('Could not load roles');
            }
        });

        // Update Role Modal
        $('#updateRoleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            var userRole = button.data('user-role');
            var modal = $(this);
            modal.find('#modalUserId').val(userId);
            var roleSelect = modal.find('#userRole');
            roleSelect.empty();
            $.each(rolesList, function(index, role) {
                var selected = (role.name === userRole) ? 'selected' : '';
                roleSelect.append('<option value="' + role.name + '" ' + selected + '>' + role.name.charAt(0).toUpperCase() + role.name.slice(1) + '</option>');
            });
        });

        $('#updateRoleForm').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var userId = $('#modalUserId').val();
            $.ajax({
                url: '{{ url("users") }}/' + userId + '/role',
                type: 'PATCH',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#05333e'
                    }).showToast();
                    setTimeout(() => window.location.reload(), 3000);
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });

        // Update Organisation Modal (same as before, uses existing route)
        $('#updateOrganisationModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            $('#modalUserId').val(userId);
            $.ajax({
                url: '{{ route("organisations.all") }}',
                method: 'GET',
                success: function(data) {
                    var select = $('#userOrganisation');
                    select.html('<option value="">Select Organisation</option>');
                    $.each(data.organisations, function(i, org) {
                        select.append('<option value="' + org.id + '">' + org.name + '</option>');
                    });
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });

        $('#updateOrganisationForm').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var userId = $('#modalUserId').val();
            $.ajax({
                url: '{{ url("users") }}/' + userId + '/organisation',
                type: 'PATCH',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#05333e'
                    }).showToast();
                    setTimeout(() => window.location.reload(), 3000);
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });

        // Update Email Modal
        $('#updateEmailModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            var userEmail = button.data('user-email');
            $('#modalEmailUserId').val(userId);
            $('#userEmail').val(userEmail);
        });

        $('#updateEmailForm').on('submit', function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var userId = $('#modalEmailUserId').val();
            $.ajax({
                url: '{{ url("users") }}/' + userId + '/email',
                type: 'PATCH',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#05333e'
                    }).showToast();
                    setTimeout(() => window.location.reload(), 3000);
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        });

        // Reset Password Modal
        $('#resetPasswordModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var userId = button.data('user-id');
            $('#modalResetPasswordUserId').val(userId);
        });

        $('#resetPasswordForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#05333e'
                    }).showToast();
                    $('#resetPasswordModal').modal('hide');
                    $('#resetPasswordForm')[0].reset();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let msg = '';
                    $.each(errors, (k, v) => msg += v[0] + '\n');
                    Toastify({
                        text: msg,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#ff4d4d'
                    }).showToast();
                }
            });
        });

        // Delete User
        let userIdToDelete;
        $(document).on('click', '.delete-user', function() {
            userIdToDelete = $(this).data('user-id');
        });
        $('#confirmDeleteUser').on('click', function() {
            $.ajax({
                url: '{{ url("users") }}/' + userIdToDelete,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'left',
                        backgroundColor: '#05333e'
                    }).showToast();
                    setTimeout(() => window.location.reload(), 3000);
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
            $('#deleteUserModal').modal('hide');
        });

        // Profile image enlarge
        $('#users-table').on('click', '.profileImage', function() {
            $('#enlargedImage').attr('src', $(this).attr('src'));
            $('#imageModal').modal('show');
        });
    });
</script>