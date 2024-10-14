@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="container">
    <div class="row">
        <div class="mt-5">
            <h1 class="display-4">404 - Page Not Found</h1>
            <p class="lead">Oops! The page you’re looking for doesn’t exist or might have been moved.</p>
            <p class="mb-4">You can go back to the dashboard or use one of the options below.</p>
            
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Return to Dashboard</a>
            </div>

        </div>
    </div>
</div>
@endsection
