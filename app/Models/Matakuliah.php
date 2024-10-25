<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function bookings()
    {
        return $this->hasMany(Booking::class,'dosen_id', 'matakuliah_id');
    }

    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Booking::class, 'matakuliah_id', 'booking_id', 'id', 'id');
    }
}

