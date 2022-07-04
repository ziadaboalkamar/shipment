<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddClientsMainComp extends Model
{

    
    protected $table = 'add_clients_main_comp_tb';
    public $timestamps = false;
    protected $filables =['code_','name_','USERNAME','PASSWORD','ID_','address_','Branch_name','Special_prices'];

    protected $primaryKey = 'code_';
    protected $guarded = [];
}
