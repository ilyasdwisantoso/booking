<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory, Sluggable;
    protected $table = 'prodi';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama_prodi',
                'onUpdate' => 'true'
            ]
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'dosen_id');
    }
}
