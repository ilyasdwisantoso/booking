<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCodeData extends Model
{
    protected $table = 'qr_code_data'; // Nama tabel yang sesuai dengan yang Anda buat

    protected $fillable = [
        'data', // Kolom untuk menyimpan data QR code dari ESP32
    ];

    // Jika Anda memerlukan relasi atau metode tambahan, Anda dapat menambahkannya di sini
}
