<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCodeData;

class QrCodeDataController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data jika diperlukan
        $validatedData = $request->validate([
            'data' => 'required|string', // Misalnya data QR code harus ada dan bertipe string
        ]);

        // Simpan data ke dalam database
        QrCodeData::create([
            'data' => $validatedData['data'],
        ]);

        // Beri respons atau balasan jika perlu
        return response()->json(['message' => 'Data QR Code diterima dan disimpan'], 201);
    }
}
