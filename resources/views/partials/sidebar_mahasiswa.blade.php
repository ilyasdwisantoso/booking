<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/mahasiswa/dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Mahasiswa</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if(Request::is('mahasiswa/dashboard')) active @endif">
        <a class="nav-link" href="{{ url('/mahasiswa/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i> <!-- Ikon tambahan untuk Dashboard -->
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Attendance -->
    <li class="nav-item @if(Request::is('mahasiswa/attendance')) active @endif">
        <a class="nav-link" href="{{ url('/mahasiswa/attendance') }}">
            <i class="fas fa-fw fa-calendar"></i> <!-- Ikon tambahan untuk Attendance -->
            <span>Attendance</span>
        </a>
    </li>

    <!-- Nav Item - Courses -->
    <li class="nav-item @if(Request::is('mahasiswa/courses')) active @endif">
        <a class="nav-link" href="{{ url('/mahasiswa/courses') }}">
            <i class="fas fa-fw fa-book"></i> <!-- Ikon tambahan untuk Course Schedule -->
            <span>Course Schedule</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
