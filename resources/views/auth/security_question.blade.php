@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="offset-md-2">
            <div class="col-lg-4 col-md-4 d-flex flex-column align-items-center justify-content-center">
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                      <span>{{ $error }}</span>
                    @endforeach

                </div>
                @endif
                <div class="card">
                    <div class="d-flex flex-column justify-content-center">
                        <a href="#" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('assets/img/logo_yellow.png') }}" style="width: 300px; object-fit:contain;" alt="logo">
                        </a>
                    </div>
                    <div class="card-title text-center">M $ E Monitor</div>
                    <div class="card-body">
                        <form method="POST" class="" action="{{ route('verify.security_question.check') }}">
                            @csrf
                            <h4 class="fw-bold text-center">Security Check</h4>
                            <div class="alert alert-warning">To ensure that you are the one trying to access your account, please answer the question below. </div>
                            <p>{{ $question }}</p>
                            <input autocomplete="off" class="form-control" type="text" name="answer" required placeholder="Your Answer">
                            <div class="text-center">
                                <button class="btn btn-sm btn-primary mt-3" type="submit">Verify Your Answer</button>
                                <a href="{{ route('login') }}" class="btn btn-sm btn-danger mt-3" type="submit">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection

@include('layouts.footer')