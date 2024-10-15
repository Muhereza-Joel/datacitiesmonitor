@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<style>
    #progress-bar-container {
        display: flex;
        width: 100%;
        text-align: center;
        margin-top: 10px;
    }

    #progress-bar {
        width: 100%;
        height: 10px;
    }

    #progress-percentage {
        display: inline-block;
        margin-top: 5px;
        color: #000;
    }
</style>

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


<main id="main" class="main">
    <div class="d-flex align-items-center px-3">

        <div style="width: 30px;"></div>
        <div id="alert-success" class="alert alert-success alert-dismissible py-1 px-2  fade d-none w-50" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <span></span>
        </div>


    </div>

    <section class="section profile">

        <div class="row mt-4 pt-4">
            <div class="col-xl-4">
                <div class="text-center">
                    <img src="{{ isset($userDetails->profile->image_url) ? asset($userDetails->profile->image_url) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle" width="300px" height="300px" style="border: 3px solid #999; object-fit: cover;">

                    <br><br>
                    <span class="text-secondary"><strong>Your Role : </strong> {{ Auth::user()->role}}</span>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                            Update Your Photo
                        </button>

                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="">
                    <div class="pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered bg-light">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>


                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">About</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['about'] ?? 'N/A'}}</div>
                                </div>

                                <h5 class="card-title fw-bold text-secondary">Biography</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Full Name</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails->profile['name'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Date of Birth</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['dob'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Gender</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['gender'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Company</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['company'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Job</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['job'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">NIN Number</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['nin'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Email</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['email'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Country</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['country'] ?? 'N/A'}}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">District</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['district'] ?? 'N/A'}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Village</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['village'] ?? 'N/A'}}</div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label fw-bold text-secondary">Phone</div>
                                    <div class="col-lg-9 col-md-8 text-secondary">{{$userDetails['phone'] ?? 'N/A'}}</div>
                                </div>


                            </div>

                            <div class="tab-pane fade profile-edit" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form id="edit-profile-form" method="post" action="{{ route('profile.update.profile') }}" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="col-xl-12">
                                        <div class="alert alert-info d-none" id="alert-profile-upadte-success"></div>
                                        <div class="">
                                            <div class=" pt-3">
                                                <div class="row mb-3">
                                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label text-secondary">Full Name</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['name'] ?? 'N/A'}}" oninput="capitalizeEveryWord(this)" name="name" type="text" class="form-control" id="fullName" placeholder="Enter your full name here">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please enter your full name.</div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="about" class="col-md-4 col-lg-3 col-form-label text-secondary"></label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <textarea id="about-textarea" name="about" class="form-control" id="about" style="height: 150px" placeholder="Brief info about your self">{{$userDetails['about'] ?? 'N/A'}}</textarea>
                                                        <small class="text-danger">This field is optional</small>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="company" class="col-md-4 col-lg-3 col-form-label text-secondary">Company</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['company'] ?? 'N/A'}}" name="company" type="text" class="form-control" id="company" placeholder="What company do you work for (Optional)">
                                                        <small class="text-danger">This field is optional</small>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="Job" class="col-md-4 col-lg-3 col-form-label text-secondary">Job Title</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['job'] ?? 'N/A'}}" name="job" type="text" class="form-control" id="Job" placeholder="Enter your Job title like manager, doctor">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please provide your Job Title</div>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <label for="nin" class="col-md-4 col-lg-3 col-form-label text-secondary">NIN Number</label>
                                                    <div class="col-md-8 col-lg-9">

                                                        <input value="{{$userDetails['nin'] ?? 'N/A'}}" min="14" name="nin" type="text" class="form-control" id="nin" placeholder="Enter your nin number">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please enter a valid NIN number with digits, letters, no spaces and 14 characters long.</div>
                                                        <small id="nin-status" class="text-success fw-bold"></small>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="email" class="col-md-4 col-lg-3 col-form-label text-secondary">Email</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input name="email" type="text" class="form-control" id="email" placeholder="Enter your email address" value="{{$userDetails['email'] ?? 'N/A'}}">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please enter your email address.</div>
                                                        <small id="email-status" class="text-success fw-bold"></small>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="gender" class="col-md-4 col-lg-3 col-form-label text-secondary">Gender</label>
                                                    <div class="col-md-8 col-lg-9">
                                                    <small class="text-danger">This field is optional</small>
                                                        <select name="gender" id="gender" class="form-control">
                                                            <option value="">Select Gender</option>
                                                            @if(isset($userDetails['gender']))
                                                            <option value="male" {{ $userDetails['gender'] === 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ $userDetails['gender'] === 'female' ? 'selected' : '' }}>Female</option>
                                                            @else
                                                            <option value="male">Male</option>
                                                            <option value="female">Female</option>
                                                            @endif
                                                        </select>

                                                        <div class="invalid-feedback">Please select gender.</div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="dob" class="col-md-4 col-lg-3 col-form-label text-secondary">Date of Birth</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['dob'] ?? 'N/A'}}" name="dob" type="date" class="form-control" id="Country" placeholder="Enter your home Country">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please choose date of birth.</div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="Country" class="col-md-4 col-lg-3 col-form-label text-secondary">Country</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['country'] ?? 'N/A'}}" oninput="capitalizeFirstLetter(this)" name="country" type="text" class="form-control" id="Country" placeholder="Enter your home Country">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please enter your home coutry.</div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="Home District" class="col-md-4 col-lg-3 col-form-label text-secondary">Home District</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['district'] ?? 'N/A'}}" oninput="capitalizeFirstLetter(this)" name="district" type="text" class="form-control" id="Home District" placeholder="Enter your home district">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please enter your home district.</div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="village" class="col-md-4 col-lg-3 col-form-label text-secondary">Village</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['village'] ?? 'N/A'}}" oninput="capitalizeFirstLetter(this)" name="village" type="text" class="form-control" id="village" placeholder="Enter the village you come from">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please enter the village you come from.</div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label text-secondary">Phone</label>
                                                    <div class="col-md-8 col-lg-9">
                                                        <input value="{{$userDetails['phone'] ?? 'N/A'}}" name="phone" type="text" class="form-control" id="Phone" placeholder="Enter your phone number">
                                                        <small class="text-danger">This field is optional</small>
                                                        <div class="invalid-feedback">Please enter a valid phone number.</div>

                                                    </div>
                                                </div>




                                            </div>
                                        </div>

                                    </div>

                                    <div class="text-left">
                                        <button id="edit-profile-submit-button" class="btn btn-dark btn-sm">Update Profile</button>

                                    </div>

                                </form>
                            </div>

                        </div>



                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <!-- Change Password Form -->
                            <div class="alert alert-info p-2">
                                After successfully changing your password, your account will be loged out and
                                then you have to log in again. You will be redirected to the login page automatically!
                            </div>


                            <form class="needs-validation" id="password-change-form" novalidate>

                                <div class="alert alert-success p-1 d-none" id="alert-password-change-success">
                                    <span></span>
                                </div>

                                <div class="row mb-3">
                                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label text-secondary">Current Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password" type="password" class="form-control" id="currentPassword" required>
                                        <div class="invalid-feedback">Please enter your current password.</div>
                                        <div class="text-danger" id="password-error"></div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label text-secondary">New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="newpassword" type="password" class="form-control" id="newPassword" required disabled>
                                        <div class="invalid-feedback">Please enter new password.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label text-secondary">Re-enter New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="renewpassword" type="password" class="form-control" id="renewPassword" required disabled>
                                        <div class="invalid-feedback">Please re enter the new password.</div>
                                        <div class="renewPassword-feedback" id="renewPassword-feedback">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-left">
                                    <button type="submit" class="btn btn-primary btn-sm" id="change-password-btn" disabled>Change Password</button>
                                </div>
                            </form><!-- End Change Password Form -->

                        </div>

                    </div><!-- End Bordered Tabs -->

                </div>
            </div>

        </div>
        </div>
        <div class="modal fade" id="basicModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-body">
                        <div id="alert-number-error" class="alert alert-danger alert-dismissible py-1 px-2 fade d-none" role="alert">
                            <i class="bi bi-exclamation-octagon me-0"></i>
                            <span></span>

                        </div>

                        <form id="change-profile-pic-form" class="" action="{{ route('profile.update.photo') }}" method="post" enctype="multipart/form-data">
                            <div id="edit-photo-alert-success" class="alert alert-success alert-dismissible py-1 px-2  fade w-100" role="alert">
                                <i class="bi bi-check-circle me-1"></i>
                                <span></span>
                            </div>
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="user_id" value="{{$userDetails->id}}">
                            <img id="profile-photo" src="{{ isset($userDetails->profile->image_url) ? asset($userDetails->profile->image_url) : asset('assets/img/placeholder.png') }}" width="100%" height="300px" alt="Profile" style="object-fit: contain;">
                            <br><br><br>
                            <input data-parsley-error-message="Please choose an image" name="image_url" id="image" type="file" accept="image/jpeg" title="Choose Image" required>
                            <div id="progress-bar-container" style="display: none;">
                                <progress id="progress-bar" value="0" max="100"></progress>
                                <span id="progress-percentage">0%</span>
                            </div>

                            <button id="save-new-profile-pic-btn" type="submit" class="btn btn-primary btn-sm">Save Photo</button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        </form>
                    </div>

                </div>
            </div>
        </div><!-- End Basic Modal-->
    </section>

