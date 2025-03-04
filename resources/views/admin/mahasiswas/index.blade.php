@extends('layouts.admin')
@section('active-page', 'Data Mahasiswa')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
   

    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('Data Mahasiswa') }}
                </h6>
                <div class="ml-auto">
                    @can('mahasiswa_create')
                    <a href="{{ route('admin.mahasiswas.create') }}" class="btn btn-primary">
                        <span class="icon text-white-50">
                            <i class="fa fa-plus"></i>
                        </span>
                        <span class="text">{{ __('New Mahasiswa') }}</span>
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-customer" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Tgl Lahir</th>
                                <th>Photo</th>
                                <th>Qr Mahasiswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $m)
                            <tr  data-entry-id="{{ $m->NIM }}">
                                <td>

                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$m->NIM}}</td>
                                <td>{{$m->Nama}}</td>
                                <td>{{$m->tgl_lahir}}</td>
                                <td>
                                    @if ($m->photo)
                                    <img  src="{{ url('photo').'/'.$m->photo }}"  alt="Database Photo" style="max-width:250px;max-height:250px">
                                    @endif
                                </td>
                                <td><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge('/storage/app/unj_sikiar.png')
                                    ->errorCorrection('L')->size(200)->generate($m->qr_code)) !!} "></td>
                                <td>
                                    <a href="{{route('admin.mahasiswas.show', $m->NIM)}}" class="btn btn-success">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.mahasiswas.edit', $m->NIM) }}" class="btn btn-info">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form onclick="return confirm('are you sure ? ')" class="d-inline" action="{{ route('admin.mahasiswas.destroy', $m->NIM) }}" method="POST">
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
                                <td colspan="9" class="text-center">{{ __('Data Empty') }}</td>
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let deleteButtonTrans = 'delete selected'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.mahasiswas.mass_destroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });
      if (ids.length === 0) {
        alert('zero selected')
        return
      }
      if (confirm('are you sure ?')) {
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'asc' ]],
    pageLength: 50,
  });
  $('.datatable-customer:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})
</script>
@endpush