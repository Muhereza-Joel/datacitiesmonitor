@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="container">
    <div class="row">
        <div class="mt-5">
            <div class="alert alert-warning">
                <h1>403 - Forbidden</h1>
                <p>{{ $message ?? 'You do not have permission to access this page.' }}</p>
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">Return to Dashboard</a>
            </div>

        </div>
    </div>
</div>
@endsection