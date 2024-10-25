<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index(){

        $userCount = User::count();
        $mahasiswaCount = Mahasiswa::count();
        $dosenCount = Dosen::count();
        $kelasCount = Booking::count();

        return view('admin.dashboard', compact('userCount', 'mahasiswaCount', 'dosenCount', 'kelasCount'));
    }
}
