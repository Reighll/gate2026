<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemLogModel extends Model
{
    protected $table = 'item_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['item_id', 'action', 'created_at'];
}