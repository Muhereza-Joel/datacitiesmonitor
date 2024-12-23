@include('layouts.header')

@include('layouts.topBar')

@include('layouts.leftPane')

<main id="main" class="main">

    <section class="section dashboard mt-3">

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

        <div class="pagetitle">
            <div class="d-flex">
                <div class="text-start w-50">
                    <h1>Your Account Settings</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Account Settings</li>
                        </ol>
                    </nav>

                </div>


            </div>
        </div><!-- End Page Title -->

        <div class="card py-3">

            <div class="card-body">
                <div class="row">
                    <!-- Vertical Tabs List -->
                    <div class="col-md-3">
                        <ul class="nav flex-column nav-pills" id="settingsTab" role="tablist">
                            <li class="nav-item my-1" role="presentation">
                                <a class="nav-link active" id="general-tab" data-bs-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">General Settings</a>
                            </li>
                            <li class="nav-item my-1" role="presentation">
                                <a class="nav-link" id="appearance-tab" data-bs-toggle="pill" href="#appearance" role="tab" aria-controls="appearance" aria-selected="false">Display Settings</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link my-1" id="notifications-tab" data-bs-toggle="pill" href="#notifications" role="tab" aria-controls="notifications" aria-selected="false">Notifications Settings</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="col-md-9">
                        <div class="tab-content" id="settingsTabContent">
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <h5>Top Level Settings</h5>
                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.auto_save') === "true" ? 'checked' : '' }} class="form-check-input js-preference-toggle" type="checkbox" data-key="auto_save" id="autoSave">
                                    <label class="form-check-label fw-bold" for="autoSave">Enable Auto-Save</label><br>
                                    <small class="text-secondary">Enable automatic saving of response data while adding responses to indicators. This ensures your input is retained and can be restored if you return to add a response to the same indicator.</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.dark_mode') === "true" ? 'checked' : '' }} class="form-check-input js-preference-toggle" type="checkbox" data-key="dark_mode" id="darkModeToggle">
                                    <label class="form-check-label fw-bold" for="darkModeToggle">Enable Dark Theme</label><br>
                                    <small class="text-secondary">Enable theme switching between dark and light modes for a customized viewing experience.</small>
                                    <div class="alert alert-info">Changes to the theme will take effect immediately.</div>
                                </div>
                                <hr>
                                <h5>Two-Factor Authentication</h5>
                                <div class="form-check form-switch my-3">
                                    <input
                                        {{ session('user.preferences.two_factor_auth') === "true" ? 'checked' : '' }}
                                        class="form-check-input js-preference-toggle"
                                        type="checkbox"
                                        data-key="two_factor_auth"
                                        id="twoFactorAuthToggle">
                                    <label class="form-check-label fw-bold" for="twoFactorAuthToggle">
                                        Enable Two-Factor Authentication
                                    </label>
                                    <small class="text-secondary d-block mt-1"> Enhance your account security with an additional verification step. Once enabled, you'll select an authentication method to be used for verifying your identity during login. </small>
                                </div>

                                <!-- Options that depend on Two-Factor Authentication -->
                                <div id="authOptions" class="mt-3 ms-3 ps-3" style="display: {{ session('user.preferences.two_factor_auth') === 'true' ? 'block' : 'none' }};">
                                    <label class="fw-bold">Choose Authentication Method:</label>

                                    <!-- Security Question Option -->
                                    <div class="form-check mt-2">
                                        <input
                                            {{ session('user.preferences.auth_method') === 'security_question' ? 'checked' : '' }}
                                            class="form-check-input js-auth-method-toggle"
                                            type="radio"
                                            name="auth_method"
                                            value="security_question"
                                            id="securityQuestionOption">
                                        <label class="form-check-label" for="securityQuestionOption">
                                            Security Question
                                        </label>
                                    </div>

                                    <!-- One-Time Password Option -->
                                    <!-- <div class="form-check mt-2">
                                        <input
                                            {{ session('user.preferences.auth_method') === 'otp' ? 'checked' : '' }}
                                            class="form-check-input js-auth-method-toggle"
                                            type="radio"
                                            name="auth_method"
                                            value="otp"
                                            id="otpOption">
                                        <label class="form-check-label" for="otpOption">
                                            Receive One-Time Password (OTP)
                                        </label>
                                    </div> -->
                                </div>

                                <!-- Security Question Form -->
                                <div id="securityQuestionForm" class="mt-3 ms-3 ps-3" style="display: {{ session('user.preferences.auth_method') === 'security_question' && session('user.preferences.two_factor_auth') === 'true' ? 'block' : 'none' }}">
                                    <div class="alert alert-warning p-2">Please provide a security question and its answer to complete the two-factor authentication setup.</div>

                                    <!-- Security Question Input -->
                                    <label for="securityQuestion" class="fw-bold">Security Question:</label>
                                    <input
                                        type="text"
                                        class="form-control mt-1 js-preference-toggle"
                                        id="securityQuestion"
                                        placeholder="Enter your security question here"
                                        required
                                        data-key="security_question">
                                    <small class="text-secondary d-block mt-1"> Select a question that only you can answer to enhance your account’s security. Avoid using easily guessed questions to keep your account more secure. </small>

                                    <!-- Security Question Answer Input -->
                                    <label for="securityQuestionAnswer" class="fw-bold mt-3">Security Question Answer:</label>
                                    <input
                                        type="text"
                                        class="form-control mt-1 js-preference-toggle"
                                        id="securityQuestionAnswer"
                                        placeholder="Enter your security question answer here"
                                        required
                                        data-key="security_question_answer">
                                    <small class="text-secondary d-block mt-1"> Provide the answer to your chosen security question above. Make sure it’s something memorable and secure. </small>

                                    <!-- Save Button -->
                                    <button id="saveSecurityQuestion" class="btn btn-primary btn-sm mt-3">Save</button>
                                </div>

                            </div>

                            <!-- Appearance Settings -->
                            <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                                <h5>Display Settings For ToCs</h5>
                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.toc_compact_mode') === 'true' ? 'checked' : '' }} data-key="toc_compact_mode" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">ToC Compact Mode</label><br>
                                    <small class="text-secondary">Allow Monitor to collapse toc description accordion automatically.</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_toc_create_date') === 'true' ? 'checked' : '' }} data-key="show_toc_create_date" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Create Date For ToCs</label><br>
                                    <small class="text-secondary">Allow Monitor to display the date when ToC is created in the listing card.</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_toc_organisation_logo') === 'true' ? 'checked' : '' }} data-key="show_toc_organisation_logo" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Organisation Logos on ToC</label><br>
                                    <small class="text-secondary">Allow Monitor show the associated logo for the organisation. This helps to show where the ToC belongs</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_toc_indicators_count') === 'true' ? 'checked' : '' }} data-key="show_toc_indicators_count" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Indicators Count For ToCs</label><br>
                                    <small class="text-secondary">Allow Monitor show the number of indicators attached to each theory of change on the listing card</small>
                                </div>

                                <hr>
                                <h5>Display Settings For Indicator</h5>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_indicator_create_date') === 'true' ? 'checked' : '' }} data-key="show_indicator_create_date" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Create Date For Indicators</label><br>
                                    <small class="text-secondary">Allow Monitor to display the date when indicator was created on the listing card.</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_indicator_organisation_logo') === 'true' ? 'checked' : '' }} data-key="show_indicator_organisation_logo" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Organisation Logos on Indicators</label><br>
                                    <small class="text-secondary">Allow Monitor show organisation logo. This helps to show the organisation the indicator is associated with</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_indicator_response_count') === 'true' ? 'checked' : '' }} data-key="show_indicator_response_count" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Response Count For Indicators</label><br>
                                    <small class="text-secondary">Allow Monitor show the number of reponses for an indicator on the listing card</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_indicator_category') === 'true' ? 'checked' : '' }} data-key="show_indicator_category" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Indicator Category</label><br>
                                    <small class="text-secondary">Allow Monitor show the category an indicator on the listing card</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_indicator_qualitative_status') === 'true' ? 'checked' : '' }} data-key="show_indicator_qualitative_status" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Indicator Qualitative Status</label><br>
                                    <small class="text-secondary">Allow Monitor show the qualitative status of indicators on the listing card</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_indicator_ruller') === 'true' ? 'checked' : '' }} data-key="show_indicator_ruller" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Enable Indicator Ruller</label><br>
                                    <small class="text-secondary">Enable Monitor to display a graphical ruler on indicator cards, visually showing progress relative to each indicator's baseline and target.</small>

                                    <div class="alert alert-warning p-2 mt-1"><strong>Note:</strong> Disabling this feature may make progress interpretation more challenging.</div>
                                </div>

                                <hr>
                                <h5>Display Settings For Archives</h5>
                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.archive_compact_mode') === 'true' ? 'checked' : '' }} data-key="archive_compact_mode" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold">Archive Compact Mode</label><br>
                                    <small class="text-secondary">Allow Monitor to collapse archive description accordion automatically.</small>
                                </div>
                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_archive_status') === 'true' ? 'checked' : '' }} data-key="show_archive_status" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold">Show Archive Status</label><br>
                                    <small class="text-secondary">Allow Monitor to show the status of archives on the listing card.</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_archive_create_date') === 'true' ? 'checked' : '' }} data-key="show_archive_create_date" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold">Show Create Date For Archives</label><br>
                                    <small class="text-secondary">Allow Monitor to display the date when archive was created in the listing card.</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_archive_organisation_logo') === 'true' ? 'checked' : '' }} data-key="show_archive_organisation_logo" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold">Show Organisation Logos on Archive Cards</label><br>
                                    <small class="text-secondary">Allow Monitor show the associated logo for the archive. This helps to show where the archive belongs</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.show_archive_indicators_count') === 'true' ? 'checked' : '' }} data-key="show_archive_indicators_count" class="form-check-input js-preference-toggle" type="checkbox">
                                    <label class="form-check-label fw-bold" for="compactMode">Show Indicators Count For Archive</label><br>
                                    <small class="text-secondary">Allow Monitor show the number of indicators in an archive on the listing card</small>
                                </div>
                            </div>

                            <!-- Notifications Settings -->
                            <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                                <h5>Notifications Settings</h5>
                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.email_notifications') === 'true' ? 'checked' : '' }} class="form-check-input js-preference-toggle" data-key="email_notifications" type="checkbox" id="emailNotifications">
                                    <label class="form-check-label fw-bold" for="emailNotifications">Email Notifications</label><br>
                                    <small class="text-secondary">Allow Monitor to send important email notifications on your verified email address</small>
                                    <div class="alert alert-info p-2">{{Auth::user()->email}} is your currently registerd email address for notifications</div>
                                </div>
                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.sms_notifications') === 'true' ? 'checked' : '' }} class="form-check-input js-preference-toggle" data-key="sms_notifications" type="checkbox" id="smsNotifications">
                                    <label class="form-check-label fw-bold" for="smsNotifications">SMS Notifications</label><br>
                                    <small class="text-secondary">Allow Monitor to send important sms notifications on your phone number</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
