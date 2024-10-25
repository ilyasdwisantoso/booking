@extends('layouts.dosen')
@section('active-page', 'Attendance')
@section('content')
<div class="container-fluid">
    <h2>Persentase Kehadiran</h2>
    <table id="attendanceTable" class="table table-bordered">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Jumlah Kehadiran</th>
                <th>Jumlah Pertemuan</th>
                <th>Persentase Kehadiran (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $data)
            <tr>
                <td>{{ $data['nim'] }}</td>
                <td>{{ $data['mahasiswa'] }}</td>
                <td>{{ $data['attendanceCount'] }}</td>
                <td>{{ $data['totalMeetings'] }}</td>
                <td>{{ $data['attendancePercentage'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5'
            ]
        });
    });
</script>
@endpush
