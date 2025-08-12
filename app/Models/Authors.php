<?php
namespace App\Models;

use Rivulet\Model;

class Authors extends Model
{
    protected $table    = 'authors';
    protected $fillable = ['name', 'email', 'password'];
    protected $guarded  = ['id'];
    protected $hidden   = ['password'];
}
