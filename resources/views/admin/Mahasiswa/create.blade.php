@extends('layouts.admin')

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
            <h1 class="h3 mb-0 text-gray-800">{{ __('create Mahasiswa') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="Nama">{{ __('Nama Lengkap') }}</label>
                        <input type="text" class="form-control" id="Nama" placeholder="{{ __('name') }}" name="name" value="{{ old('name') }}" />
                    </div>
                    
                    <div class="form-group">
                        <label for="NIM">{{ __('NIM') }}</label>
                        <input type="text" class="form-control" id="NIM" placeholder="{{ __('NIM') }}" name="NIM" value="{{ old('NIM') }}" />
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input type="number" class="form-control" id="phone" placeholder="{{ __('phone') }}" name="phone" value="{{ old('phone') }}" />
                    </div>
                    <div class="form-group">
                        <label for="address">{{ __('Address') }}</label>
                        <textarea class="form-control" name="address" id="address" placeholder="address" cols="30" rows="10">{{ old('address') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection