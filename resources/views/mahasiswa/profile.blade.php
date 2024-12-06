@extends('layouts.mahasiswa')

@section('active-page', 'Profil')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Profil Mahasiswa</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Informasi Mahasiswa -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Mahasiswa</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h5>Foto Mahasiswa</h5>
                    @if ($mahasiswa->photo)
                        <img src="{{ url('photo').'/'.$mahasiswa->photo }}" 
                             alt="Foto Mahasiswa" class="img-fluid rounded" 
                             style="max-width: 250px; max-height: 250px;">
                    @else
                        <p class="text-muted">Foto belum tersedia.</p>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th>NIM</th>
                            <td>{{ $mahasiswa->NIM }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $mahasiswa->Nama }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ \Carbon\Carbon::parse($mahasiswa->tgl_lahir)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>QR Code</th>
                            <td>
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->merge('/storage/app/unj.png')
                                    ->errorCorrection('L')->size(200)->generate($mahasiswa->qr_code)) !!} ">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Ubah Password -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('mahasiswa.profile.changePassword') }}">
                @csrf
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Ubah Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
