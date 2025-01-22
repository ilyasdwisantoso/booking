<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Booking;
use Carbon\Carbon;

class AccessControlController extends Controller
{
    public function verifyAccess(Request $request)
    {
        $validatedData = $request->validate([
            'qr_code' => 'nullable|string',
            'token' => 'nullable|string',
        ]);

        $qrCode = $validatedData['qr_code'] ?? null;
        $token = $validatedData['token'] ?? null;

        // Validasi QR Code atau Token
        $dosen = Dosen::where('qr_code', $qrCode)
            ->orWhereHas('bookings', function ($query) use ($token) {
                $query->where('code_token', $token);
            })
            ->first();

        if (!$dosen) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $currentDateTime = Carbon::now();
        $dayOfWeek = $currentDateTime->format('w');

        // Cari jadwal kelas yang aktif
        $classSchedule = Booking::where('dosen_id', $dosen->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$classSchedule) {
            return response()->json(['error' => 'Tidak ada jadwal kelas aktif untuk hari ini'], 404);
        }

        // Perbarui status ruangan menjadi "open" dan aktifkan mode presensi
        $roomStatus = $classSchedule->room_status == 'locked' ? 'open' : 'locked';
        $classSchedule->update([
            'room_status' => $roomStatus,
            'is_attendance_mode' => $roomStatus === 'open', // Mode presensi ON jika ruangan terbuka
        ]);

        return response()->json([
            'success' => 'Akses berhasil divalidasi',
            'action' => $roomStatus,
            'room_status' => $classSchedule->room_status,
            'is_attendance_mode' => $classSchedule->is_attendance_mode,
        ], 200);
    }
}
