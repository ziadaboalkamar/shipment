<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MandoubEstlam extends Model
{
    use \Awobaz\Compoships\Compoships;
    public $timestamps = false;
    protected $primaryKey = 'serial_';
    protected $table = 'mandoub_estlam_tas3irtb';
    protected $guarded = [];
    protected $filables =['area_name_','price_','branch','mandoub_name_','city_name_','mandoub_ID'];

}
