<?php

namespace App\Models;

use Rivulet\Model;

class Users extends Model {
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'phone', 'username', 'password', 'authtoken'];
    protected $guarded = ['id'];
    protected $hidden = ['password', 'authtoken'];
    protected $columns = [
        'id' => 'int',
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'username' => 'string',
        'password' => 'string',
        'authtoken' => 'string',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
        'deleted' => 'int',
    ];
}