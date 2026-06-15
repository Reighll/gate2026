<?php

namespace App\Models;

use CodeIgniter\Model;

class GuardModel extends Model
{
    protected $table            = 'guards';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields    = [
        'username',
        'first_name',
        'last_name',
        'password',
        'profile_pic',
        'status',
        'last_login'
    ];
}