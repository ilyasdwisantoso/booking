@extends('layouts.dosen')

@section('content')
    <div class="container">
        <h1>Presentase Kehadiran Mahasiswa</h1>
        <p><strong>Kode Kelas:</strong> {{ $booking->Kode_Kelas }}</p>
        <p><strong>Mata Kuliah:</strong> {{ $booking->matakuliah->Nama_MK }}</p>
        <p><strong>Dosen Pengampu:</strong> {{ $booking->dosen->nama_dosen }}</p>
        <p><strong>Jumlah Pertemuan:</strong> {{ $booking->jumlah_pertemuan }}</p>
        <p><strong>Presentase Kehadiran:</strong> {{ number_format($presentase_kehadiran, 2) }}%</p>
    </div>
@endsection