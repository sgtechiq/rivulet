<?php
namespace Database\Seeders;

use App\Models\Users;
use Rivulet\Database\Migrations\SeedOperation;

class UserSeeder extends SeedOperation
{
    public function run()
    {
        $data = [
            'name'      => 'Administrator',
            'email'     => 'email@domain.com',
            'phone'     => null,
            'username'  => 'admin',
            'password'  => PassEncrypt('admin'),
            'authtoken' => null,
        ];
        Users::create($data);
    }
}
