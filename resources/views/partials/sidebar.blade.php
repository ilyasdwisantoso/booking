<ul class="navbar-nav bg-gradient-midnight-blue sidebar sidebar-dark accordion" id="accordionSidebar">


            
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('backend/img/logo-unj.png') }}" alt="Logo" style="width: 40px; height: 40px;">
                </div>
                <div class="sidebar-brand-text mx-3" style="font-size: 1.25rem; font-weight: bold;">{{ __('SIKIAR') }}</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item {{ request()->is('admin/dashboard') || request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <span>{{ __('User Management') }}</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        
                        <a class="collapse-item {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"> <i class="fa fa-user mr-2"></i> {{ __('Users') }}</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseRFID" aria-expanded="true" aria-controls="collapseTwo">
                    <span>{{ __('Presensi Mahasiswa') }}</span>
                </a>
                <div id="collapseRFID" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->is('admin/attendance') || request()->is('admin/attendance/*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}"> <i class="fa fa-briefcase mr-2"></i> {{ __('Attendance') }}</a>
                        
                        
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseData" aria-expanded="true" aria-controls="collapseRFID">
                    <span>{{ __('Data Management') }}</span>
                </a>
                <div id="collapseData" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->is('admin/mahasiswas') || request()->is('admin/mahasiswas/*') ? 'active' : '' }}" href="{{ route('admin.mahasiswas.index') }}"> <i class="fa fa-briefcase mr-2"></i> {{ __('Data Mahasiswa') }}</a>
                        <a class="collapse-item {{ request()->is('admin/dosen') || request()->is('admin/dosen/*') ? 'active' : '' }}" href="{{ route('admin.dosen.index') }}"> <i class="fa fa-user mr-2"></i> {{ __('Data Dosen') }}</a>
                        
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseAkademik" aria-expanded="true" aria-controls="collapseData">
                    <span>{{ __('Akademik Management') }}</span>
                </a>
                <div id="collapseAkademik" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->is('admin/prodi') || request()->is('admin/prodi/*') ? 'active' : '' }}" href="{{ route('admin.prodi.index') }}"> <i class="fa fa-briefcase mr-2"></i> {{ __('Prodi') }}</a>
                        <a class="collapse-item {{ request()->is('admin/matakuliah') || request()->is('admin/matakuliah/*') ? 'active' : '' }}" href="{{ route('admin.matakuliah.index') }}"> <i class="fa fa-user mr-2"></i> {{ __('Mata Kuliah') }}</a>
                        <a class="collapse-item {{ request()->is('admin/ruangan') || request()->is('admin/ruangan/*') ? 'active' : '' }}" href="{{ route('admin.ruangan.index') }}"><i class="fa fa-briefcase mr-2"></i> {{ __('Ruangan') }}</a>
                    </div>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseBooking" aria-expanded="true" aria-controls="collapseAkademik">
                    <span>{{ __('Jadwal Kelas') }}</span>
                </a>
                <div id="collapseBooking" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        
                        <a class="collapse-item {{ request()->is('admin/booking') || request()->is('admin/booking/*') ? 'active' : '' }}" href="{{ route('admin.booking.index') }}"><i class="fa fa-briefcase mr-2"></i> {{ __('Kelas') }}</a>
                       
                        
                    </div>
                </div>
            


        </ul>