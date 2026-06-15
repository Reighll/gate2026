<?php

namespace App\Models;

use CodeIgniter\Model;

class VisitorLogModel extends Model
{
    protected $table            = 'visitor_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false; // We manage time_in/out manually

    protected $allowedFields    = [
        'name',
        'purpose',
        'items',
        'valid_id',
        'id_photo',
        'tag_id',    // The human label (Visitor 1)
        'rfid_uid',  // <--- THIS WAS MISSING!
        'status',
        'time_in',
        'time_out',
        'guard_in',
        'guard_out'
    ];
}