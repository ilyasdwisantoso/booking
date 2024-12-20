<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'admin',
            ],
            [
                'id'    => 2,
                'title' => 'mahasiswa',
            ],
            [
                'id'    => 3,
                'title' => 'dosen',
            ],
            [
                'id'    => 4,
                'title' => 'pj',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],  // Kondisi pengecekan
                ['title' => $role['title']] // Data yang akan diperbarui atau ditambahkan
            );
        }
    }
}
