@extends('layouts.admin')
@section('active-page', 'Jadwal Kelas')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
   

    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('Jadwal Kelas') }}
                </h6>
                <div class="ml-auto">
                    
                    <a href="{{ route('admin.booking.create') }}" class="btn btn-primary">
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
                                <th>Status Kelas</th> <!-- Tambahkan kolom ini -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr data-entry-id="{{ $booking->id }}">
                                <td></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>
                                    @foreach($booking->mahasiswas as $mahasiswa)
                                    <span class="badge badge-info">{{ $loop->iteration }}.{{ $mahasiswa->Nama }} {{ $mahasiswa->NIM }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $booking->prodi->nama_prodi }}</td>
                                <td>{{ $booking->matakuliah->Nama_MK }}</td>
                                <td>{{ $booking->dosen->nama_dosen }}</td>
                                <td>{{ $booking->ruangan->no_ruangan }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->day_of_week_text }}</td>
                                <td>{{ $booking->code_token }}</td>        
                                <td id="status-{{ $booking->id }}">{{ $booking->status }}</td> <!-- Tambahkan kolom ini -->
                                <td>
                                    <a href="{{route('admin.booking.show', $booking->id)}}" class="btn btn-success">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.booking.edit', $booking->id) }}" class="btn btn-info">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form onclick="return confirm('are you sure ? ')" class="d-inline" action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center">{{ __('Data Empty') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <!-- Content Row -->

</div>
@endsection

@push('script-alt')
<script>
    // Fungsi untuk memperbarui status kelas
    function updateClassStatus() {
        fetch('/admin/update-class-status')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                updateStatusDisplay();
            })
            .catch(error => console.error('Error:', error));
    }

    // Fungsi untuk memperbarui tampilan status kelas di halaman
    function updateStatusDisplay() {
        fetch('/admin/get-class-statuses')
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

    // Panggil updateClassStatus setiap menit (10000ms)
    setInterval(updateClassStatus, 10000);

    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        let deleteButtonTrans = 'delete selected';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.booking.mass_destroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id');
                });
                if (ids.length === 0) {
                    alert('zero selected');
                    return;
                }
                if (confirm('are you sure ?')) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }})
                        .done(function () { location.reload() });
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
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    });
</script>
@endpush
