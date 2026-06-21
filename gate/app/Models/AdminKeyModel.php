<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminKeyModel extends Model
{
    protected $table            = 'admin_keys';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Matched to your exact database columns
    protected $allowedFields    = [
        'key_code',
        'generated_by',
        'status',
        'used_at'
    ];

    // Enable timestamps but tell CI4 to only auto-manage created_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // We will manually update 'used_at' later
}