<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\User;



class DashboardController extends Controller
{
    public function index()
    {   
        $userCount = User::count();
        $mahasiswaCount = Mahasiswa::count();
        $dosenCount = Dosen::count();
        $kelasCount = Booking::count();
        // Implementasi untuk menampilkan dashboard mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        
        return view('mahasiswa.dashboard', compact('mahasiswa','userCount', 'mahasiswaCount', 'dosenCount', 'kelasCount'));
    }

    public function courses()
{
    // Dapatkan mahasiswa yang sedang login
    $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

    // Cek jika data mahasiswa tidak ditemukan
    if (!$mahasiswa) {
        return redirect()->route('mahasiswa.dashboard.index')->with([
            'message' => 'Data mahasiswa tidak ditemukan!',
            'alert-type' => 'danger',
        ]);
    }

    // Daftar kuliah hari ini
    $todayBookings = $mahasiswa->bookings()
        ->where('day_of_week', now()->dayOfWeek)
        ->where(function ($query) {
            $query->whereTime('start_time', '<=', now()->format('H:i:s'))
                  ->whereTime('end_time', '>=', now()->format('H:i:s'));
        })
        ->orderBy('start_time')
        ->get();

        dd($todayBookings);
    // Daftar kuliah mendatang
    $upcomingBookings = $mahasiswa->bookings()
        ->where(function ($query) {
            $query->where('day_of_week', '>', now()->dayOfWeek)
                  ->orWhere(function ($subQuery) {
                      $subQuery->where('day_of_week', now()->dayOfWeek)
                               ->whereTime('start_time', '>', now()->format('H:i:s'));
                  });
        })
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();

        
    return view('mahasiswa.courses.index', compact('todayBookings', 'upcomingBookings'));
}


}
