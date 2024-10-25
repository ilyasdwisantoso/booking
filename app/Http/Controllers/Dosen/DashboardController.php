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
        if (!$dosen) {
            return redirect()->route('dosen.dashboard.index')->with([
                'message' => 'Data dosen tidak ditemukan!',
                'alert-type' => 'danger'
            ]);
        }
        $bookings = Booking::where('dosen_id', $dosen->id)->get();
        return view('dosen.courses.index', compact('bookings'));
    }

    public function updateClassStatus()
    {
        $currentDateTime = Carbon::now();
        $dayOfWeek = $currentDateTime->format('w');
        $currentTime = $currentDateTime->format('H:i:s');

        $bookings = Booking::all();

        foreach ($bookings as $booking) {
            if ($booking->day_of_week == $dayOfWeek &&
                $booking->start_time <= $currentTime &&
                $booking->end_time >= $currentTime) {
                $booking->status = 'kelas dimulai';
            } else {
                $booking->status = 'kelas belum dimulai';
            }
            $booking->save();
        }

        return response()->json(['success' => 'Class statuses updated']);
    }

    public function getClassStatuses()
    {
        try {
            \Log::info('Fetching class statuses');
            $bookings = Booking::all();
            \Log::info('Bookings fetched successfully', ['bookings' => $bookings]);
            return response()->json(['bookings' => $bookings]);
        } catch (\Exception $e) {
            \Log::error('Error fetching class statuses: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
