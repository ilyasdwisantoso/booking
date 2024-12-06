<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Data User
        User::updateOrCreate(
            ['email' => 'chacha@gmail.com'], // Kondisi pencarian
            [
                'name' => 'Chacha Peregrin',
                'password' => Hash::make('chacha123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'shinta@gmail.com'], // Kondisi pencarian
            [
                'name' => 'Shinta Ariyani',
                'password' => Hash::make('shinta123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'putraferdiansyah@gmail.com'], // Kondisi pencarian
            [
                'name' => 'Putra Ferdiansyah',
                'password' => Hash::make('putra123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'dwi@gmail.com'], // Kondisi pencarian
            [
                'name' => 'Dwi Hardian',
                'password' => Hash::make('dwi123'),
            ]
        );

        // Relasi dengan tabel mahasiswas
        $this->updateMahasiswaRelations();
    }

    private function updateMahasiswaRelations()
    {
        // Data mahasiswa yang akan diperbarui
        $mahasiswas = [
            [
                'NIM' => '1501619005',
                'Nama' => 'Chacha Peregrin',
                'tgl_lahir' => '2024-07-02',
                'photo' => '240709121421.jpg',
                'qr_code' => '9321431833',
                'email' => 'chacha@gmail.com',
            ],
            [
                'NIM' => '1501619023',
                'Nama' => 'Shinta Ariyani',
                'tgl_lahir' => '2024-07-01',
                'photo' => '240712084252.jpg',
                'qr_code' => '2009056648',
                'email' => 'shinta@gmail.com',
            ],
            [
                'NIM' => '1501619044',
                'Nama' => 'Putra Ferdiansyah',
                'tgl_lahir' => '2024-07-01',
                'photo' => '240712084311.jpg',
                'qr_code' => '6146074716',
                'email' => 'putraferdiansyah@gmail.com',
            ],
            [
                'NIM' => '1501619066',
                'Nama' => 'Dwi Hardian',
                'tgl_lahir' => '2024-11-08',
                'photo' => null,
                'qr_code' => '4001654918',
                'email' => 'dwi@gmail.com',
            ],
        ];

        foreach ($mahasiswas as $mahasiswaData) {
            // Cari user berdasarkan email
            $user = User::where('email', $mahasiswaData['email'])->first();

            if ($user) {
                // Perbarui atau buat data mahasiswa dengan user_id yang sesuai
                Mahasiswa::updateOrCreate(
                    ['NIM' => $mahasiswaData['NIM']], // Kondisi pencarian berdasarkan NIM
                    [
                        'Nama' => $mahasiswaData['Nama'],
                        'tgl_lahir' => $mahasiswaData['tgl_lahir'],
                        'photo' => $mahasiswaData['photo'],
                        'qr_code' => $mahasiswaData['qr_code'],
                        'user_id' => $user->id, // Relasi dengan user
                    ]
                );
            }
        }
    }
}
