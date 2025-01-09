@extends('layouts.dosen')
@section('active-page', 'Attendance')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Detail Kehadiran Kelas: {{ $booking->Kode_Kelas }}</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kehadiran Mahasiswa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-attendance-records" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Kode Kelas</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Pertemuan Ke</th>
                            <th>Foto Profile Mahasiswa</th>
                            <th>Foto Camera</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $attendance->mahasiswas->Nama }}</td>
                                <td>{{ $attendance->mahasiswas->NIM }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->translatedFormat('l') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('H:i:s') }}</td>
                                <td>{{ $attendance->pertemuan_ke }}</td>
                                <td>
                                    @if ($attendance->mahasiswas->photo)
                                        <img src="{{ url('photo').'/'.$attendance->mahasiswas->photo }}" 
                                             alt="Database Photo" 
                                             style="max-width:250px; max-height:250px; object-fit:cover;">
                                    @else
                                        <span>Tidak Ada Foto</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($attendance->photo)
                                        <img src="{{ url('photo').'/'.$attendance->photo }}" 
                                             alt="Real-Time Photo" 
                                             style="max-width:250px; max-height:250px; object-fit:cover;">
                                    @else
                                        <span>Tidak Ada Foto</span>
                                    @endif
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
        // Initialize DataTables for Detail Kehadiran
        $('.datatable-attendance-records:not(.ajaxTable)').DataTable({
            order: [[1, 'asc']],
            pageLength: 50,
        });

        // Adjust columns on tab switch
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endpush
