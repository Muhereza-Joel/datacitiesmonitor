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
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Dashboard') }}</span>
      </a>
    </li>

    <li class="nav-item dashboard-tour-step-1">
      <a class="nav-link" href="{{ route('theory.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Theories of Change') }}</span>
      </a>
    </li>


    <li class="nav-item">
      <a class="nav-link" href="{{ route('indicators.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Indicators') }}</span>
      </a>
    </li>


    @if (str_starts_with(Auth::user()->organisation->name, 'Administrator'))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('organisations.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Organisations') }}</span>
      </a>
    </li>
    @endif

    <li class="nav-item dashboard-tour-step-2">
      <a class="nav-link" href="{{ route('archives.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Archives') }}</span>
      </a>
    </li>
    
    @if( Auth::user()->role === 'admin' || Auth::user()->role === 'root')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('users.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('Users') }}</span>
      </a>
    </li>

    <li class="nav-item dashboard-tour-step-3">
      <a class="nav-link" href="{{ route('logs.index') }}">
        <img src="{{ isset(Auth::user()->organisation->logo) ? asset(Auth::user()->organisation->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">
        <span>{{ __('User Activity') }}</span>
      </a>
    </li>
    @endif

    
    <li class="nav-heading dashboard-tour-step-4">Publications</li>
    @if($other_organizations->isEmpty())
    <p>No organisation found...</p>
    @else
    @foreach($other_organizations as $row)
    @if (!str_starts_with($row->name, 'Administrator'))
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#org-nav-{{ $row->id }}" data-bs-toggle="collapse" href="#" aria-expanded="false">
      <img src="{{ isset($row->logo) ? asset($row->logo) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle bg-light p-1 me-1" width="30px" height="30px" style="object-fit: cover; border: 2px solid #fff">{{ $row->name }}</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="org-nav-{{ $row->id }}" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a class="publication-link" href="{{ route('organisation.publications', [$row->id, 'type' => 'public_indicators']) }}">
            <i class="bi bi-circle bg-light"></i><span>Public Indicators</span>
          </a>
        </li>
        <li>
          <a class="publication-link" href="{{ route('organisation.publications', [$row->id, 'type' => 'archives']) }}">
            <i class="bi bi-circle bg-light"></i><span>Archives</span>
          </a>
        </li>
      </ul>
    </li>
    @endif
    @endforeach
    @endif

  </ul>

</aside><!-- End Sidebar-->