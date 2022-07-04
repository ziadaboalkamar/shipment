<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BranchInfo extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'serial';
    protected $table = 'branch_info_tb';
    protected $guarded = [];
    protected $filables =['name_' ,'name_E' , 'address_' => 'required', 'Tel_','notes_'];

    public function Khazna()
    {
        return $this->belongsTo(khazna::class,'branch_id','code_');
    }

}
