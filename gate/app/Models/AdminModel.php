<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // We will just use 'status' enum instead of soft deletes for now

    // Fields that we can insert/update
    protected $allowedFields    = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'status',
        'last_login',
        'profile_pic'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}