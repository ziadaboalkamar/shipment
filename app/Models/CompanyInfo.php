<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'serial_';
    protected $table = 'company_info_tb';
    protected $guarded = [];
    protected $filables =['name_' ,'name_E' , 'address_' => 'required','Tel_','notes_','branch_','serial_','image_data'];
}
