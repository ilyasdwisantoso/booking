@extends('layouts.admin')
@section('active-page', 'Jadwal Kelas')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
   

    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('View Booking Kelas') }}
                </h6>
                <div class="ml-auto">
                    <a href="{{ route('admin.booking.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                        <tr>
                            <th>Kode Kelas(Nama Kelas)</th>
                            <td>{{ $booking->Kode_Kelas }}</td>
                        </tr>
                        <tr>
                            <th>Mahasiswa</th>
                            <td>@foreach($booking->mahasiswas as $mahasiswa)
                                {{ $mahasiswa->Nama }}<br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Prodi</th>
                            <td>{{ $booking->prodi->nama_prodi }}</td>
                        </tr>
                        <tr>
                            <th>Matakuliah</th>
                            <td>{{ $booking->matakuliah->Nama_MK }}</td>
                        </tr>
                        <tr>
                            <th>Dosen</th>
                            <td>{{ $booking->dosen->nama_dosen }}</td>
                        </tr>
                        <tr>
                            <th>Hari</th>
                            <td>{{ $booking->day_of_week_text }}</td>
                        </tr>
                        <tr>
                            <th>Waktu</th>
                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                        </tr>
                        <tr>
                            <th>Code Token</th>
                            <td>{{ $booking->code_token }}</td>
                        </tr>
                    </table>
                </div>
            </div>
           
        </div>
    <!-- Content Row -->

</div>
@endsection