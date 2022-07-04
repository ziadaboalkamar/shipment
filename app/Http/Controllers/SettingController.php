<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mohfza;
use App\Models\Mantikqa;
use App\User;
use App\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings  =Setting::getAllSettings()->keyBy('name');
        
        return view('setting.index', compact('settings'));
    }
    
    public function store(Request $request)
    {
        // $rules = Setting::getValidationRules();
        // dd('a');
        // $data = $this->validate($request, $rules);
        // $validSettings = array_keys($rules);
        if($request->shipment_code_ai =='on')
            Setting::add('shipment_code_ai', 1, Setting::getDataType('shipment_code_ai'));
        else
            Setting::add('shipment_code_ai', 0, Setting::getDataType('shipment_code_ai'));
        if($request->remove_mantka =='on')
            Setting::add('remove_mantka', 1, Setting::getDataType('remove_mantka'));
        else
            Setting::add('remove_mantka', 0, Setting::getDataType('remove_mantka'));
        if($request->remove_mo7fza =='on')
            Setting::add('remove_mo7fza', 1, Setting::getDataType('remove_mo7fza'));
        else
            Setting::add('remove_mo7fza', 0, Setting::getDataType('remove_mo7fza'));
        if($request->remove_client_name =='on')
            Setting::add('remove_client_name', 1, Setting::getDataType('remove_client_name'));
        else
            Setting::add('remove_client_name', 0, Setting::getDataType('remove_client_name'));
        if($request->remove_commercial_name =='on')
            Setting::add('remove_commercial_name', 1, Setting::getDataType('remove_commercial_name'));
        else
            Setting::add('remove_commercial_name', 0, Setting::getDataType('remove_commercial_name'));
           
        // dd($request->all());
        $data= $request->except(['_token','shipment_code_ai' ,'remove_mantka','remove_mo7fza','remove_client_name','remove_commercial_name']);
        foreach ($data as $key => $val) {
            //if (in_array($key, $validSettings)) {
                Setting::add($key, $val, Setting::getDataType($key));
            //}
        }
    
        return redirect()->back()->with('status', 'تم الحفظ');
    }
    
  
}
