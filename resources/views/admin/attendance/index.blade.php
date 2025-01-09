@extends('layouts.admin')
@section('active-page', 'Attendance')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Presensi Mahasiswa</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div id="real-time-clock" class="mb-3"></div>
    <!-- Bagian Data Kehadiran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Data Kehadiran') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-attendance" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kode Kelas</th>
                            <th>Dosen</th>
                            <th>Jumlah Kehadiran</th>
                            <th>Persentase Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendanceData as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data['student']->Nama }}</td>
                                <td>{{ $data['booking']->Kode_Kelas }}</td>
                                <td>{{ $data['booking']->dosen->nama_dosen }}</td>
                                <td>{{ $data['attendanceCount'] }}</td>
                                <td>{{ number_format($data['attendancePercentage'], 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bagian Catatan Kehadiran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Catatan Kehadiran') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable-attendance-records" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Kode Kelas</th>
                            <th>Dosen</th>
                            <th>Matakuliah</th>
                            <th>Pertemuan Ke</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Foto Database</th>
                            <th>Foto Real-Time</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-alt')
<script>
    // Fungsi untuk mengambil waktu real-time dan memperbarui elemen HTML
    function updateClock() {
        const now = new Date();
        const formattedTime = now.toLocaleTimeString('en-US', { hour12: false });
        document.getElementById('real-time-clock').innerHTML = `<p>Waktu Real-Time: ${formattedTime}</p>`;
    }

    // Panggil updateClock setiap detik (1000ms)
    setInterval(updateClock, 1000);

    // Fungsi untuk memuat data Catatan Kehadiran secara real-time
    function fetchRealtimeAttendances() {
        $.ajax({
            url: "{{ url('/api/realtime-attendances') }}",
            method: "GET",
            success: function(data) {
                let tableBody = '';
                data.forEach((attendance, index) => {
                    tableBody += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${attendance.mahasiswas.Nama}</td>
                            <td>${attendance.mahasiswas.NIM}</td>
                            <td>${attendance.booking.Kode_Kelas}</td>
                            <td>${attendance.booking.dosen ? attendance.booking.dosen.nama_dosen : ''}</td>
                            <td>${attendance.booking.matakuliah ? attendance.booking.matakuliah.Nama_MK : ''}</td>
                            <td>${attendance.pertemuan_ke}</td>
                            <td>${new Date(attendance.attended_at).toLocaleDateString('id-ID')}</td>
                            <td>${new Date(attendance.attended_at).toLocaleDateString('id-ID', { weekday: 'long' })}</td>
                            <td>${new Date(attendance.attended_at).toLocaleTimeString('id-ID')}</td>
                            <td>
                                ${attendance.mahasiswas.photo ? 
                                    `<img src="{{ url('photo') }}/${attendance.mahasiswas.photo}" alt="Database Photo" style="max-width:250px;max-height:250px">`
                                    : ''}
                            </td>
                            <td>
                                ${attendance.photo ? 
                                    `<img src="{{ url('photo') }}/${attendance.photo}" alt="Real-Time Photo" style="max-width:250px;max-height:250px">`
                                    : ''}
                            </td>
                            <td>
                                <form onclick="return confirm('Apakah Anda yakin ingin menghapus?')" class="d-inline" action="{{ url('admin/attendance/destroy') }}/${attendance.id}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>`;
                });

                $('.datatable-attendance-records tbody').html(tableBody);

                // Reinitialize DataTables (optional, if you want sorting/pagination)
                $('.datatable-attendance-records').DataTable().destroy();
                $('.datatable-attendance-records').DataTable({
                    order: [[ 0, 'asc' ]],
                    pageLength: 50,
                });
            },
            error: function(xhr) {
                console.error('Error fetching data:', xhr);
            }
        });
    }

    // Panggil fungsi fetchRealtimeAttendances setiap 5 detik
    setInterval(fetchRealtimeAttendances, 5000);

    // Panggilan pertama kali saat halaman di-load
    fetchRealtimeAttendances();

    // Initialize DataTables for the first time
    $(document).ready(function() {
        $('.datatable-attendance').DataTable({
            order: [[ 0, 'asc' ]],
            pageLength: 50,
        });

        // Initialize empty DataTable for attendance records
        $('.datatable-attendance-records').DataTable({
            order: [[ 0, 'asc' ]],
            pageLength: 50,
        });
    });
</script>
@endpush
