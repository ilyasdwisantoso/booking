@extends('layouts.admin')
@section('active-page', 'Jadwal Kelas')
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
            <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Booking Kelas') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.booking.index') }}" class="btn btn-primary">
                        <span class="text">{{ __('Go Back') }}</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                    <!-- Kode Kelas -->
                    <div class="form-group">
                        <label for="Kode_Kelas">{{ __('Kode Kelas (Nama Kelas)') }}</label>
                        <input type="text" class="form-control" id="Kode_Kelas" name="Kode_Kelas" required value="{{ old('Kode_Kelas', $booking->Kode_Kelas) }}" />
                    </div>
                
                    <!-- Mahasiswa -->
                    <div class="form-group">
                        <label for="mahasiswas">{{ __('Mahasiswa') }}</label>
                        <select name="mahasiswas[]" id="mahasiswas" class="form-control select2" multiple="multiple" required>
                            @foreach($mahasiswas as $NIM => $namaMahasiswa)
                                <option value="{{ $NIM }}" 
                                    {{ $booking->mahasiswas->pluck('NIM')->contains($NIM) ? 'selected' : '' }}>
                                    {{ $namaMahasiswa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    <!-- Prodi -->
                    <div class="form-group">
                        <label for="prodi">{{ __('Nama Prodi') }}</label>
                        <select class="form-control" name="prodi_id" id="prodi" required>
                            @foreach($prodi as $id => $namaProdi)
                                <option value="{{ $id }}" {{ $booking->prodi_id == $id ? 'selected' : '' }}>
                                    {{ $namaProdi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    <!-- Matakuliah -->
                    <div class="form-group">
                        <label for="matakuliah">{{ __('Nama Matakuliah') }}</label>
                        <select class="form-control" name="matakuliah_id" id="matakuliah" required>
                            @foreach($matakuliah as $id => $namaMatkul)
                                <option value="{{ $id }}" {{ $booking->matakuliah_id == $id ? 'selected' : '' }}>
                                    {{ $namaMatkul }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    <!-- Dosen -->
                    <div class="form-group">
                        <label for="dosen">{{ __('Nama Dosen') }}</label>
                        <select class="form-control" name="dosen_id" id="dosen" required>
                            @foreach($dosen as $id => $namaDosen)
                                <option value="{{ $id }}" {{ $booking->dosen_id == $id ? 'selected' : '' }}>
                                    {{ $namaDosen }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    <!-- Ruangan -->
                    <div class="form-group">
                        <label for="ruangan">{{ __('Nama Ruangan') }}</label>
                        <select class="form-control" name="ruangan_id" id="ruangan" required>
                            @foreach($ruangan as $id => $namaRuangan)
                                <option value="{{ $id }}" {{ $booking->ruangan_id == $id ? 'selected' : '' }}>
                                    {{ $namaRuangan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    <!-- Hari -->
                    <div class="form-group">
                        <label for="day_of_week">{{ __('Hari') }}</label>
                        <select class="form-control" name="day_of_week" id="day_of_week" required>
                            <option value="0" {{ $booking->day_of_week == 0 ? 'selected' : '' }}>Sunday</option>
                            <option value="1" {{ $booking->day_of_week == 1 ? 'selected' : '' }}>Monday</option>
                            <option value="2" {{ $booking->day_of_week == 2 ? 'selected' : '' }}>Tuesday</option>
                            <option value="3" {{ $booking->day_of_week == 3 ? 'selected' : '' }}>Wednesday</option>
                            <option value="4" {{ $booking->day_of_week == 4 ? 'selected' : '' }}>Thursday</option>
                            <option value="5" {{ $booking->day_of_week == 5 ? 'selected' : '' }}>Friday</option>
                            <option value="6" {{ $booking->day_of_week == 6 ? 'selected' : '' }}>Saturday</option>
                        </select>
                    </div>
                
                    <!-- Waktu Mulai -->
                    <div class="form-group">
                        <label for="start_time">Waktu Mulai</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required value="{{ old('start_time', $booking->start_time) }}">
                    </div>
                
                    <!-- Waktu Selesai -->
                    <div class="form-group">
                        <label for="end_time">Waktu Selesai</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required value="{{ old('end_time', $booking->end_time) }}">
                    </div>
                
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
                
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection


@push('style-alt')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endpush

@push('script-alt')
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: "YYYY-MM-DD HH:mm"
        });
    </script>
@endpush