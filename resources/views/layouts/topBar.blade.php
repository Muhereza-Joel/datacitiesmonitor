<!-- ======= Header ======= -->

<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">

    <a href="{{ url('dashboard', null, true) }}" class="logo d-flex align-items-center">
      <span class="d-none d-lg-block">M & E Monitor</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="GET" action="{{ route('search') }}">
      <input type="text" name="query" placeholder="Search Indicators" title="Enter search keyword" required>
      <button type="submit" title="Search"><i class="bi bi-search"></i></button>
    </form>
  </div>



  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li>
      <li class="nav-item dropdown">

        @php
        $notifications = Auth::user()->notifications;
        @endphp
        <a class="nav-link nav-icon show" href="#" data-bs-toggle="dropdown" aria-expanded="true">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">{{$notifications->count()}}</span>
        </a><!-- End Notification Icon -->


        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-25px, 35px);">
          <li class="dropdown-header">
            You have {{$notifications->count()}} new notifications
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          @if($notifications->count())
          @foreach($notifications as $notification)
          <li class="notification-item">
            @if($notification->data['notification_type'] === 'warning')
            <i class="bi bi-exclamation-circle text-warning"></i>
            @elseif($notification->data['notification_type'] === 'info')
            <i class="bi bi-info-circle text-info"></i>
            @elseif($notification->data['notification_type'] === 'success')
            <i class="bi bi-check-circle text-success"></i>
            @elseif($notification->data['notification_type'] === 'danger')
            <i class="bi bi-x-circle text-danger"></i>
            @else
            <i class="bi bi-bell text-secondary"></i> <!-- Default icon -->
            @endif
            <a href="{{ $notification->data['request_verification_url'] }}">
              <div>
                <h4>{{ $notification->data['notification_title'] }}</h4>
                <p>{{ $notification->data['message'] }}</p>
                <p>{{ $notification->created_at->diffForHumans() }}</p>
              </div>
            </a>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>
          @endforeach
          @endif

          <li class="dropdown-footer">
            <a href="#">Show all notifications</a>
          </li>

        </ul><!-- End Notification Dropdown Items -->

      </li>
      <li class="nav-item dropdown pe-3 dashboard-tour-step-5">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="{{ isset(Auth::user()->profile->image_url) ? asset(Auth::user()->profile->image_url) : asset('assets/img/placeholder.png') }}" alt="Profile" class="rounded-circle" width="40px" height="40px" style="object-fit: cover; border: 2px solid #fff">
          <span class="d-none d-md-block dropdown-toggle px-2">Hello, {{ Auth::user()->name }}</span>
        </a><!-- End Profile Image Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

          <li class="dropdown-header">
            <span class="text-primary">Signed In As</span>
            <h6>{{ Auth::user()->name }}</h6>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center justify-content-start" href="{{ route('profile.show') }}">
              <i class="bi bi-person"></i>
              <span>Manage Your Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center justify-content-start" href="https://monitor-docs.opendata-analytics.org" target="_blank">
              <i class="bi bi-book"></i> <!-- Changed to a book icon for User Manual -->
              <span>Visit User Manual</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center justify-content-start" href="#">
              <i class="bi bi-file-earmark-text"></i> <!-- Changed to a document icon for Terms of Use -->
              <span>Terms of Use</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center justify-content-start" href="#">
              <i class="bi bi-shield-lock"></i> <!-- Changed to a shield lock icon for Privacy Policy -->
              <span>Our Privacy Policy</span>
            </a>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center justify-content-start" href="{{ route('preferences.show') }}">
              <i class="bi bi-gear"></i>
              <span>Your Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>


          <li>
            <a class="dropdown-item d-flex align-items-center justify-content-start" href="{{ route('logout') }}">
              <i class="bi bi-box-arrow-right"></i>
              <span onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </span>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->


</header><!-- End Header -->