</main>



@include('layouts.footer')

<script>
    function capitalizeFirstLetter(input) {
        input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1);
    }

    function capitalizeEveryWord(input) {
        var words = input.value.split(' ');

        for (var i = 0; i < words.length; i++) {
            words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
        }

        input.value = words.join(' ');
    }
</script>

<script>
    // Image preview logic
    $('#image').change(function(e) {
        const file = e.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a new FileReader
            reader.onload = function(event) {
                $('#profile-photo').attr('src', event.target.result); // Set the src attribute of the image
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    });

    $('#change-profile-pic-form').submit(function(event) {
        event.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            method: "POST",
            url: "/profile/update/photo",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $("#edit-photo-alert-success").removeClass('d-none');
                $("#edit-photo-alert-success").addClass('show');
                $('#edit-photo-alert-success span').text(response.message);
                $('#save-new-profile-pic-btn').prop('disabled', true);

                setTimeout(function() {
                    $("#edit-photo-alert-success").addClass('d-none');
                    window.location.reload();
                }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#save-new-profile-pic-btn').prop('disabled', false);
            }
        })
    })

    $('#currentPassword').on('input', function() {
        let password = $(this).val();

        $.ajax({
            url: '/auth/check-password/',
            method: 'GET',
            data: {
                password: password
            },
            success: function(response) {
                $('#password-error').removeClass('text-danger');
                $('#password-error').addClass('text-success');
                $('#password-error').text(response.message);

                $('#newPassword').removeAttr('disabled');
                $('#renewPassword').removeAttr('disabled');
                $('#change-password-btn').removeAttr('disabled');

            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 401) {
                    $('#password-error').removeClass('text-success');
                    $('#password-error').addClass('text-danger');
                    $('#password-error').text(jqXHR.responseJSON.message);

                    $('#newPassword').attr('disabled', true);
                    $('#renewPassword').attr('disabled', true);
                    $('#change-password-btn').attr('disabled', true);
                }
            }
        })
    })

    $('#password-change-form').submit(function(event) {
        var newPassword = $('#newPassword').val();
        var renewPassword = $('#renewPassword').val();

        if (newPassword !== renewPassword) {

            $('#renewPassword-feedback span').text('Passwords do not match.');
            event.preventDefault();


        } else {
            event.preventDefault()

            if (this.checkValidity() === true) {

                let formData = $(this).serialize();

                $.ajax({
                    method: 'post',
                    url: '/auth/change-password/',
                    data: formData,
                    success: function(response) {
                        $('#alert-password-change-success').removeClass('d-none');
                        $('#alert-password-change-success').addClass('show');
                        $('#alert-password-change-success span').text(response.message);

                        setTimeout(function() {
                            window.location.replace("/auth/login/");
                        }, 3000)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 401) {
                            alert('An Error Occured, Failled to change your password');
                        }
                    }
                })
            }
        }
    });

    let profileUpdateTimestamp = $("#last-update-timestamp").text();
    const momentTimestamp = moment(profileUpdateTimestamp);
    const relativeTime = momentTimestamp.fromNow();
    $("#last-update").text("updated " + relativeTime)
</script>