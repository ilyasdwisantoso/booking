<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classmahasiswa extends Model
{
    use HasFactory;

    protected $table = 'classmahasiswa';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = [
        'booking_id',
        'mahasiswas_NIM',
    ];
    public function mahasiswas()
    {
        return $this->belongsTo(Mahasiswa::class,'mahasiswas_NIM');
    }

    public function bookings()
    {
        return $this->belongsTo(Booking::class,'booking_id');
    }
}
