<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'mahasiswas_NIM',
        'booking_id',
        'attended_at',
        'pertemuan_ke',
        'photo'
    ];


    public function mahasiswas()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswas_NIM', 'NIM');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id','id');
    }

   
}
