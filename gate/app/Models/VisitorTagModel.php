<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitorTagModel extends Model
{
    protected $table            = 'visitor_tags';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    // These match the columns in your database
    protected $allowedFields    = [
        'rfid_uid',
        'pass_number',
        'status' // 'available', 'in_use', 'lost'
    ];
}