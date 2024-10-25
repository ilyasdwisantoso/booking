@extends('layouts.admin')
@section('active-page', 'Matakuliah')
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
            <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Matakuliah') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.matakuliah.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.matakuliah.update',$matakuliah->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="Kode_MK">{{ __('Kode MK') }}</label>
                        <input type="text" class="form-control" id="Kode_MK" placeholder="{{ __('Kode MK') }}" name="Kode_MK" value="{{ $matakuliah->Kode_MK }}" />
                    </div>
                    
                    <div class="form-group">
                        <label for="Nama_MK">{{ __('Nama MK') }}</label>
                        <input type="text" class="form-control" id="Nama_MK" placeholder="{{ __('Nama_MK') }}" name="Nama_MK" value="{{ $matakuliah->Nama_MK }}" />
                    </div>

                    <div class="form-group">
                        <label for="sks">{{ __('SKS') }}</label>
                        <input type="text" class="form-control" id="sks" placeholder="{{ __('SKS') }}" name="sks" value="{{ $matakuliah->sks }}" />
                    </div>
                
                    
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection