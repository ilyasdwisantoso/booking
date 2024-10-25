@extends('layouts.admin')
@section('active-page', 'Data Dosen')
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
            <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Dosen') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="nama_dosen">{{ __('Nama Lengkap Dosen') }}</label>
                        <input type="text" class="form-control" id="nama_dosen" placeholder="{{ __('Masukkan Nama Dosen') }}" name="nama_dosen" value="{{ $dosen->nama_dosen }}" />
                    </div>
                    
                    <div class="form-group">
                        <label for="nip">{{ __('NIP') }}</label>
                        <input type="text" class="form-control" id="nip" placeholder="{{ __('Masukkan NIP') }}" name="nip" value="{{ $dosen->nip }}" />
                    </div>
                    
                    <div class="form-group">
                        <label for="no_tlp">{{ __('Nomor Telepon') }}</label>
                        <input type="text" class="form-control" id="no_tlp" placeholder="{{ __('Masukkan No Telepon') }}" name="no_tlp" value="{{ $dosen->no_tlp }}"/>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection