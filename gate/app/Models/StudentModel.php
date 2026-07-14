<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields    = [
        'first_name',
        'last_name',
        'student_number',
        'email',
        'password',
        'department',
        'year_level',
        'is_verified',
        'verify_token',
        'profile_pic',
        'reset_token',
        'reset_token_expires'
    ];
}