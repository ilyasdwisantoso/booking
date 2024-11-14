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
    ];

    foreach ($roles as $role) {
        Role::updateOrInsert(['id' => $role['id']], ['title' => $role['title']]);
    }
}

}
