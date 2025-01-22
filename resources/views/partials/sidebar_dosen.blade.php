<ul class="navbar-nav bg-gradient-midnight-blue sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/dosen/dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img') }}/unj_sikiar.png" alt="Logo" style="width: 60px; height: 60px;">
        </div>
        <div class="sidebar-brand-text mx-3" style="font-size: 1.5rem; font-weight: bold;">{{ __('Dosen') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::is('dosen/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/dosen/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt" style="color: #ff4757;"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading" style="font-size: 0.9rem; font-weight: bold;">
        {{ __('Interface') }}
    </div>

    <!-- Nav Item - Attendance -->
    <li class="nav-item {{ Request::is('dosen/attendance') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/dosen/attendance') }}">
            <i class="fas fa-fw fa-calendar" style="color: #ffa502;"></i>
            <span>{{ __('Attendance') }}</span>
        </a>
    </li>

    <!-- Nav Item - Courses -->
    <li class="nav-item {{ Request::is('dosen/courses') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/dosen/courses') }}">
            <i class="fas fa-fw fa-book" style="color: #1e90ff;"></i>
            <span>
                {{ __('Jadwal Perkuliahan') }}
                @if(isset($countTodayBookings) && $countTodayBookings > 0)
                    <span class="badge badge-pill badge-danger ml-1">{{ $countTodayBookings }}</span>
                @endif
            </span>
        </a>
    </li>

     <!-- Nav Item - Profile -->
     <li class="nav-item {{ Request::is('dosen/profile') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dosen.profile.show') }}">
            <i class="fas fa-fw fa-user" style="color: #2ed573;"></i>
            <span>{{ __('Profile') }}</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
