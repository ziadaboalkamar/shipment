<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MandoubTaslim extends Model
{
    use \Awobaz\Compoships\Compoships;
    public $timestamps = false;
    protected $primaryKey = 'serial_';
    protected $table = 'mandoub_taslim_tas3irtb';
    protected $guarded = [];
    protected $filables =['mandoub_ID','mo7afaza_id','mantika_id','price_','branch','mandoub_name_','area_name_'];
}
