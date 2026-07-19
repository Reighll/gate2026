<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentItemModel extends Model
{
    protected $table            = 'student_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields    = [
        'student_id',
        'category',
        'brand_model',
        'serial_number',
        'photo',
        'status',
        'notes',
        'reason',
        'created_at',
        'updated_at',
        'rfid',
        'in_campus',
        'resolved_at'
    ];
}