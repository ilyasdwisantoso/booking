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
                <form action="{{ route('admin.booking.update', $booking->id ) }}"  method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="Kode_Kelas">{{ __('Kode Kelas(Nama Kelas)') }}</label>
                        <input type="text" class="form-control" id="Kode_Kelas" placeholder="{{ __('Masukkan Kode Kelas') }}" name="Kode_Kelas" required value="{{ $booking->Kode_Kelas }}" />
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
                        <select class="form-control" name="prodi_id" required="required" id="prodi">
                            @foreach($prodi as $id => $p)
                                <option value="{{ $id }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="matakuliah">{{ __('Nama Matakuliah') }}</label>
                        <select class="form-control" name="matakuliah_id" required="required" id="matakuliah">
                            @foreach($matakuliah as $id => $matkul)
                                <option value="{{ $id }}">{{ $matkul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dosen">{{ __('Nama Dosen') }}</label>
                        <select class="form-control" name="dosen_id" required="required" id="dosen">
                            @foreach($dosen as $id => $d)
                                <option value="{{ $id }}" >{{ $d }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ruangan">{{ __('Nama Ruangan') }}</label>
                        <select class="form-control" name="ruangan_id" required="required" id="ruangan">
                            @foreach($ruangan as $id => $r)
                            <option value="{{ $id }}">{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="day_of_week">{{ __('Hari') }}</label>
                        <select class="form-control" name="day_of_week" id="day_of_week" required="required">
                                <option value="0" {{ $booking->day_of_week == 0 ? 'selected' : null }}>Sunday</option>
                                <option value="1" {{ $booking->day_of_week == 1 ? 'selected' : null }}>Monday</option>
                                <option value="2" {{ $booking->day_of_week == 2 ? 'selected' : null }}>Tuesday</option>
                                <option value="3" {{ $booking->day_of_week == 3 ? 'selected' : null }}>Wednesday</option>
                                <option value="4" {{ $booking->day_of_week == 4 ? 'selected' : null }}>Thursday</option>
                                <option value="5" {{ $booking->day_of_week == 5 ? 'selected' : null }}>Friday</option>
                                <option value="6" {{ $booking->day_of_week == 6 ? 'selected' : null }}>Saturday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_time">Waktu Mulai</label>
                        <input type="time" name="start_time" id="start_time" required="required" class="form-control" value="{{ old('start_time', $booking->start_time ) }}">
                    </div>
            
                    <div class="form-group">
                        <label for="end_time">Waktu Selesai</label>
                        <input type="time" name="end_time" id="end_time" required="required" class="form-control" value="{{ old('end_time', $booking->end_time ) }}">
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
            format: "YYYY-MM-DD HH:mm"
        });
    </script>
@endpush