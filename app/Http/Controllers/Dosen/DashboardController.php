<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Mahasiswa;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $mahasiswaCount = Mahasiswa::count();
        $dosenCount = Dosen::count();
        $kelasCount = Booking::count();
        $dosen = Dosen::where('user_id', Auth::id())->first();
        return view('dosen.dashboard', compact('dosen', 'userCount', 'mahasiswaCount', 'dosenCount', 'kelasCount'));
    }

    public function courses()
{
    $dosen = Dosen::where('user_id', Auth::id())->first();

    // Redirect jika data dosen tidak ditemukan
    if (!$dosen) {
        return redirect()->route('dosen.dashboard.index')->with([
            'message' => 'Data dosen tidak ditemukan!',
            'alert-type' => 'danger'
        ]);
    }

    // Hari ini berdasarkan format day_of_week (0 = Minggu, 1 = Senin, dst.)
    $today = Carbon::now();
    $todayDayOfWeek = $today->format('w');

    // Data jadwal kelas hari ini
    $todayBookings = Booking::where('dosen_id', $dosen->id)
        ->where('day_of_week', $todayDayOfWeek)
        ->get();

    // Data jadwal kelas mendatang
    $upcomingBookings = Booking::where('dosen_id', $dosen->id)
        ->where('day_of_week', '!=', $todayDayOfWeek)
        ->get();

    // Kirim data ke view
    return view('dosen.courses.index', compact('todayBookings', 'upcomingBookings'));
}


    public function updateClassStatus()
{
    $currentDateTime = Carbon::now();
    $dayOfWeek = $currentDateTime->format('w');
    $currentTime = $currentDateTime->format('H:i:s');

    // Hanya ambil jadwal kelas milik dosen yang login
    $bookings = Booking::where('dosen_id', auth()->user()->dosen->id)->get();

    foreach ($bookings as $booking) {
        if ($booking->day_of_week == $dayOfWeek &&
            $booking->start_time <= $currentTime &&
            $booking->end_time >= $currentTime) {
            $booking->status = 'kelas dimulai';
            $booking->room_status = 'open';
        } else {
            $booking->status = 'kelas belum dimulai';
            $booking->room_status = 'closed';
        }
        $booking->save();
    }

    return response()->json(['bookings' => $bookings]);
}

public function getClassStatuses()
{
    // Ambil jadwal kelas milik dosen yang login
    $bookings = Booking::where('dosen_id', auth()->user()->dosen->id)->get();

    return response()->json(['bookings' => $bookings]);
}

}
