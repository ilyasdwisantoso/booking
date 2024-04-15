<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mahasiswa')->insert([
            'Nama'      => 'ilyas dwi santoso',
            'NIM'       => 1501619033,
            'tgl_lahir' => '2000-11-05'
        ]);

        DB::table('mahasiswa')->insert([
            'Nama'      => 'Shinta',
            'NIM'       => 1501619024,
            'tgl_lahir' => '2000-11-04'
        ]);
    }
}
