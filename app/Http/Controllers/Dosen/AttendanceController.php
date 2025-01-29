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

    public function toggleAttendanceMode(Request $request, Booking $booking)
{
    $validatedData = $request->validate([
        'is_attendance_mode' => 'required|boolean',
        'qr_code' => 'required|string',
    ]);

    $qrCode = $validatedData['qr_code'];

    // Validasi QR Code/Token Dosen
    $dosen = Dosen::where('qr_code', $qrCode)
        ->orWhereHas('bookings', function ($query) use ($qrCode) {
            $query->where('code_token', $qrCode);
        })
        ->first();

    if (!$dosen) {
        return response()->json(['error' => 'QR Code atau Token tidak valid'], 403);
    }

    // Validasi room_status
    if ($booking->room_status === 'locked') {
        return response()->json(['error' => 'Ruangan masih terkunci. Silakan buka ruangan terlebih dahulu.'], 403);
    }

    // Update mode presensi
    $booking->update([
        'is_attendance_mode' => $validatedData['is_attendance_mode'],
    ]);

    return response()->json([
        'success' => 'Mode presensi berhasil diperbarui.',
        'is_attendance_mode' => $booking->is_attendance_mode,
    ]);
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

    public function show($id)
{
    // Ambil data kelas berdasarkan ID
    $booking = Booking::with(['mahasiswas', 'attendances'])->findOrFail($id);

    // Validasi apakah kelas tersebut milik dosen yang sedang login
    if ($booking->dosen_id !== Auth::user()->dosen->id) {
        abort(403, 'Unauthorized action.');
    }

    // Data kehadiran mahasiswa di kelas ini
    $attendances = Attendance::where('booking_id', $booking->id)->get();

    return view('dosen.attendance.show', compact('booking', 'attendances'));
}

public function editRoom(Booking $booking)
{
    // Validasi apakah booking ini milik dosen yang sedang login
    if ($booking->dosen_id !== Auth::user()->dosen->id) {
        abort(403, 'Unauthorized action.');
    }

    return view('dosen.attendance.edit', compact('booking'));
}



public function updateRoom(Request $request, Booking $booking) {
    $validatedData = $request->validate([
        'room_status' => 'required|in:open,locked',
    ]);

    // Validasi apakah booking ini milik dosen yang sedang login
    if ($booking->dosen_id !== Auth::user()->dosen->id) {
        abort(403, 'Unauthorized action.');
    }
    // Perbarui status ruangan di database
    $booking->update([
        'room_status' => $validatedData['room_status'],
    ]);

    // Kirim status ke ESP32
    $this->sendRoomStatusToESP32($validatedData['room_status']);

    return redirect()->route('dosen.attendance.show', $booking->id)
                     ->with('success', 'Status ruangan berhasil diperbarui.');
}


private function sendRoomStatusToESP32($status) {
    $esp32_ip = "http://192.168.251.192/update-room-status"; // IP ESP32
    

    $client = new \GuzzleHttp\Client();

    try {
        $response = $client->post($esp32_ip, [
            'json' => ['room_status' => $status],
            'timeout' => 5 // Timeout 5 detik
        ]);

        if ($response->getStatusCode() == 200) {
            \Log::info("ESP32 updated successfully: " . $status);
        } else {
            \Log::error("ESP32 update failed: " . $response->getStatusCode());
        }
    } catch (\Exception $e) {
        \Log::error("ESP32 request error: " . $e->getMessage());
    }
}



public function destroy($id)
{
    $attendance = Attendance::findOrFail($id);
    $bookingId = $attendance->booking_id;

    $attendance->delete();

    return redirect()->route('dosen.attendance.show', $bookingId)->with([
        'message' => 'Data berhasil dihapus.',
        'alert-type' => 'danger'
    ]);
}

}
