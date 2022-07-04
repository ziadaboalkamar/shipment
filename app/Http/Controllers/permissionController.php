<?php

namespace App\Http\Controllers;

use App\Models\BranchInfo;
use App\User;
use Illuminate\Http\Request;
use App\Role;
use App\Permission;

class permissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $user=auth()->user();
        if(!$user->isAbleTo('permitions-setting')){
            return abort(403); 
        }
        
        $selected_user=-1;
        $permisssions=-1;
        $users=[];
        if(isset(request()->user_id)){
            $selected_user=user::where('code_',request()->user_id)->where('type_','موظف')->where('status_',1)->first();
            $permisssions_1 = Permission::where('sort_no',1)->get();
            $permisssions_2 = Permission::where('sort_no',2)->get();
            $permisssions_3 = Permission::where('sort_no',3)->get();
            $permisssions_4 = Permission::where('sort_no',4)->get();
            $permisssions_5 = Permission::where('sort_no',5)->get();
            $permisssions_6 = Permission::where('sort_no',6)->get();
            $permisssions_7 = Permission::where('sort_no',7)->get();
            $permisssions=['صلاحيات الشحنات'=>$permisssions_1,
            'صلاحيات الفروع'=>$permisssions_2,
            'صلاحيات الحسابات'=>$permisssions_3,
            'صلاحيات التعريفات'=>$permisssions_4,
            'صلاحيات التسعر'=>$permisssions_5,
            'صلاحيات المستخدمين'=>$permisssions_6,
            'صلاحيات الاعدادات'=>$permisssions_7];
        }else{
            $users=user::where('type_','موظف')->where('status_',1)->get();
        }
        $page_title='الصلاحيات';
        return view('permissions.index', compact('users','permisssions','selected_user','page_title') );
    }

    public function store(Request $request){
        $selected_user = user::where('code_',request()->user_id)->where('type_','موظف')->where('status_',1)->first();
        //  dd( $selected_user);
        //if(!isset($selected_user))  return abort(404); 
        $perms =$request->except(['user_id','_token']);
        $perms = (array_keys($perms));
        $selected_user->syncPermissions($perms);
        return back();
        //dd($request->all());
    }
}
