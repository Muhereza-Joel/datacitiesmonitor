@include('layouts.header')

@include('layouts.topBar');

@include('layouts.leftPane');

@php
use \Carbon\Carbon;
@endphp

<style>
    .event-visibility h6 {
        font-size: 1.25rem;
        margin-bottom: 10px;
    }

    .event-visibility p {
        margin-bottom: 10px;
    }

    .event-visibility ul {
        list-style-type: disc;
        padding-left: 20px;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Manage Areas Of Focus</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard/">Home</a></li>
                <li class="breadcrumb-item active">Manage Areas Of Focus</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
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

    <section class="section dashboard">

    </section>



</main><!-- End #main -->


@include('layouts.footer')

<script>

</script>