<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Attendance;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $dosen_id = Auth::user()->dosen->id;
        $bookings = Booking::where('dosen_id', $dosen_id)->get();

        // Get all attendance records for the logged-in lecturer's classes
        $attendances = Attendance::whereIn('booking_id', $bookings->pluck('id'))->get();

        // Calculate attendance percentage for each student in each class
        $attendanceData = [];
        foreach ($bookings as $booking) {
            $totalMeetings = $booking->jumlah_pertemuan;
            $students = $booking->mahasiswas;

            foreach ($students as $student) {
                $attendanceCount = $attendances->where('mahasiswas_NIM', $student->NIM)->where('booking_id', $booking->id)->count();
                $attendancePercentage = ($totalMeetings > 0) ? ($attendanceCount / $totalMeetings) * 100 : 0;

                $attendanceData[] = [
                    'student' => $student,
                    'booking' => $booking,
                    'attendanceCount' => $attendanceCount,
                    'attendancePercentage' => $attendancePercentage,
                ];
            }
        }

        return view('dosen.attendance.index', compact('bookings', 'attendanceData', 'attendances'));
    }

    public function updatePertemuan(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->jumlah_pertemuan = $request->input('jumlah_pertemuan');
        $booking->save();

        return redirect()->route('dosen.attendance.index')->with('success', 'Jumlah pertemuan berhasil diperbarui.');
    }



    public function showAttendanceChart($id)
    {
    // Retrieve the booking
    $booking = Booking::find($id);

    // Ensure the booking exists
    if (!$booking) {
        abort(404, 'Booking not found.');
    }

    // Ensure the booking belongs to the authenticated dosen
    if ($booking->dosen_id !== Auth::user()->dosen->id) {
        abort(403, 'Unauthorized action.');
    }

    $attendances = Attendance::where('booking_id', $booking->id)->get();
    $totalMeetings = $booking->jumlah_pertemuan;

    $attendanceData = $attendances->groupBy('mahasiswas_NIM')->map(function ($attendanceGroup) use ($totalMeetings) {
        $attendanceCount = $attendanceGroup->count();
        $attendancePercentage = ($totalMeetings > 0) ? ($attendanceCount / $totalMeetings) * 100 : 0;

        return [
            'mahasiswa' => $attendanceGroup->first()->mahasiswas->Nama,
            'nim' => $attendanceGroup->first()->mahasiswas->NIM,
            'attendanceCount' => $attendanceCount,
            'attendancePercentage' => number_format($attendancePercentage, 2),
            'totalMeetings' => $totalMeetings,
        ];
    });

    return view('dosen.attendance.attendance_chart', compact('booking', 'attendanceData'));
    }

    public function massDestroy(Request $request)
    {
        
        Attendance::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }


}
