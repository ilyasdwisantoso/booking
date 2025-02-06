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
    // Validasi input
    $validatedData = $request->validate([
        'qr_code' => 'nullable|string',
        'token' => 'nullable|string',
        'room_id' => 'required|integer', // Validasi room_id
    ]);

    $qrCode = $validatedData['qr_code'] ?? null;
    $token = $validatedData['token'] ?? null;
    $roomId = $validatedData['room_id'];

    // Validasi QR Code atau Token
    $dosen = Dosen::when($qrCode, function ($query) use ($qrCode) {
            $query->where('qr_code', $qrCode);
        })
        ->when($token, function ($query) use ($token) {
            $query->orWhereHas('bookings', function ($query) use ($token) {
                $query->where('code_token', $token);
            });
        })
        ->first();

    if (!$dosen) {
        return response()->json(['error' => 'Akses ditolak'], 403);
    }

    $currentDateTime = Carbon::now();
    $dayOfWeek = $currentDateTime->format('w');

    // Cari jadwal kelas yang aktif berdasarkan dosen, hari, dan room_id
    $classSchedule = Booking::where('dosen_id', $dosen->id)
        ->where('day_of_week', $dayOfWeek)
        ->where('ruangan_id', $roomId) // Validasi ruangan
        ->first();

    if (!$classSchedule) {
        return response()->json(['error' => 'Tidak ada jadwal kelas aktif untuk ruangan ini'], 404);
    }

    // Update status ruangan
    $newRoomStatus = $classSchedule->room_status === 'locked' ? 'open' : 'locked';
    $statusText = $newRoomStatus === 'open' ? 'pintu dibuka' : 'pintu ditutup';

    $classSchedule->update([
        'room_status' => $newRoomStatus,
        'status' => $statusText,
    ]);

    return response()->json([
        'success' => 'Akses berhasil divalidasi',
        'action' => $newRoomStatus,
        'room_status' => $classSchedule->room_status,
        'status' => $classSchedule->status,
        'room_id' => $classSchedule->ruangan_id,
    ], 200);
}


}
