@extends('layouts.admin')
@section('active-page', 'Attendance')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
   

    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('View Daftar Presensi Mahasiswa Bedasarkan Kelas') }}
                </h6>
                <div class="ml-auto">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                        <tr>
                            <th>Kode Kelas(Nama Kelas)</th>
                            <td>{{ $attendance->bookings->Kode_Kelas }}</td>
                        </tr>
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <td>{{ $attendance->mahasiswas->Nama }}</td>
                        </tr>
                        <tr><th>NIM</th>
                            <th>{{ $attendance->mahasiswas->NIM }}</th>
                        </tr>
                        <tr><th>Dosen</th>
                            <th>{{ $attendance->bookings->dosen->nama_dosen }}</th>
                        </tr>
                        <tr><th>Matakuliah</th>
                            <th>{{ $attendance->bookings->matakuliah->Nama_MK }}</th>
                        </tr>
                        <tr><th>Ruangan</th>
                            <th>{{ $attendance->bookings->ruangan->no_ruangan }}</th>
                        </tr>
                        <tr><th>Tanggal</th>
                            <th>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('Y-m-d') }}</th>
                        </tr>
                        <tr><th>Hari</th>
                            <th>{{ $attendance->bookings->day_of_week_text }}</th>
                        </tr>
                        <tr><th>Waktu Absen</th>
                            <th>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('H:i:s') }}</th>
                        </tr>
                    </table>
                </div>
            </div>
           
        </div>
    <!-- Content Row -->

</div>
@endsection