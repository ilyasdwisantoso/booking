<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Attendance;
use App\Models\Booking;


class AttendanceController extends Controller
{
    public function index()
{
    // Dapatkan mahasiswa yang sedang login
    $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

    // Jika mahasiswa tidak ditemukan, kembalikan error
    if (!$mahasiswa) {
        return redirect()->route('mahasiswa.dashboard.index')->with([
            'message' => 'Data mahasiswa tidak ditemukan!',
            'alert-type' => 'danger',
        ]);
    }

    // Mengambil semua bookings yang terhubung dengan mahasiswa
    $bookings = $mahasiswa->bookings;

    // Inisialisasi array untuk data kehadiran
    $attendanceData = [];

    // Looping untuk setiap booking yang dimiliki mahasiswa
    foreach ($bookings as $booking) {
        // Mengambil semua presensi yang terkait dengan mahasiswa dan booking ini
        $attendances = Attendance::where('mahasiswas_NIM', $mahasiswa->NIM)
            ->where('booking_id', $booking->id)
            ->get();

        // Hitung jumlah presensi
        $attendanceCount = $attendances->count();

        // Hitung total pertemuan yang dijadwalkan
        $totalMeetings = $booking->jumlah_pertemuan;

        // Hitung persentase kehadiran
        $attendancePercentage = ($totalMeetings > 0) ? ($attendanceCount / $totalMeetings) * 100 : 0;

        // Simpan data kehadiran dalam array
        $attendanceData[] = [
            'student' => $mahasiswa,
            'booking' => $booking,
            'attendanceCount' => $attendanceCount,
            'attendancePercentage' => $attendancePercentage,
        ];
    }

    // Variabel `$classes` adalah bookings milik mahasiswa
    $classes = $bookings;

    // Dapatkan daftar presensi berdasarkan NIM mahasiswa
    $attendances = Attendance::where('mahasiswas_NIM', $mahasiswa->NIM)->get();

    // Kirim data ke view
    return view('mahasiswa.attendance.index', compact('attendanceData', 'attendances', 'classes'));
}
public function show($bookingId)
{
    $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

    if (!$mahasiswa) {
        return redirect()->route('mahasiswa.attendance.index')->with([
            'message' => 'Data mahasiswa tidak ditemukan!',
            'alert-type' => 'danger'
        ]);
    }

    $booking = Booking::findOrFail($bookingId);

    if ($booking->mahasiswa_id !== $mahasiswa->id) {
        return redirect()->route('mahasiswa.attendance.index')->with([
            'message' => 'Anda tidak memiliki akses ke kelas ini!',
            'alert-type' => 'danger'
        ]);
    }

    // Ambil semua presensi untuk mahasiswa dan kelas tertentu
    $attendances = Attendance::where('mahasiswas_NIM', $mahasiswa->NIM)
        ->where('booking_id', $bookingId)
        ->get();

    // Kirim variabel yang diperlukan ke view
    return view('mahasiswa.attendance.show', compact('booking', 'attendances'));
}


    public function showAttendancePercentage($booking_id)
    {
        // Ambil data booking
        $booking = Booking::findOrFail($booking_id);

        // Hitung jumlah pertemuan yang sudah dilakukan
        $jumlah_pertemuan = $booking->jumlah_pertemuan;

        // Hitung jumlah pertemuan yang dihadiri oleh mahasiswa
        $attendances = Attendance::where('booking_id', $booking_id)->count();

        // Hitung presentase kehadiran
        $presentase_kehadiran = $jumlah_pertemuan > 0 ? ($attendances / $jumlah_pertemuan) * 100 : 0;

        return view('mahasiswa.attendance.percentage', [
            'booking' => $booking,
            'presentase_kehadiran' => $presentase_kehadiran,
        ]);
    }
}
