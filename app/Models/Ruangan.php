<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'dosen_id');
    }
}
