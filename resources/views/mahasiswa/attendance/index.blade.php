@extends('layouts.mahasiswa')
@section('active-page', 'Attendance')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Presensi Mahasiswa</h1>

    <!-- Waktu Real-Time -->
    <div id="real-time-clock" class="mb-3"></div>

    <!-- Data Kehadiran -->
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

    <!-- Tabel Presensi Mahasiswa -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Presensi Mahasiswa') }}</h6>
        </div>
        @if(empty($attendances))
            <div class="card-body">
                <p>Tidak ada data presensi yang ditemukan.</p>
            </div>
        @else
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-attendance" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Kelas</th>
                                <th>Dosen</th>
                                <th>Tanggal</th>
                                <th>Day</th> <!-- Tambahkan kolom untuk menampilkan hari -->
                                <th>Time</th>
                                <th>Foto Database</th>
                                <th>Foto Real-Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->mahasiswas->Nama }}</td>
                                    <td>{{ $attendance->mahasiswas->NIM }}</td>
                                    <td>{{ $attendance->booking->Kode_Kelas }}</td>
                                    <td>{{ $attendance->booking->dosen->nama_dosen }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('Y-m-d') }}</td>
                                    <td>{{ $attendance->booking->day_of_week_text }}</td> <!-- Menampilkan hari -->
                                    <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('H:i:s') }}</td>
                                    <td>
                                        @if ($attendance->mahasiswas->photo)
                                            <img src="{{ url('photo').'/'.$attendance->mahasiswas->photo }}" alt="Database Photo" style="max-width:250px;max-height:250px">
                                        @endif
                                    </td>
                                    <td></td> <!-- Placeholder untuk foto real-time -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
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
        let deleteButtonTrans = 'Delete selected';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.attendance.mass_destroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id');
                });
                if (ids.length === 0) {
                    alert('No selected');
                    return;
                }
                if (confirm('Are you sure?')) {
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
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endpush
