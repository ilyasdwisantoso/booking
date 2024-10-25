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

        // Jika mahasiswa tidak ditemukan, kembalikan error
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard.index')->with([
                'message' => 'Data mahasiswa tidak ditemukan!',
                'alert-type' => 'danger'
            ]);
        }

        // Dapatkan daftar mata kuliah berdasarkan mahasiswa_id
        $bookings = $mahasiswa->bookings;

        return view('mahasiswa.courses.index', compact('bookings'));
    }
}