</main>


@include('layouts.footer')

<script>
    $(document).ready(function() {
        // Toggle options based on Two-Factor Authentication switch
        $('#twoFactorAuthToggle').change(function() {
            $('#authOptions').toggle(this.checked);
            if (!this.checked) {
                $('#securityQuestionForm').hide();
                $('#securityQuestionAnswerForm').hide();
            } else {
                $('#securityQuestionForm').show();
                $('#securityQuestionAnswerForm').show();

            }
        });

        // Show Security Question form based on radio selection for auth method
        $('input[name="auth_method"]').change(function() {
            let authMethod = $(this).val();

            // Show or hide security question fields based on selection
            if (authMethod === 'security_question') {
                $('#securityQuestionForm').show();
                $('#securityQuestionAnswerForm').show();
            } else {
                $('#securityQuestionForm').hide();
                $('#securityQuestionAnswerForm').hide();
            }

            // Update the selected authentication method preference
            $.ajax({
                url: '/preferences/update',
                method: 'PUT',
                data: {
                    preferences: {
                        auth_method: authMethod
                    }
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Authentication method updated:', response.message);
                },
                error: function(error) {
                    console.error('Error updating authentication method:', error);
                }
            });
        });


        // Handle preference updates for checkboxes
        $('.js-preference-toggle').change(function() {
            let key = $(this).data('key');
            let value = this.checked;

            $.ajax({
                url: '/preferences/update',
                method: 'PUT',
                data: {
                    preferences: {
                        [key]: value
                    }
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(`${key} preference updated:`, response.message);
                },
                error: function(error) {
                    console.error(`Error updating ${key} preference:`, error);
                }
            });
        });


        $('#saveSecurityQuestion').on('click', function() {
            // Get values of security question and answer
            let securityQuestion = $('#securityQuestion').val().trim();
            let authMethod = "security_question";
            let securityQuestionAnswer = $('#securityQuestionAnswer').val().trim();

            // Check if either field is empty
            if (!securityQuestion || !securityQuestionAnswer) {
                showToast("Both the security question and answer fields must be filled out.", "#ff8282");
                return;
            }

            // Proceed with AJAX request if both fields have values
            $.ajax({
                url: '/preferences/update',
                method: 'PUT',
                data: {
                    preferences: {
                        auth_method: authMethod,
                        security_question: securityQuestion,
                        security_question_answer: securityQuestionAnswer
                    }
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    showToast("Question and Answer Saved!", '#28a745');
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });



        $('#darkModeToggle').on('change', function() {
            let isDarkMode = $(this).is(':checked');

            // Toggle the theme instantly by setting data attribute on body
            $('body').attr('data-bs-theme', isDarkMode ? 'dark' : 'light');

            // Save the preference in a cookie
            document.cookie = "dark_mode=" + (isDarkMode ? "true" : "false") + "; path=/; max-age=" + (30 * 24 * 60 * 60); // 30 days
        });
        var toastInstance = null;

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
    });
</script>