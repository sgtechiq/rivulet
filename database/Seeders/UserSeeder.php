<?php

namespace Database\Seeders;

use Rivulet\Database\Migrations\SeedOperation;
use App\Models\Users;

class UserSeeder extends SeedOperation {
    public function run() {
        $data = [
            'name' => 'Administrator',
            'email' => 'email@domain.com',
            'phone' => null,
            'username' => 'admin',
            'password' => encrypt_password('admin'),
            'authtoken' => null,
        ];
        (new Users())->query()->insert($data);
    }
}