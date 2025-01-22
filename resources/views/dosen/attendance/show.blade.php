@extends('layouts.dosen')
@section('active-page', 'Attendance')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Detail Kehadiran Kelas: {{ $booking->Kode_Kelas }}</h1>
        <!-- Form untuk edit room status -->
        <form action="{{ route('dosen.room-status.update', $booking->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <div class="input-group">
                <select name="room_status" class="form-select form-select-sm" style="width: auto;" required>
                    <option value="open" {{ $booking->room_status === 'open' ? 'selected' : '' }}>Pintu Dibuka</option>
                    <option value="locked" {{ $booking->room_status === 'locked' ? 'selected' : '' }}>Pintu Ditutup</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Kehadiran Mahasiswa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-attendance-records" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Kode Kelas</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Pertemuan Ke</th>
                            <th>Foto Profil Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $attendance->mahasiswas->Nama }}</td>
                                <td>{{ $attendance->mahasiswas->NIM }}</td>
                                <td>{{ $booking->Kode_Kelas }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->translatedFormat('l') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('H:i:s') }}</td>
                                <td>{{ $attendance->pertemuan_ke }}</td>
                                <td>
                                    @if ($attendance->mahasiswas->photo)
                                        <img src="{{ url('photo').'/'.$attendance->mahasiswas->photo }}" 
                                             alt="Database Photo" 
                                             style="max-width:250px; max-height:250px; object-fit:cover;">
                                    @else
                                        <span>Tidak Ada Foto</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
