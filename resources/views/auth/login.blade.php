@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="offset-md-2">
            <div class="col-lg-4 col-md-4 d-flex flex-column align-items-center justify-content-center">
                <div class="card">
                    <div class="d-flex flex-column justify-content-center">
                        <a href="#" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('assets/img/logo_yellow.png') }}" style="width: 300px; object-fit:contain;" alt="logo">
                        </a>
                    </div>
                    <!-- End Logo -->
                    <div class="card-title text-center">M $ E Monitor</div>
                    <div class="card-body">
                        <div class="pt-0 text-center">
                            <p class="small">{{__('Enter your username & password to login.')}}</p>
                        </div>

                        <div id="invalid-login" class="alert alert-danger alert-dismissible fade d-none p-1" role="alert">
                            <span class="text-center"></span>
                        </div>

                        <form id="login-form" method="POST" action="{{ route('login') }}" class="row g-3 needs-validation" novalidate>
                            @csrf
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
                            <div class="col-12">
                                <label for="identifier" class="form-label">{{__('Username or Email')}}</label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="identifier" value="{{ old('identifier' )}}" id="yourUsername" required placeholder="Enter your username or email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="yourPassword" class="form-label">Password.</label>
                                <div class="input-group has-validation">
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="yourPassword" required placeholder="Enter your password">
                                        <button class="btn btn-secondary" type="button" id="togglePasswordVisibility" onclick="togglePasswordVisibility()"><i class="bi bi-eye"></i></button>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="col-12">

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                            </div>

                            <div class="col-12">

                                <button type="submit" class="btn btn-secondary w-100" id="login-button">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                <div class="text-center">

                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                                @endif

                            </div>
                        </form>


                    </div>
                </div>

                
            </div>
            
        </div>
        <div class="d-flex offset-md-1 align-items-center mt-2">
            <div class="alert">
                <a class="btn btn-link fw-bold" target="_blank" title="M $ E Monitor User Manual" href="https://monitor-docs.opendata-analytics.org">User Manual</a> |
                <a class="btn btn-link fw-bold" href="#">Privacy Policy</a> |
                <a class="btn btn-link fw-bold" href="#">Terms of Use</a>

            </div>
        </div>
    </div>

</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#togglePasswordVisibility').click(function() {
            let passwordInput = $('#yourPassword');
            let passwordVisibilityIcon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                passwordVisibilityIcon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                passwordVisibilityIcon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
    })
</script>
@endsection
<!-- End #main -->
@endsection