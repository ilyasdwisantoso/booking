<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRCode;

class QRCodeController extends Controller
{
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        // Simpan data QR code ke database
        $qr_code = new QRCode();
        $qr_code->qr_code = $request->qr_code;
        $qr_code->save();

        return response()->json(['message' => 'QR code saved successfully'], 200);
    }

    public function index()
    {
        // Ambil semua data QR code
        $qrCodes = QRCode::all();

        // Tampilkan data menggunakan view blade
        return view('qr_codes.index', ['qrCodes' => $qrCodes]);
    }
}
