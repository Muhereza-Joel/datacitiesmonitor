<!-- ======= Sidebar ======= -->
@php
$other_organizations = session('other_organizations');
@endphp
<aside id="sidebar" class="sidebar">

  <style>
    .nav-link.active {
      color: #fff;
      border-left: 10px solid #14445e;
      background-color: #181f1d;
    }
  </style>


  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Dashboard') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('theory.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Theories of Change') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('indicators.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Indicators') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('organisations.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Organisations') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('users.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Users') }}</span>
      </a>
    </li>

    <hr>

    <li class="nav-item my-3">
      <a class="nav-link" href="{{ route('archives.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Archives') }}</span>
      </a>
    </li>

    <li class="nav-heading">Other organisatons</li>
    @if($other_organizations->isEmpty())
    <p>No organisation found...</p>
    @else
    @foreach($other_organizations as $row)
    <a href="" class="btn btn-link">
      <span>
        {{ $row->name !== 'Administrator.' ? $row->name : '' }}
        @if ($row->name !== 'Administrator.')
        <i class="bi bi-box-arrow-in-up-right ms-2"></i>
        @endif
      </span>
    </a><br>
    @endforeach

    @endif

  </ul>

</aside><!-- End Sidebar-->