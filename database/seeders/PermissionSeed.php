<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['title' => 'user_management_access',],
            ['title' => 'user_management_create',],
            ['title' => 'user_management_edit',],
            ['title' => 'user_management_view',],
            ['title' => 'user_management_delete',],
            ['title' => 'permission_access',],
            ['title' => 'permission_create',],
            ['title' => 'permission_edit',],
            ['title' => 'permission_view',],
            [ 'title' => 'permission_delete',],
            [ 'title' => 'role_access',],
            [ 'title' => 'role_create',],
            [ 'title' => 'role_edit',],
            [ 'title' => 'role_view',],
            [ 'title' => 'role_delete',],
            [ 'title' => 'user_access',],
            [ 'title' => 'user_create',],
            [ 'title' => 'user_edit',],
            [ 'title' => 'user_view',],
            [ 'title' => 'user_delete',],
            [ 'title' => 'mahasiswa_access',],
            [ 'title' => 'mahasiswa_create',],
            [ 'title' => 'mahasiswa_edit',],
            [ 'title' => 'mahasiswa_view',],
            [ 'title' => 'mahasiswa_delete',],
            [ 'title' => 'dosen_access',],
            [ 'title' => 'dosen_create',],
            [ 'title' => 'dosen_edit',],
            [ 'title' => 'dosen_view',],
            [ 'title' => 'dosen_delete',],
            [ 'title' => 'prodi_access',],
            [ 'title' => 'prodi_create',],
            [ 'title' => 'prodi_edit',],
            [ 'title' => 'prodi_view',],
            [ 'title' => 'prodi_delete',],
            [ 'title' => 'matakuliah_access',],
            [ 'title' => 'matakuliah_create',],
            [ 'title' => 'matakuliah_edit',],
            [ 'title' => 'matakuliah_view',],
            [ 'title' => 'matakuliah_delete',],
            [ 'title' => 'ruangan_access',],
            [ 'title' => 'ruangan_create',],
            [ 'title' => 'ruangan_edit',],
            [ 'title' => 'ruangan_view',],
            [ 'title' => 'ruangan_delete',],
            [ 'title' => 'qrmahasiswa_access',],
            [ 'title' => 'qrmahasiswa_create',],
            [ 'title' => 'qrmahasiswa_edit',],
            [ 'title' => 'qrmahasiswa_view',],
            [ 'title' => 'qrmahasiswa_delete',],
            [ 'title' => 'booking_access',],
            [ 'title' => 'booking_create',],
            [ 'title' => 'booking_edit',],
            [ 'title' => 'booking_view',],
            [ 'title' => 'booking_delete',],
        ];

            Permission::insert($permissions);

    }
}
