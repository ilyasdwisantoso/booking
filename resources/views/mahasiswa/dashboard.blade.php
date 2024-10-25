@extends('layouts.mahasiswa')
@section('active-page', 'Dashboard')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Selamat Datang, {{ auth()->user()->name }}</h1>
    <div class="row">

        
        <!-- User Card Example -->
        <div class="col-xl-3 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                               User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i> <!-- Ganti dengan icon users -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Card Example -->
        <div class="col-xl-3 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Mahasiswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mahasiswaCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i> <!-- Ganti dengan icon user graduate -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dosen Card Example -->
        <div class="col-xl-3 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dosen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dosenCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i> <!-- Ganti dengan icon chalkboard teacher -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelas Card Example -->
        <div class="col-xl-3 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                               Kelas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kelasCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i> <!-- Ganti dengan icon book open -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="{{ asset('img/fakultasteknik.jpg') }}" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="{{ asset('img/plaza.jpg') }}" alt="Second slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <!-- Page Heading -->
   
    <h1 class="h3 mb-2 text-gray-800">Data Mahasiswa</h1>
    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                {{ auth()->user()->name }}
                </h6>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $mahasiswa->Nama}}</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>{{ $mahasiswa->NIM }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ $mahasiswa->tgl_lahir }}</td>
                        </tr>
                        <tr>
                            <th>Foto Database</th>
                            <td>@if ($mahasiswa->photo)
                                <img  src="{{ url('photo').'/'.$mahasiswa->photo }}"  alt="Database Photo" style="max-width:250px;max-height:250px">
                                @endif</td>
                        </tr>
                        <tr>
                            <th>QR Presensi</th>
                            <td><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge('/storage/app/unj.png')
                                ->errorCorrection('L')->size(200)->generate($mahasiswa->qr_code)) !!} "></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <!-- Content Row -->

</div>
@endsection