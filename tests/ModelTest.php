<?php
namespace Tests;

use App\Models\Users;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function testCreateUser()
    {
        $data = ['name' => 'Test', 'email' => 'test@example.com', 'username' => 'test', 'password' => 'hashed'];
        $user = new Users($data);
        $user->save();
        $this->assertNotNull($user->getAttribute('id'));
    }

    // Add more tests for all, find, update, delete
}
