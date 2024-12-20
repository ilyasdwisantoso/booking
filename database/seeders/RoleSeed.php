<?php

use App\Models\Role;

public function run()
{
    Role::firstOrCreate(['id' => 1], ['title' => 'admin']);
    Role::firstOrCreate(['id' => 2], ['title' => 'user']);
}
