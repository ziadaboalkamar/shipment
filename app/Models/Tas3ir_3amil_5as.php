<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tas3ir_3amil_5as extends Model
{
	use \Awobaz\Compoships\Compoships;
	public $timestamps = false;

    protected $primaryKey = 'code_';
    protected $table = 'special_prices_tb';
    protected $guarded = [];


}
