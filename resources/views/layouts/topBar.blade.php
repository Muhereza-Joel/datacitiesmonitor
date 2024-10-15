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
      <li class="nav-item dropdown pe-3">
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
              <span>Your Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center justify-content-start" href="#">
              <i class="bi bi-gear"></i>
              <span>Your Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
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