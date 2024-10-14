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
        
        <span>{{ __('Dashboard') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('theory.index') }}">
        
        <span>{{ __('Theories of Change') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('indicators.index') }}">
        
        <span>{{ __('Indicators') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('organisations.index') }}">
       
        <span>{{ __('Organisations') }}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('users.index') }}">
      
        <span>{{ __('Users') }}</span>
      </a>
    </li>

    <hr>

    <li class="nav-item my-3">
      <a class="nav-link" href="{{ route('archives.index') }}">
        
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