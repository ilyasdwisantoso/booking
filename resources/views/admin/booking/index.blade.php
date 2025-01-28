@extends('layouts.admin')
@section('active-page', 'Jadwal Kelas')
@section('content')
<div class="container-fluid">

    <!-- Jadwal Kelas Hari Ini -->
    <div class="card">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('Jadwal Kelas Hari Ini') }}
            </h6>
            <a href="{{ route('admin.booking.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Kelas
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-today-booking" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kelas/(Nama Kelas)</th>
                            <th>Prodi</th>
                            <th>Matakuliah</th>
                            <th>Nama Mahasiswa</th>
                            <th>Dosen</th>
                            <th>Ruangan</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Kode Token Kelas</th>
                            <th>Status Ruangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayBookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ $booking->prodi->nama_prodi }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK }}</td>
                                <td>
                                    @foreach($booking->mahasiswas->sortBy('NIM') as $mahasiswa)
                                        <span class="badge badge-info">
                                            {{ $loop->iteration }}. {{ $mahasiswa->NIM }} - {{ $mahasiswa->Nama }}
                                        </span><br>
                                    @endforeach
                                </td>  
                                <td>{{ $booking->dosen->nama_dosen }}</td>
                                <td>{{ $booking->ruangan->no_ruangan }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->code_token }}</td>
                                <td id="room-status-{{ $booking->id }}">
                                    <span class="{{ $booking->room_status === 'open' ? 'text-success' : 'text-danger' }}">
                                        {{ $booking->room_status === 'open' ? 'Ruangan Dibuka' : 'Ruangan Ditutup' }}
                                    </span>
                                </td>
                                <td>{{ $booking->day_of_week_text }}</td>
                                <td>
                                    <a href="{{route('admin.booking.show', $booking->id)}}" class="btn btn-success btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.booking.edit', $booking->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form onclick="return confirm('Are you sure?')" class="d-inline" action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
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

    <!-- Jadwal Kelas Mendatang -->
    <div class="card">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('Jadwal Kelas Mendatang') }}
            </h6>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-upcoming-booking" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kelas/(Nama Kelas)</th>
                            <th>Prodi</th>
                            <th>Matakuliah</th>
                            <th>Nama Mahasiswa</th>
                            <th>Dosen</th>
                            <th>Ruangan</th>
                            <th>Waktu</th>
                            <th>Hari</th>
                            <th>Kode Token Kelas</th>
                            <th>Status Ruangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingBookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ $booking->prodi->nama_prodi }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK }}</td>
                                <td>
                                    @foreach($booking->mahasiswas->sortBy('NIM') as $mahasiswa)
                                        <span class="badge badge-info">
                                            {{ $loop->iteration }}. {{ $mahasiswa->NIM }} - {{ $mahasiswa->Nama }}
                                        </span><br>
                                    @endforeach
                                </td>  
                                <td>{{ $booking->dosen->nama_dosen }}</td>
                                <td>{{ $booking->ruangan->no_ruangan }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->day_of_week_text }}</td>
                                <td>{{ $booking->code_token }}</td>
                                <td id="room-status-{{ $booking->id }}">
                                    <span class="{{ $booking->room_status === 'open' ? 'text-success' : 'text-danger' }}">
                                        {{ $booking->room_status === 'open' ? 'Ruangan Dibuka' : 'Ruangan Ditutup' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{route('admin.booking.show', $booking->id)}}" class="btn btn-success btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.booking.edit', $booking->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form onclick="return confirm('Are you sure?')" class="d-inline" action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
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

@push('script-alt')
<script>
    $(function () {
        $('.datatable-today-booking, .datatable-upcoming-booking').DataTable({
            order: [[1, 'asc']],
            pageLength: 50,
        });
    });
</script>
@endpush
