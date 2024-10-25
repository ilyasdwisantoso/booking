<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TempQrCode;
use App\Models\Attendance;
use Carbon\Carbon;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
    $qrCode = $request->input('qr_code');
    $photoFile = $request->file('photo');

    $temporaryEntry = TempQrCode::where('qr_code', $qrCode)
        ->where('expires_at', '>', now())
        ->first();

    if ($temporaryEntry) {
        $photoName = date('ymdhis') . "." . $photoFile->extension();
        $photoFile->move(public_path('photos'), $photoName);

        // Save attendance record
        Attendance::create([
            'mahasiswas_NIM' => $temporaryEntry->mahasiswas_NIM,
            'booking_id' => $temporaryEntry->booking_id,
            'attended_at' => now(),
            'photo' => $photoName,
        ]);

        // Delete temporary entry
        $temporaryEntry->delete();

        return response()->json(['success' => 'Attendance recorded successfully']);
        } else {
            return response()->json(['error' => 'QR code not valid or expired'], 400);
        }


    }
}
