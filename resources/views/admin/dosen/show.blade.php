@extends('layouts.admin')
@section('active-page', 'Data Dosen')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
   

    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('View Data Dosen') }}
                </h6>
                <div class="ml-auto">
                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-primary">
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
                            <th>Nama Dosen</th>
                            <td>{{ $dosen->nama_dosen}}</td>
                        </tr>
                        <tr>
                            <th>NIP</th>
                            <td>{{ $dosen->nip }}</td>
                        </tr>
                        <tr>
                            <th>No Telepon</th>
                            <td>{{ $dosen->no_tlp }}</td>
                        </tr>
                        <tr>
                            <th>Qr Code Dosen</th>
                            <td><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge('/storage/app/unj.png')
                                ->errorCorrection('L')->size(200)->generate($dosen->qr_code)) !!} "></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <!-- Content Row -->

</div>
@endsection