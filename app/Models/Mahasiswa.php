<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mahasiswa extends Model
{
    use HasFactory;

    

    protected $primaryKey = 'NIM';
    protected $table = 'mahasiswas';
    protected $guarded = ['created_at', 'updated_at'];
    protected $fillable = [
        'Nama',
        'NIM',
        'tgl_lahir',
        'photo',
        'qr_code',
        'user_id'
    ];
    
    // Mahasiswa.php
public function bookings()
{
    return $this->belongsToMany(Booking::class, 'classmahasiswa', 'mahasiswas_NIM', 'booking_id', 'NIM', 'id');
}


    public function attandances(){
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
{
    return $this->hasMany(Attendance::class, 'mahasiswas_NIM', 'NIM');
}
    
}