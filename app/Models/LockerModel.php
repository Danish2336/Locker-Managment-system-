<?php

namespace App\Models;

use CodeIgniter\Model;

class LockerModel extends Model
{
    protected $table = 'lockers';
    protected $primaryKey = 'locker_id';
    protected $allowedFields = ['status', 'user_id', 'locker_no', 'next_locker_no'];
}
