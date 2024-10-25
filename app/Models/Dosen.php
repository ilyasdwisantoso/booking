<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'dosen_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
