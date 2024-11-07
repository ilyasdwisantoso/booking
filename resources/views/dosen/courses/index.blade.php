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
                            <th width="10"></th>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td></td>
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
                                <td id="status-{{ $booking->id }}">{{ $booking->status }}</td>
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
    function updateClassStatus() {
        fetch('{{ route("dosen.update-class-status") }}')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                updateStatusDisplay();
            })
            .catch(error => console.error('Error:', error));
    }

    function updateStatusDisplay() {
        fetch('{{ route("dosen.get-class-statuses") }}')
            .then(response => response.json())
            .then(data => {
                data.bookings.forEach(booking => {
                    const statusElement = document.getElementById(`status-${booking.id}`);
                    if (statusElement) {
                        statusElement.innerText = booking.status;
                    }
                });
            })
            .catch(error => console.error('Error:', error));
    }

    setInterval(updateClassStatus, 10000);

    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        let deleteButtonTrans = 'Delete selected';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.booking.mass_destroy') }}",
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
        $('.datatable-booking:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endpush
