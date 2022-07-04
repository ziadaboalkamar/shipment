<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class khazna extends Model
{
    
    protected $table = '5azna';
    protected $guarded = [];
    protected $filable = ['name', 'branch_id'];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsToMany(User::class,'5azna_user','user_id','5azna_id');
    }

}
