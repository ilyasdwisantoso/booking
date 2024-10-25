<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateAppCredentials()
    {
        $this->appid = Str::uuid();
        $this->appsecret = Str::random(32);
        $this->save();
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole($role){
    return $this->roles()->where('title', $role)->exists();

    }
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class);
    }

    public function isAdmin()
    {
        return $this->roles()->where('title', 'admin')->exists();
    }

    public function isDosen()
    {
        return $this->roles()->where('title', 'dosen')->exists();
    }

    public function isMahasiswa()
    {
        return $this->roles()->where('title', 'mahasiswa')->exists();
    }

}
