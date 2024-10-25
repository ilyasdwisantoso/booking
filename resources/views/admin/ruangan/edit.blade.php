@extends('layouts.admin')
@section('active-page', 'Ruangan')
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
            <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Ruangan') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.ruangan.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ruangan.update', $ruangan->id ) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="no_ruangan">{{ __('No/Nama Ruangan') }}</label>
                        <input type="text" class="form-control" id="no_ruangan" placeholder="{{ __('Masukkan No/Nama Ruangan') }}" name="no_ruangan" value="{{ $ruangan->no_ruangan}}" />
                    </div>
                    <div class="form-group">
                        <label for="gedunng">{{ __('Nama Gedung') }}</label>
                        <input type="text" class="form-control" id="gedung" placeholder="{{ __('Masukkan Nama Gedung') }}" name="gedung" value="{{ $ruangan->gedung }}" />
                    </div>
                    <div class="form-group">
                        <label for="lantai">{{ __('Lantai') }}</label>
                        <input type="text" class="form-control" id="lantai" placeholder="{{ __('Masukkan No Lantai') }}" name="lantai" value="{{ $ruangan->lantai }}" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection