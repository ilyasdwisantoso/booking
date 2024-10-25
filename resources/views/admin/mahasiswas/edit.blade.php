@extends('layouts.admin')
@section('active-page', 'Data Mahasiswa')
@section('content')
<div class="container-fluid">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<!-- Content Row -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex">
            <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Mahasiswa') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.mahasiswas.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mahasiswas.update',$mahasiswa->NIM) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="NIM">{{ __('NIM') }}</label>
                        <input type="text" class="form-control" id="NIM" placeholder="{{ __('NIM') }}" name="NIM" value="{{ $mahasiswa->NIM }}" />
                    </div>

                    <div class="form-group">
                        <label for="Nama">{{ __('Nama Lengkap') }}</label>
                        <input type="text" class="form-control" id="Nama" placeholder="{{ __('Nama') }}" name="Nama" value="{{ $mahasiswa->Nama }}" />
                    </div>
            
                    <div class="form-group">
                        <label for="tgl_lahir">{{ __('Tanggal Lahir') }}</label>
                        <input type="date" class="form-control" id="tgl_lahir" placeholder="{{ __('tanggal lahir') }}" name="tgl_lahir" value="{{ $mahasiswa->tgl_lahir }}" />
                    </div>

                    <div class="form-group">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection