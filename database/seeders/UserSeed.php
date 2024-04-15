<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'           => 'admin',
                'email'          => 'ilyasdwisantoso@gmail.com',
                'password'       => bcrypt('123'),
                'remember_token' => null,
            ],

            [
                'name'           => 'user',
                'email'          => 'dwi@gmail.com',
                'password'       => bcrypt('123'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
