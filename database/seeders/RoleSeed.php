<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

public function run()
{
    Role::firstOrCreate(['id' => 1], ['title' => 'admin']);
    Role::firstOrCreate(['id' => 2], ['title' => 'user']);

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'title' => 'admin'],
            ['id' => 2, 'title' => 'mahasiswa'],
            ['id' => 3, 'title' => 'dosen'],
            ['id' => 4, 'title' => 'pj'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                ['title' => $role['title']]
            );
        }
    }
}



