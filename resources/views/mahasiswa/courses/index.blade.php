@extends('layouts.mahasiswa')
@section('active-page', 'Courses Schedule')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Jadwal Kuliah</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Jadwal Kuliah</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-course" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>No</th>
                            <th>Kode Kelas</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Program Studi</th>
                            <th>Ruangan</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK }}</td>
                                <td>{{ $booking->prodi->nama_prodi }}</td>
                                <td>{{ $booking->ruangan->no_ruangan }}</td>
                                <td>{{ $booking->day_of_week_text }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->dosen->nama_dosen }}</td>
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
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        $.extend(true, $.fn.dataTable.defaults, {
            order: [[ 1, 'asc' ]],
            pageLength: 50,
        });
        $('.datatable-course:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endpush
