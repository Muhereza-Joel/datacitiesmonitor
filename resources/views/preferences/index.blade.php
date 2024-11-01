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
                                    <small class="text-secondary">Allow Monitor to automatically save response data when adding responses to indicators in your browser, such that it can be restored when you navigate back to add a response the same indicator.</small>
                                </div>

                                <div class="form-check form-switch my-2">
                                    <input {{ session('user.preferences.dark_mode') === "true" ? 'checked' : '' }} class="form-check-input js-preference-toggle" type="checkbox" data-key="dark_mode" id="darkModeToggle">
                                    <label class="form-check-label fw-bold" for="darkModeToggle">Enable Dark Theme</label><br>
                                    <small class="text-secondary">Allow Monitor to switch between dark and light themes.</small>
                                    <div class="alert alert-info">The theme will change instantly.</div>
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
                                    <small class="text-secondary d-block mt-1">
                                        Add an additional layer of security to your account. Once enabled, you will be prompted to choose another authentication method that will be used when you are loging in to your account.
                                    </small>
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
                                            Set Security Question
                                        </label>
                                    </div>

                                    <!-- One-Time Password Option -->
                                    <div class="form-check mt-2">
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
                                    </div>
                                </div>

                                <!-- Security Question Form -->
                                <div id="securityQuestionForm" class="mt-3 ms-3 ps-3" style="display: {{ session('user.preferences.auth_method') === 'security_question' && session('user.preferences.two_factor_auth') === 'true' ? 'block' : 'none' }}" ;>
                                    <div class="alert alert-warning p-2">Please do not forget to set a question and its answer if you choose security question as auth option</div>
                                    <label for="securityQuestion" class="fw-bold">Security Question:</label>
                                    <input
                                        type="text"
                                        class="form-control mt-1 js-preference-toggle"
                                        id="securityQuestion"
                                        placeholder="Enter your security question here"
                                        data-key="security_question">

                                    <small class="text-secondary d-block mt-1">
                                        Choose a question that only you know the answer to for added security.
                                    </small>
                                </div>

                                <!-- Security Question Answer Form -->
                                <div id="securityQuestionAnswerForm" class="mt-3 ms-3 ps-3" style="display: {{ session('user.preferences.auth_method') === 'security_question' && session('user.preferences.two_factor_auth') === 'true' ? 'block' : 'none' }}">
                                    <label for="securityQuestionAnswer" class="fw-bold">Security Question Answer:</label>
                                    <input
                                        type="text"
                                        class="form-control mt-1 js-preference-toggle"
                                        id="securityQuestionAnswer"
                                        placeholder="Enter your security question answer here"
                                        data-key="security_question_answer">

                                    <small class="text-secondary d-block mt-1">
                                        What is the answer for the above question that you have set.
                                    </small>
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
                                    <small class="text-secondary">Allow Monitor to show a graphical ruller on indicators on the listing card that shows the progress for each indicator with regard to its baseline and target</small>
                                    <div class="alert alert-warning p-2 mt-1"><strong>Note: </strong>Turning off this setting makes interpriting progress hard</div>
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

        // Show Security Question form based on radio selection
        $('input[name="auth_method"]').change(function() {
            if ($(this).val() === 'security_question') {
                $('#securityQuestionForm').show();
                $('#securityQuestionAnswerForm').show();
            } else {
                $('#securityQuestionForm').hide();
                $('#securityQuestionAnswerForm').hide();
            }

            // Update the selected authentication method
            let key = 'auth_method';
            let value = $(this).val();

            $.ajax({
                url: '/preferences/update',
                method: 'PUT',
                data: {
                    key,
                    value
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        // Handle preference updates for checkbox
        $('.js-preference-toggle').change(function() {
            let key = $(this).data('key');
            let value = this.checked;

            $.ajax({
                url: '/preferences/update',
                method: 'PUT',
                data: {
                    key,
                    value
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        // Handle the security question input change
        $('#securityQuestion').on('input', function() {
            let key = $(this).data('key');
            let value = $(this).val();

            $.ajax({
                url: '/preferences/update',
                method: 'PUT',
                data: {
                    key,
                    value
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });

        // Handle the security question answer input change
        $('#securityQuestionAnswer').on('input', function() {
            let key = $(this).data('key');
            let value = $(this).val();

            $.ajax({
                url: '/preferences/update',
                method: 'PUT',
                data: {
                    key,
                    value
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response.message);
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
    });
</script>