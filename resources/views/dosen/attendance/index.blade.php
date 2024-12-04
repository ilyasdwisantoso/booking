@extends('layouts.dosen')
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

     <!-- Waktu Real-Time -->
     <div id="real-time-clock" class="mb-3"></div>
    <!-- Bagian Pemesanan Kelas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Update Jumlah Pertemuan Kelas') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-bookings" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Kelas</th>
                            <th>Mata Kuliah</th>
                            <th>Jumlah Pertemuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK }}</td>
                                <td>
                                    <form action="{{ route('dosen.attendance.updatePertemuan', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="jumlah_pertemuan" value="{{ $booking->jumlah_pertemuan }}" min="0" class="form-control">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('dosen.attendance.attendance_chart', $booking->id) }}" class="btn btn-info btn-sm">Lihat Detail Presentase Kehadiran</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bagian Data Kelas Dosen -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Data Kelas Dosen') }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-classes" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Kelas</th>
                        <th>Mata Kuliah</th>
                        <th>Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $booking->Kode_Kelas }}</td>
                            <td>{{ $booking->matakuliah->Nama_MK }}</td>
                            <td>{{ $booking->prodi->nama_prodi }}</td>
                            <td>
                                <a href="{{ route('dosen.attendance.show', $booking->id) }}" class="btn btn-primary btn-sm">Lihat Kehadiran</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian Data Kehadiran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Data Kehadiran') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-attendance" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kode Kelas</th>
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
                                <td>{{ $data['attendanceCount'] }}</td>
                                <td>{{ number_format($data['attendancePercentage'], 2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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

    // Initialize DataTables
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        let deleteButtonTrans = 'Hapus yang dipilih';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('dosen.attendance.mass_destroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id');
                });
                if (ids.length === 0) {
                    alert('Tidak ada yang dipilih');
                    return;
                }
                if (confirm('Anda yakin?')) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    }).done(function () { location.reload() });
                }
            }
        };
        dtButtons.push(deleteButton);
        $.extend(true, $.fn.dataTable.defaults, {
            order: [[ 1, 'asc' ]],
            pageLength: 50,
        });
        $('.datatable-attendance:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        $('.datatable-attendance-records:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });

    $(function () {
        // Initialize DataTables for Data Kelas Dosen
        $('.datatable-classes:not(.ajaxTable)').DataTable({
            order: [[1, 'asc']],
            pageLength: 50,
        });

        // Adjust columns when tabs are switched
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endpush