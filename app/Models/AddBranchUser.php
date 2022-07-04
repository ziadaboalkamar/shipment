<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddBranchUser extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'serial';
    protected $guarded = [];
    protected $table = 'add_branch_users_tb';
}
