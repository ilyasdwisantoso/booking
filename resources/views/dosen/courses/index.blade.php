@extends('layouts.dosen')
@section('active-page', 'Courses Schedule')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Jadwal Kuliah</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Daftar Jadwal Kuliah ') }}</h6>
            <div class="ml-auto">
            
                <a href="{{ route('dosen.courses.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">{{ __('New Kelas') }}</span>
                </a>
                
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-booking" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kelas/(Nama Kelas)</th>
                            <th>Daftar Mahasiswa</th>
                            <th>Prodi</th>
                            <th>Matakuliah</th>
                            <th>Dosen</th>
                            <th>Ruangan</th>
                            <th>Waktu</th>
                            <th>Hari</th>
                            <th>Token</th>
                            <th>Status Kelas</th>
                            <th>Status Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>
                                    @foreach($booking->mahasiswas as $mahasiswa)
                                        <p><span class="badge badge-info">{{ $loop->iteration }}.{{ $mahasiswa->Nama }} {{ $mahasiswa->NIM }}</span></p>
                                    @endforeach
                                </td>
                                <td>{{ $booking->prodi->nama_prodi }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK }}</td>
                                <td>{{ $booking->dosen->nama_dosen }}</td>
                                <td>{{ $booking->ruangan->no_ruangan }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->day_of_week_text }}</td>
                                <td>{{ $booking->code_token }}</td>
                                <td id="status-{{ $booking->id }}">
                                    <span class="badge {{ $booking->status === 'kelas belum dimulai' ? 'badge-danger' : 'badge-success' }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td id="room-status-{{ $booking->id }}">
                                    <span class="{{ $booking->room_status == 'open' ? 'text-success' : 'text-danger' }}">
                                        {{ $booking->room_status == 'open' ? 'Ruangan Dibuka' : 'Ruangan Ditutup' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dosen.courses.edit', $booking->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('dosen.courses.destroy', $booking->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
