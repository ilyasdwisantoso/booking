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
        $qrCode = $request->input('qr_code');
        $token = $request->input('token');

        // Cek apakah dosen valid dengan QR code atau token
        $dosen = Dosen::where('qr_code', $qrCode)
                    ->orWhereHas('bookings', function ($query) use ($token) {
                        $query->where('code_token', $token);
                    })
                    ->first();

        if (!$dosen) {
            return response()->json(['error' => 'Access Denied'], 403);
        }

        $currentDateTime = Carbon::now();
        $dayOfWeek = $currentDateTime->format('w');

        // Periksa apakah dosen memiliki jadwal kelas yang aktif pada hari ini
        $classSchedule = Booking::where('dosen_id', $dosen->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$classSchedule) {
            return response()->json(['error' => 'No active class for this schedule'], 404);
        }

        // Perbarui status ruangan menjadi "open" atau "locked"
        $roomStatus = $classSchedule->room_status == 'locked' ? 'open' : 'locked';
        $classSchedule->update(['room_status' => $roomStatus]);

        return response()->json([
            'success' => 'Status updated',
            'action' => $roomStatus,
            'room_status' => $classSchedule->room_status
        ], 200);
    }
}
