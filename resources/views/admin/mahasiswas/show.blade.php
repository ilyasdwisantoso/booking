@extends('layouts.admin')
@section('active-page', 'Data Mahasiswa')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
   

    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('view Data Mahasiswa') }}
                </h6>
                <div class="ml-auto">
                    <a href="{{ route('admin.mahasiswas.index') }}" class="btn btn-primary">
                        <span class="icon text-white-50">
                            
                        </span>
                        <span class="text">{{ __('Kembali') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $mahasiswas->Nama}}</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>{{ $mahasiswas->NIM }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ $mahasiswas->tgl_lahir }}</td>
                        </tr>
                        <tr>
                            <th>Foto Database</th>
                            <td>@if ($mahasiswas->photo)
                                <img  src="{{ url('photo').'/'.$mahasiswas->photo }}"  alt="Database Photo" style="max-width:250px;max-height:250px">
                                @endif</td>
                        </tr>
                        <tr>
                            <th>QR Presensi</th>
                            <td><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge('/storage/app/unj.png')
                                ->errorCorrection('L')->size(200)->generate($mahasiswas->qr_code)) !!} "></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <!-- Content Row -->

</div>
@endsection