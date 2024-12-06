<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeedPivot extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data relasi user dengan role
        $roles = [
            1 => [1], // User ID 1 dengan role admin (Role ID 1)
            21 => [2], // User ID 21 dengan role mahasiswa (Role ID 2)
            22 => [2], // User ID 22 dengan role mahasiswa (Role ID 2)
            25 => [2], // User ID 25 dengan role mahasiswa (Role ID 2)
            28 => [2], // User ID 28 (Dwi Hardian) dengan role mahasiswa (Role ID 2)
        ];

        foreach ($roles as $userId => $roleIds) {
            $user = User::find($userId);

            if ($user) {
                // Sinkronisasi role untuk setiap user
                $user->roles()->sync($roleIds);
            }
        }
    }
}
