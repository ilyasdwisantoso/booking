@extends('layouts.mahasiswa')
@section('active-page', 'Courses Schedule')
@section('content')

<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Jadwal Kuliah</h1>

    <!-- Daftar Kuliah Hari Ini -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kuliah Hari Ini</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-today-booking" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kelas</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Program Studi</th>
                            <th>Ruangan</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Dosen</th>
                            <th>Status Kelas</th>
                            <th>Status Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayBookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK ?? 'Tidak Ada Data' }}</td>
                                <td>{{ $booking->prodi->nama_prodi ?? 'Tidak Ada Data' }}</td>
                                <td>{{ $booking->ruangan->no_ruangan ?? 'Tidak Ada Data' }}</td>
                                <td>{{ $booking->day_of_week_text }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->dosen->nama_dosen ?? 'Tidak Ada Data' }}</td>
                                <td>
                                    <span class="badge {{ $booking->status === 'kelas belum dimulai' ? 'badge-danger' : 'badge-success' }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $booking->room_status === 'open' ? 'text-success' : 'text-danger' }}">
                                        {{ $booking->room_status === 'open' ? 'Ruangan Dibuka' : 'Ruangan Ditutup' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak Ada Kuliah Hari Ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Daftar Jadwal Kuliah Mendatang -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Jadwal Kuliah Mendatang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-upcoming-booking" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kelas</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Program Studi</th>
                            <th>Ruangan</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Dosen</th>
                            <th>Status Kelas</th>
                            <th>Status Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingBookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK ?? 'Tidak Ada Data' }}</td>
                                <td>{{ $booking->prodi->nama_prodi ?? 'Tidak Ada Data' }}</td>
                                <td>{{ $booking->ruangan->no_ruangan ?? 'Tidak Ada Data' }}</td>
                                <td>{{ $booking->day_of_week_text }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->dosen->nama_dosen ?? 'Tidak Ada Data' }}</td>
                                <td>
                                    <span class="badge {{ $booking->status === 'kelas belum dimulai' ? 'badge-danger' : 'badge-success' }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $booking->room_status === 'open' ? 'text-success' : 'text-danger' }}">
                                        {{ $booking->room_status === 'open' ? 'Ruangan Dibuka' : 'Ruangan Ditutup' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak Ada Jadwal Kuliah Mendatang</td>
                            </tr>
                        @endforelse
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
        // Inisialisasi DataTables
        $('.datatable-today-booking').DataTable({
            order: [[1, 'asc']],
            pageLength: 50,
        });

        $('.datatable-upcoming-booking').DataTable({
            order: [[1, 'asc']],
            pageLength: 50,
        });
    });
</script>
@endpush
