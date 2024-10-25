<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempQrCode extends Model
{
    use HasFactory;

    protected $fillable = ['mahasiswas_NIM', 'qr_code', 'expires_at'];

    protected $dates = ['expires_at'];
}
