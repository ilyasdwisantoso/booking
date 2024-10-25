@extends('layouts.admin')
@section('active-page', 'Prodi')
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
            <h1 class="h3 mb-0 text-gray-800">{{ __('create prodi') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.prodi.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.prodi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_prodi">{{ __('Nama prodi') }}</label>
                        <input type="text" class="form-control" id="nama_prodi" placeholder="{{ __('nama prodi') }}" name="nama_prodi" value="{{ old('nama_prodi') }}" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection