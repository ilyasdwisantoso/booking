<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = [
        'Kode_Kelas',
        'code_token',
        'prodi_id',
        'matakuliah_id',
        'dosen_id' ,
        'ruangan_id' ,
        'day_of_week',
        'start_time',
        'end_time',
        'status',
        'room_status',
    ];

    protected $appends = ['day_of_week_text', 'status_text']; // jika membutuhkan

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'classmahasiswa', 'booking_id', 'mahasiswas_NIM', 'id', 'NIM');
    }

    public function attandances(){
        return $this->hasMany(Attendance::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'matakuliah_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function qrmahasiswa()
    {
        return $this->hasMany(Qrmahasiswa::class);
    }

    public function getDayOfWeekTextAttribute()
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return $days[$this->day_of_week];
    }

    public function getStatusTextAttribute() // jika membutuhkan
    {
        return $this->status == 'kelas sedang berlangsung' ? 'kelas sedang berlangsung' : 'kelas belum dimulai';
    }

    public function getDuration()
    {
        return Carbon::parse($this->start_time)->diffInMinutes(Carbon::parse($this->end_time));
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
