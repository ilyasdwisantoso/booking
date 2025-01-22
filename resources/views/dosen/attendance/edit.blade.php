@extends('layouts.dosen')
@section('active-page', 'Edit Room Status')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Status Ruangan</h1>

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

    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Status Ruangan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('dosen.room-status.update', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="room_status">Status Ruangan</label>
                    <select id="room_status" name="room_status" class="form-control" required>
                        <option value="open" {{ $booking->room_status == 'open' ? 'selected' : '' }}>Pintu Dibuka</option>
                        <option value="locked" {{ $booking->room_status == 'locked' ? 'selected' : '' }}>Pintu Ditutup</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('dosen.attendance.show', $booking->id) }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
