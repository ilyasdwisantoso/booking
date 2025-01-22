@extends('layouts.dosen')
@section('active-page', 'Dashboard')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 text-center">Selamat Datang, <strong>{{ auth()->user()->name }}</strong>!</h1>

    <!-- Info Cards -->
    <div class="row justify-content-center">
        <!-- Total Jadwal Kelas Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jadwal Kelas Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayClassCount }}</div>
                        </div>
                        <div>
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Mahasiswa di Semua Kelas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Mahasiswa Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                        </div>
                        <div>
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Kehadiran -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Kehadiran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $averageAttendancePercentage }}%</div>
                        </div>
                        <div>
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel -->
        <div id="dosenCarousel" class="carousel slide mb-4 shadow" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#dosenCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#dosenCarousel" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100 rounded" src="{{ asset('img/fakultasteknik.jpg') }}" alt="Fakultas Teknik">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 rounded" src="{{ asset('img/plaza.jpg') }}" alt="Plaza UNJ">
                </div>
            </div>
            <a class="carousel-control-prev" href="#dosenCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#dosenCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <!-- Data Jumlah Mahasiswa per Kelas -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Jumlah Mahasiswa per Kelas</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode Kelas</th>
                        <th>Jumlah Mahasiswa</th>
                        <th>Jumlah Pertemuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classesData as $class)
                    <tr>
                        <td>{{ $class['kode_kelas'] }}</td>
                        <td>{{ $class['student_count'] }}</td>
                        <td>{{ $class['meeting_count'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kehadiran Hari Ini -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Presentasi Kehadiran Mahasiswa Hari Ini</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode Kelas</th>
                        <th>Hadir</th>
                        <th>Total Mahasiswa</th>
                        <th>Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendanceTodayData as $attendance)
                    <tr>
                        <td>{{ $attendance['kode_kelas'] }}</td>
                        <td>{{ $attendance['present_today'] }}</td>
                        <td>{{ $attendance['total_students'] }}</td>
                        <td>{{ $attendance['attendance_percentage'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Informasi Dosen -->
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Dosen</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Nama Dosen</th>
                    <td>{{ $dosen->nama_dosen }}</td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td>{{ $dosen->nip }}</td>
                </tr>
                <tr>
                    <th>No Telepon</th>
                    <td>{{ $dosen->no_tlp }}</td>
                </tr>
                <tr>
                    <th>Qr Code Dosen</th>
                    <td class="text-center">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge('/storage/app/unsikiar.png')->errorCorrection('L')->size(200)->generate($dosen->qr_code)) !!}" alt="QR Code">
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>
@endsection
