@extends('layouts.dosen')
@section('active-page', 'Attendance')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Detail Kehadiran Kelas: {{ $booking->Kode_Kelas }}</h1>
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
                            <th>Mode Presensi</th>
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
                                <td id="attendance-mode-{{ $booking->id }}">
                                    <label class="switch">
                                        <input type="checkbox" 
                                               {{ $booking->is_attendance_mode ? 'checked' : '' }} 
                                               onchange="toggleAttendanceMode({{ $booking->id }}, this.checked)">
                                        <span class="slider round"></span>
                                    </label>
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

@push('script-alt')
<script>
    function toggleAttendanceMode(bookingId, newStatus) {
        const qrCode = prompt("Masukkan QR Code Dosen/Token Kelas:");
        if (!qrCode) {
            alert("Validasi QR Code atau Token diperlukan.");
            document.querySelector(`#attendance-mode-${bookingId} input`).checked = !newStatus;
            return;
        }

        fetch(`/dosen/attendance-mode/${bookingId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ is_attendance_mode: newStatus, qr_code: qrCode }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else if (data.error) {
                alert(data.error);
                // Revert the switch state
                document.querySelector(`#attendance-mode-${bookingId} input`).checked = !newStatus;
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #4CAF50;
    }

    input:checked + .slider:before {
        transform: translateX(14px);
    }
</style>
