<ul class="navbar-nav bg-gradient-midnight-blue sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/mahasiswa/dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img') }}/unj_sikiar.png" alt="Logo" style="width: 60px; height: 60px;">
        </div>
        <div class="sidebar-brand-text mx-3" style="font-size: 1.75rem; font-weight: bold;">{{ __('SIKIAR') }}</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->is('mahasiswa/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/mahasiswa/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt" style="color: #ff4757;"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseAttendance" aria-expanded="true" aria-controls="collapseAttendance">
            <i class="fas fa-calendar"></i> <!-- Ikon tambahan untuk Presensi -->
            <span>{{ __('Presensi') }}</span>
        </a>
        <div id="collapseAttendance" class="collapse" aria-labelledby="headingAttendance" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('mahasiswa/attendance') ? 'active' : '' }}" href="{{ url('/mahasiswa/attendance') }}">
                    <i class="fas fa-check-circle mr-2" style="color: #ffa502;"></i> {{ __('Kehadiran') }}
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseCourses" aria-expanded="true" aria-controls="collapseCourses">
            <i class="fas fa-book"></i> <!-- Ikon tambahan untuk Jadwal Kuliah -->
            <span>{{ __('Jadwal Kuliah') }}</span>
        </a>
        <div id="collapseCourses" class="collapse" aria-labelledby="headingCourses" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('mahasiswa/courses') ? 'active' : '' }}" href="{{ url('/mahasiswa/courses') }}">
                    <i class="fas fa-list-alt mr-2" style="color: #1e90ff;"></i> {{ __('Daftar Mata Kuliah') }}
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseProfile" aria-expanded="true" aria-controls="collapseProfile">
            <i class="fas fa-user"></i> <!-- Ikon tambahan untuk Profil -->
            <span>{{ __('Profil') }}</span>
        </a>
        <div id="collapseProfile" class="collapse" aria-labelledby="headingProfile" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('mahasiswa/profile') ? 'active' : '' }}" href="{{ url('/mahasiswa/profile') }}">
                    <i class="fas fa-id-card mr-2" style="color: #2ed573;"></i> {{ __('Data Diri') }}
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
