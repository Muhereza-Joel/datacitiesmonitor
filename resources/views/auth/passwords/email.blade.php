@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="d-flex flex-column justify-content-center">
                    <a href="#" class="logo d-flex align-items-center w-auto">
                        <img src="{{ asset('assets/img/logo_yellow.png') }}" style="width: 300px; object-fit:contain;" alt="logo">
                    </a>
                </div>
                <!-- End Logo -->
                <div class="text-center">
                    <div class="card-title">M $ E Account Recouvery</div>
                    <h6 class="text-dark">Ooh Sorry for loosing access to your account, please provide your email address you used when creating this account.</h6>
                </div>


                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3 my-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Your Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" placeholder="Please provide your email address..." class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0 mt-4">
                            <div class="col-md-10 offset-md-2 d-flex">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-secondary mx-2">
                                    {{ __('Go back to login') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection