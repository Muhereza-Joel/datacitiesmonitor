@php
$other_organizations = session('other_organizations');
@endphp

<aside id="sidebar" class="sidebar p-0 d-flex border-end">
  <style>
    #sidebar {
      width: 320px;
      transition: all 0.3s;
      overflow: hidden;
      display: flex;
      background-color: var(--bs-tertiary-bg);
      height: 100vh;
    }

    /* Icon Rail */
    .icon-rail {
      width: 65px;
      background-color: var(--bs-dark-bg-subtle);
      border-right: 1px solid var(--bs-border-color);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 30px;
      flex-shrink: 0;
    }

    .rail-item {
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      margin-bottom: 15px;
      color: var(--bs-secondary-color);
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .rail-item:hover {
      color: var(--bs-primary);
      background-color: var(--bs-secondary-bg);
    }

    .rail-item.active {
      background-color: var(--bs-primary);
      color: #fff !important;
    }

    /* Menu Pane */
    .menu-pane {
      flex-grow: 1;
      overflow-y: auto;
      background-color: var(--bs-body-bg);
    }

    .menu-group {
      display: none;
      padding: 15px;
    }

    .menu-group.active {
      display: block;
    }

    /* Skeleton Styles */
    .skeleton {
      background: linear-gradient(90deg, var(--bs-secondary-bg) 25%, var(--bs-tertiary-bg) 50%, var(--bs-secondary-bg) 75%);
      background-size: 200% 100%;
      animation: skeleton-loading 1.5s infinite ease-in-out;
      border-radius: 4px;
      display: inline-block;
    }

    @keyframes skeleton-loading {
      0% {
        background-position: 200% 0;
      }

      100% {
        background-position: -200% 0;
      }
    }

    .skeleton-item {
      width: 100%;
      height: 35px;
      margin-bottom: 10px;
    }

    .skeleton-text {
      width: 80%;
      height: 12px;
    }

    /* Navigation Items */
    .sidebar-nav .nav-link {
      font-size: 14px;
      font-weight: 500;
      color: var(--bs-heading-color);
      padding: 10px 15px;
      border-radius: 4px;
      display: flex;
      align-items: center;
      margin-bottom: 5px;
      transition: 0.2s;
    }

    .sidebar-nav .nav-link i {
      font-size: 18px;
      margin-right: 10px;
      color: var(--bs-secondary-color);
    }

    .sidebar-nav .nav-link:hover,
    .sidebar-nav .nav-link.active {
      color: var(--bs-primary);
      background-color: var(--bs-primary-bg-subtle);
    }

    .nav-heading {
      font-size: 11px;
      text-transform: uppercase;
      font-weight: 700;
      color: var(--bs-secondary-color);
      margin: 15px 0 10px 15px;
      opacity: 0.7;
    }
  </style>

  <div class="icon-rail">
    <!-- M and E Icon -->
    <div class="rail-item" data-target="me-menu" title="M and E">
      <i class="bi bi-speedometer2"></i>
    </div>
    <!-- Reporting Icon -->
    <div class="rail-item" data-target="reporting-menu" title="Reporting">
      <i class="bi bi-file-earmark-bar-graph"></i>
    </div>
    <div class="rail-item" data-target="org-menu" title="Organizations & Publications">
      <i class="bi bi-building"></i>
    </div>
    <div class="rail-item" data-target="history-menu" title="History & Administration">
      <i class="bi bi-clock-history"></i>
    </div>
    <div class="rail-item" data-target="support-menu" title="Support">
      <i class="bi bi-question-circle"></i>
    </div>
  </div>

  <div class="menu-pane">
    <!-- Skeleton Placeholder -->
    <div id="sidebar-skeleton" class="p-3">
      <div class="skeleton skeleton-text mb-4" style="width: 40%"></div>
      <div class="skeleton skeleton-item"></div>
      <div class="skeleton skeleton-item"></div>
      <div class="skeleton skeleton-item"></div>
      <div class="skeleton skeleton-text mt-4 mb-4" style="width: 30%"></div>
      <div class="skeleton skeleton-item"></div>
    </div>

    <ul class="sidebar-nav d-none" id="sidebar-nav">

      <!-- M and E Pane -->
      <div id="me-menu" class="menu-group">
        <li class="nav-heading">M and E Module</li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-eye"></i>
            <span>{{ __('Overview') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('theory.*') ? 'active' : '' }}" href="{{ route('theory.index') }}">
            <i class="bi bi-diagram-3"></i>
            <span>{{ __('Theories of Change') }}</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('indicators.*') ? 'active' : '' }}" href="{{ route('indicators.index') }}">
            <i class="bi bi-speedometer2"></i>
            <span>{{ __('Indicators') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}" href="{{ route('calendar') }}">
            <i class="bi bi-calendar3"></i>
            <span>{{ __('Calendar') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('archives.index') ? 'active' : '' }}" href="{{ route('archives.index') }}">
            <i class="bi bi-archive"></i>
            <span>{{ __('Archives') }}</span>
          </a>
        </li>
      </div>

      <!-- Reporting Pane -->
      <div id="reporting-menu" class="menu-group">
        <li class="nav-heading">Reporting Module</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('areas-of-focus.*') ? 'active' : '' }}" href="{{ route('areas-of-focus.index') }}">
            <i class="bi bi-book"></i>
            <span>{{ __('Areas of Focus') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
            <i class="bi bi-file-text"></i>
            <span>{{ __('Reports') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('report-areas.index') ? 'active' : '' }}" href="{{ route('report-areas.index') }}">
            <i class="bi bi-geo"></i>
            <span>{{ __('Report Areas') }}</span>
          </a>
        </li>
      </div>

      <!-- Organizations Pane -->
      <div id="org-menu" class="menu-group">
        <li class="nav-heading">Publications Module</li>
        @if(isset($other_organizations) && !$other_organizations->isEmpty())
        @foreach($other_organizations as $row)
        @if (!str_starts_with($row->name, 'Administrator'))
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#org-nav-{{ $row->id }}" data-bs-toggle="collapse" href="#">
            <img src="{{ isset($row->logo) ? asset($row->logo) : asset('assets/img/placeholder.png') }}" class="rounded-circle me-2" width="20px" height="20px">
            <span class="text-truncate" style="max-width: 140px;">{{ $row->name }}</span>
            <i class="bi bi-chevron-down ms-auto" style="font-size: 12px;"></i>
          </a>
          <ul id="org-nav-{{ $row->id }}" class="nav-content collapse list-unstyled ps-4" data-bs-parent="#sidebar-nav">
            <li><a href="{{ route('organisation.publications', [$row->id, 'type' => 'public_indicators']) }}" class="small py-1 d-block text-muted">Public Indicators</a></li>
            <li><a href="{{ route('organisation.publications', [$row->id, 'type' => 'archives']) }}" class="small py-1 d-block text-muted">Archives</a></li>
          </ul>
        </li>
        @endif
        @endforeach
        @else
        <li class="px-3 small text-muted">No other organisations</li>
        @endif
      </div>

      <!-- History/Admin Pane -->
      <div id="history-menu" class="menu-group">
        <li class="nav-heading">System Admin Module</li>
        @if( Auth::user()->role === 'admin' || Auth::user()->role === 'root')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
            <i class="bi bi-clock"></i> <span>Scheduling</span>
          </a>
        </li>
        @if (str_starts_with(Auth::user()->organisation->name, 'Administrator'))
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('organisations.index') ? 'active' : '' }}" href="{{ route('organisations.index') }}">
            <i class="bi bi-bank"></i> <span>Organisations</span>
          </a>
        </li>
        @endif
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
            <i class="bi bi-folder"></i>
            <span>{{ __('Projects') }}</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="bi bi-people"></i> <span>Users</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('logs.index') ? 'active' : '' }}" href="{{ route('logs.index') }}">
            <i class="bi bi-journal-text"></i> <span>User Activity</span>
          </a>
        </li>
        @else
        <li class="px-3 small text-muted">Administrative access restricted.</li>
        @endif
      </div>

      <div id="support-menu" class="menu-group">
        <li class="nav-heading">Help & Resources Module</li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-info-circle"></i> <span>Documentation</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-envelope"></i> <span>Contact Support</span></a></li>
      </div>

    </ul>
  </div>
</aside>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const railItems = document.querySelectorAll('.rail-item');
    const menuGroups = document.querySelectorAll('.menu-group');
    const skeleton = document.getElementById('sidebar-skeleton');
    const sidebarNav = document.getElementById('sidebar-nav');

    function switchRail(targetId, saveState = true) {
      railItems.forEach(i => i.classList.remove('active'));
      menuGroups.forEach(group => group.classList.remove('active'));

      const targetRail = document.querySelector(`.rail-item[data-target="${targetId}"]`);
      const targetMenu = document.getElementById(targetId);

      if (targetRail && targetMenu) {
        targetRail.classList.add('active');
        targetMenu.classList.add('active');
        if (saveState) localStorage.setItem('activeRail', targetId);
      }

      // Swap skeleton for real content after state is determined[cite: 8]
      if (skeleton) skeleton.classList.add('d-none');
      if (sidebarNav) sidebarNav.classList.remove('d-none');
    }

    railItems.forEach(item => {
      item.addEventListener('click', function() {
        switchRail(this.getAttribute('data-target'));
      });
    });

    const savedRail = localStorage.getItem('activeRail');
    const activeLink = document.querySelector('.sidebar-nav .nav-link.active');

    if (activeLink) {
      const parentGroup = activeLink.closest('.menu-group');
      if (parentGroup) {
        switchRail(parentGroup.id);
      }
    } else if (savedRail) {
      switchRail(savedRail, false);
    } else {
      switchRail('me-menu', false);
    }
  });
</script>