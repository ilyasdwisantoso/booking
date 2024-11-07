@extends('layouts.dosen')
@section('active-page', 'Jadwal Kelas')
@section('content')
<div class="container-fluid">

    @if(session('message'))
        <div class="alert alert-{{ session('alert-type', 'info') }}">
            {{ session('message') }}
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

    <!-- Content Row -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex">
            <h1 class="h3 mb-0 text-gray-800">{{ __('Create Kelas') }}</h1>
            <div class="ml-auto">
                <a href="{{ route('dosen.courses.index') }}" class="btn btn-primary">
                    <span class="text">{{ __('Go Back') }}</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('dosen.courses.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="Kode_Kelas">{{ __('Nama Kelas/Kode Kelas') }}</label>
                    <input type="text" class="form-control" id="Kode_Kelas" placeholder="{{ __('Masukkan Kode Kelas') }}" name="Kode_Kelas" value="{{ old('Kode_Kelas') }}" />
                </div>
                <div class="form-group">
                    <label for="mahasiswas">{{ __('Mahasiswas') }}</label>
                    <select name="mahasiswas[]" id="mahasiswas" class="form-control select2" multiple="multiple" required>
                        @foreach($mahasiswas as $NIM => $mahasiswas)
                            <option value="{{ $NIM }}" {{ (in_array($NIM, old('mahasiswas', [])) || isset($booking) && $booking->mahasiswas->contains($NIM)) ? 'selected' : '' }}>{{ $mahasiswas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="prodi">{{ __('Nama Prodi') }}</label>
                    <select class="form-control" name="prodi_id" id="prodi">
                        @foreach($prodi as $id => $p)
                            <option value="{{ $id }}" {{ old('prodi_id') == $id ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="matakuliah">{{ __('Nama Matakuliah') }}</label>
                    <select class="form-control" name="matakuliah_id" id="matakuliah">
                        @foreach($matakuliah as $id => $matkul)
                            <option value="{{ $id }}" {{ old('matakuliah_id') == $id ? 'selected' : '' }}>{{ $matkul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="dosen">{{ __('Nama Dosen') }}</label>
                    <input type="text" class="form-control" value="{{ $dosen->nama_dosen }}" disabled>
                    <input type="hidden" name="dosen_id" value="{{ $dosen->id }}">
                </div>
                
                <div class="form-group">
                    <label for="ruangan">{{ __('Nama Ruangan') }}</label>
                    <select class="form-control" name="ruangan_id" id="ruangan">
                        @foreach($ruangan as $id => $r)
                            <option value="{{ $id }}" {{ old('ruangan_id') == $id ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="day_of_week">{{ __('Hari') }}</label>
                    <select class="form-control" name="day_of_week" id="day_of_week">
                        <option value="0" {{ old('day_of_week') == '0' ? 'selected' : '' }}>Sunday</option>
                        <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>Monday</option>
                        <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>Tuesday</option>
                        <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>Wednesday</option>
                        <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>Thursday</option>
                        <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>Friday</option>
                        <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>Saturday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_time">{{ __('Waktu Mulai') }}</label>
                    <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}">
                </div>
                <div class="form-group">
                    <label for="end_time">{{ __('Waktu Selesai') }}</label>
                    <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
                </div>
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
        format: 'YYYY-MM-DD HH:mm',
        locale: 'en',
        sideBySide: true,
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            date: 'fas fa-calendar',
            time: 'fas fa-clock'
        }
    });
</script>
@endpush
