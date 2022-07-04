<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantikqa extends Model
{
	use \Awobaz\Compoships\Compoships;
    protected $primaryKey = 'serial';
    protected $table = 'edaft_manateq_iraq_tb';
    protected $guarded = [];
    
    public function Tas3ir_3amil()
    {
        return $this->hasOne(Tas3ir_3amil::class,  ['area_name_' ,'city_name_','branch'],['name', 'mo7afza','branch']);
    }
    public function Tas3ir_ta7wel()
    {
        return $this->hasOne(Tas3ir_ta7wel::class,  ['area_name_' ,'city_name_','branch'],['name', 'mo7afza','branch']);
    }
    public function Tas3ir_3amil_5as()
    {
        return $this->hasOne(Tas3ir_3amil_5as::class,  ['area_name_' ,'city_name_'],['name', 'mo7afza']);
    }
    public function Tas3ir_Mandobe_Estilam()
    {
        return $this->hasOne(MandoubEstlam::class,  ['area_name_' ,'city_name_'],['name', 'mo7afza']);
    }
    public function Tas3ir_Mandobe_Taslim()
    {
        return $this->hasOne(MandoubTaslim::class,  ['area_name_' ,'city_name_'],['name', 'mo7afza']);
    }
}
