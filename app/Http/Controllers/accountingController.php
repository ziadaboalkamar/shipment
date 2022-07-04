<?php

namespace App\Http\Controllers;

use App\CustomClass\response;
use App\Http\Controllers\Api\site\Controller;
use App\Models\BranchInfo;
use App\Models\Mantikqa;
use App\Models\Mohfza;
use App\Models\Shipment;
use App\Models\Tempo;
use App\Models\AllUser;
use App\User;
use App\Setting;
use App\Models\Shipment_status;
use App\Models\Commercial_name;
use App\Models\Archive;

use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class accountingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        return view('home');
    }
    public function getAllMo7afazat(){

        return Mohfza::where('branch',auth()->user()->branch)->get();
    }
    public function amilNotMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('notMosadad3amel-accounting')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;
        $shipments = Shipment::select('*')
                                    ->where('status_','!=',8)

                                    ->where('el3amil_elmosadad','')    // مسدد
                                    ->where('branch_', '=', $user->branch)->with(['client']);

        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;
        else
            $shipments = $shipments->where('status_' ,'!=',8) ;

        if(isset($request->code)){
           $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
         }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
         }
       if(isset($request->client_id) && $request->client_id!='الكل'){
        $shipments = $shipments->where('client_name_', '=', $request->client_id);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            }
        $all_shipments = $shipments;

        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

        if(isset( request()->hala_date_from))
            $shipments= $shipments->where('tarikh_el7ala' ,'>=',DATE( request()->hala_date_from) );
        if(isset( request()->hala_date_to))
            $shipments= $shipments->where('tarikh_el7ala' ,'<=',DATE( request()->hala_date_to) );

        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }
        //  dd($all_shipments->skip(0)->limit(40)->get()[20]);
        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $all_shipments->sum('tawsil_coast_');
        $allCount = $all_shipments->count();
        $netCost =  $totalCost-$tawsilCost;
        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'netCost'=>$netCost, 'allCount'=>$allCount];
        $all = $all_shipments->skip($limit*$page)->limit($limit)->get();
        if(isset(request()->lodaMore)){

            return response()->json([
                'status' => 200,
                'data' => $all,
                'message' => 'sucecss',
                'sums'=>$sums
            ], 200);
        }

        // $all->withPath("?mo7afza={$request->mo7afza}&showAll={$request->showAll}
        // &client_id={$request->client_id}");

        $mo7afazat =$this->getAllMo7afazat();
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();


        $clients =User::where('type_','عميل')->where('branch',$user->branch)->get();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        //  dd($status_color);
        $page_title='الشحنات الغير مسددة للعميل';
        if(isset(request()->pdf)){
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                // dd(request()->pdf);
                $all=Shipment::whereIn('code_',$codes);
                // dd($all);


            }
            $all=$all->get();
         $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tawsil_coast_');
        $alSafiCost = $all->sum('total_');

        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company' => 1];
         $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            $mpdf = PDF::loadView('accounting.3amil.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('accounting.3amil.notmosadad',compact('all','mo7afazat','waselOnly','page_title','Commercial_names',
        'clients','status_color','css_prop','sums'));
    }
    public function amilTasdid(Request $request){

        $user=auth()->user();
        // if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch)
        // {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'لم يتم التسديد',
        //     ], 404);
        // }
        $row = DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        ->where('add_shipment_tb_.status_', '!=',8)
        ->where('add_shipment_tb_.status_', '=',7)
        ->where('add_shipment_tb_.el3amil_elmosadad','')
        ->where('branch_', '=', $user->branch)
         ->update(['tarikh_tasdid_el3amil'=>Carbon::now(),
            'add_shipment_tb_.el3amil_elmosadad' =>'مسدد'
            ]);


            return response()->json([
                'status' => 200,
                'count' => $row,
                'message' => 'تم التسديد',
            ], 200);
    }
    public function amilMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('mosadad3amel-accounting')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = 'الفرع الرئيسى';
        // if(isset($request->branch))
        //     $brach_filter= $request->branch;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;
        $shipments = Shipment::select('*')
                                    ->where('status_','!=',8)
                                    ->where('el3amil_elmosadad','مسدد')    // مسدد
                                    ->where('branch_', '=', $user->branch)->with(['client']);
            //saif = shipmnt_cost  - t7weel

        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;
        else
            $shipments = $shipments->where('status_' ,'!=',8) ;

        if(isset($request->code)){
            $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
        }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
        }
        if(isset($request->client_id) && $request->client_id!='الكل'){
            $shipments = $shipments->where('client_name_', '=', $request->client_id);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            }
        $all_shipments = $shipments;

        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

        if(isset( request()->tasdid_date_from))
            $shipments= $shipments->where('tarikh_tasdid_el3amil' ,'>=',DATE( request()->tasdid_date_from) );
        if(isset( request()->tasdid_date_to))
            $shipments= $shipments->where('tarikh_tasdid_el3amil' ,'<=',DATE( request()->tasdid_date_to) );


        if(request()->showAll == 'on'){

            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }
        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $all_shipments->sum('tawsil_coast_');
        $allCount = $all_shipments->count();
        $netCost =  $totalCost-$tawsilCost;
        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'netCost'=>$netCost, 'allCount'=>$allCount];
        $all = $all_shipments->skip($limit*$page)->limit($limit)->get();
        if(isset(request()->lodaMore)){

            return response()->json([
                'status' => 200,
                'data' => $all,
                'message' => 'sucecss',
                'sums'=>$sums
            ], 200);
        }
        $mo7afazat =$this->getAllMo7afazat();
        $clients =User::where('type_','عميل')->where('branch',$user->branch)->get();
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        // dd($counter);
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        $page_title='الشحنات  المسددة للعميل';
        if(isset(request()->pdf)){
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                // dd(request()->pdf);
                $all=Shipment::whereIn('code_',$codes);
                // dd($all);


            }
            $all=$all->get();
            $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tawsil_coast_');
        $alSafiCost = $all->sum('total_');

        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company' => 1];
         $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            $mpdf = PDF::loadView('accounting.3amil.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('accounting.3amil.mosadad',compact('sums','all','mo7afazat','brach_filter','waselOnly',
        'page_title','clients' ,'user','css_prop','status_color','Commercial_names'));
    }
    public function amilcanselTasdid(Request $request){

        $user=auth()->user();
        // if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch)
        // {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'لم يتم التسديد',
        //     ], 404);
        // }
        //case 1
        $row = DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        ->where('add_shipment_tb_.status_', '!=',8)
        ->where('add_shipment_tb_.status_', '=',7)
        ->where('add_shipment_tb_.el3amil_elmosadad','مسدد')
        ->where('branch_', '=', $user->branch)
         ->update(['tarikh_tasdid_el3amil'=>'',
            'add_shipment_tb_.el3amil_elmosadad' =>''
            ]);

            return response()->json([
                'status' => 200,
                'count' => $row,
                'message' => 'تم التسديد',
            ], 200);
    }

    public function mandoubTaslimNotMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('notMosadadMandoubTaslem-accounting')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;
        $shipments = Shipment::select('*')
            ->where('elmandoub_elmosadad_taslim','')
            ->where('Delivery_Delivered_Shipment_ID', '!=',null)    // مسدد
            ->where('Delivery_Delivered_Shipment_ID', '!=',0)    // مسدد
            //->where('branch_', '=', $user->branch)
            ->with(['client']);
            $shipments = $shipments->where('status_' ,'!=',8) ;
            //saif = shipmnt_cost  - t7weel

        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;
        else
            $shipments = $shipments->where('status_' ,'!=',8) ;

        if(isset($request->code)){
            $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
        }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
        }
        if(isset($request->client_id) && $request->client_id!='الكل'){
            $u = User::where('name_',$request->client_id)->first();
            $shipments = $shipments->where('Delivery_Delivered_Shipment_ID', '=', $u->code_);
        }
        // if(isset($request->Commercial_name)){
        //     $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
        //     }
        $all_shipments = $shipments;

        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

        if(isset( request()->hala_date_from))
            $shipments= $shipments->where('tarikh_el7ala' ,'>=',DATE( request()->hala_date_from) );
        if(isset( request()->hala_date_to))
            $shipments= $shipments->where('tarikh_el7ala' ,'<=',DATE( request()->hala_date_to) );


        if(request()->showAll == 'on'){

            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }
        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $all_shipments->sum('tas3ir_mandoub_taslim');
        $allCount = $all_shipments->count();
        $netCost =  $totalCost-$tawsilCost;
        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'netCost'=>$netCost, 'allCount'=>$allCount];
        $all = $all_shipments->skip($limit*$page)->limit($limit)->get();
        if(isset(request()->lodaMore)){

            return response()->json([
                'status' => 200,
                'data' => $all,
                'message' => 'sucecss',
                'sums'=>$sums
            ], 200);
        }
        $mo7afazat =$this->getAllMo7afazat();
        $clients =User::where('type_','مندوب تسليم')->where('branch',$user->branch)->get();
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        // dd($counter);
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='الشحنات الغير مسددة لمندوب التسليم';
        if(isset(request()->pdf)){
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                // dd(request()->pdf);
                $all=Shipment::whereIn('code_',$codes);
                // dd($all);


            }
            $all=$all->get();
         $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tas3ir_mandoub_taslim');
        $alSafiCost = $totalCost - $tawsilCost;


        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'mandoub' => 1,];
         $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            $mpdf = PDF::loadView('accounting.mandoubtaslim.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('accounting.mandoubtaslim.notmosadad',compact('sums','all','mo7afazat','waselOnly','page_title',
        'clients','status_color','css_prop','Commercial_names'));
    }
    public function mandoubTaslimTasdid(Request $request){

        $user=auth()->user();
        // if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch && 0)
        // {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'لم يتم التسديد',
        //     ], 404);
        // }
        //case 1
        $row = DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        ->where('add_shipment_tb_.elmandoub_elmosadad_taslim','')
        ->where('add_shipment_tb_.status_', '=',7)
        ->where('Ship_area_', '=', $user->branch)
         ->update(['tarikh_tasdid_mandoub_eltaslim'=>Carbon::now(),
            'add_shipment_tb_.elmandoub_elmosadad_taslim' =>'مسدد'
            ]);

            return response()->json([
                'status' => 200,
                'count' => $row,
                'message' => 'تم التسديد',
            ], 200);
    }
    public function mandoubtaslimMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('mosadadMandoubTaslem-accounting')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = 'الفرع الرئيسى';
        if(isset($request->branch))
            $brach_filter= $request->branch;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;
        $shipments = Shipment::select('*')
            ->where('elmandoub_elmosadad_taslim','مسدد')
            ->where('Delivery_Delivered_Shipment_ID', '!=',null)    // مسدد
            ->where('Delivery_Delivered_Shipment_ID', '!=',0)
            //->where('branch_', '=', $user->branch)
            ->with(['client']);
            $shipments = $shipments->where('status_' ,'!=',8) ;
        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;
        else
        $shipments = $shipments->where('status_' ,'!=',8) ;
        if(isset($request->code)){
            $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
        }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
        }
        if(isset($request->client_id) && $request->client_id!='الكل'){
            $u = User::where('name_',$request->client_id)->first();
            $shipments = $shipments->where('Delivery_Delivered_Shipment_ID', '=', $u->code_);
        }
        // if(isset($request->Commercial_name)){
        //     $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
        //     }
        $all_shipments = $shipments;

        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

        if(isset( request()->tasdid_date_from))
            $shipments= $shipments->where('tarikh_tasdid_mandoub_eltaslim' ,'>=',DATE( request()->tasdid_date_from) );
        if(isset( request()->tasdid_date_to))
            $shipments= $shipments->where('tarikh_tasdid_mandoub_eltaslim' ,'<=',DATE( request()->tasdid_date_to) );

            if(request()->showAll == 'on'){

            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }
        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $all_shipments->sum('tas3ir_mandoub_taslim');
        $allCount = $all_shipments->count();
        $netCost =  $totalCost-$tawsilCost;
        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'netCost'=>$netCost, 'allCount'=>$allCount];
        $all = $all_shipments->skip($limit*$page)->limit($limit)->get();
        if(isset(request()->lodaMore)){

            return response()->json([
                'status' => 200,
                'data' => $all,
                'message' => 'sucecss',
                'sums'=>$sums
            ], 200);
        }
        $mo7afazat =$this->getAllMo7afazat();
        $clients =User::where('type_','مندوب تسليم')->where('branch',$user->branch)->get();
        $filtered_clients = User::where('type_','مندوب تسليم')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        // dd($counter);
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='الشحنات  المسددة لمندوب التسليم';
        if(isset(request()->pdf)){
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                // dd(request()->pdf);
                $all=Shipment::whereIn('code_',$codes);
                // dd($all);


            }
            $all=$all->get();
         $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tas3ir_mandoub_taslim');
            $alSafiCost = $totalCost - $tawsilCost;

        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'mandoub' => 1];
         $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            $mpdf = PDF::loadView('accounting.mandoubtaslim.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('accounting.mandoubtaslim.mosadad',compact('sums','all','mo7afazat','waselOnly','page_title',
        'clients','status_color', 'css_prop','Commercial_names'));
    }
    public function mandoubtaslimCanselTasdid(Request $request){

        $user=auth()->user();
        if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch)
        {
            return response()->json([
                'status' => 404,
                'message' => 'لم يتم التسديد',
            ], 404);
        }
        //case 1
        $row =  DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        //->where('add_shipment_tb_.status_', '!=',8)
        ->where('add_shipment_tb_.status_', '=',7)
        ->where('add_shipment_tb_.elmandoub_elmosadad_taslim','مسدد')
        ->where('Ship_area_', '=', $user->branch)
         ->update(['tarikh_tasdid_mandoub_eltaslim'=>'',
            'add_shipment_tb_.elmandoub_elmosadad_taslim' =>''
            ]);

            return response()->json([
                'status' => 200,
                'count' => $row,
                'message' => 'تم التسديد',
            ], 200);
    }

    public function mandoubestlamNotMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('notMosadadMandoubEstlam-accounting')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = 'الفرع الرئيسى';
        if(isset($request->branch))
            $brach_filter= $request->branch;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;
        $shipments = Shipment::select('*')
            ->where('elmandoub_elmosadad_estlam','')
            ->where('Delivery_take_shipment_ID', '!=',null)    // مسدد
            ->where('Delivery_take_shipment_ID', '!=',0)
            ->where('Ship_area_', '=', $user->branch)->with(['client']);
            $shipments = $shipments->where('status_' ,'!=',8) ;
            //saif = shipmnt_cost  - t7weel

        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;
        else
            $shipments = $shipments->where('status_' ,'!=',8) ;

            if(isset($request->code)){
                $shipments = $shipments->where('code_', '=', $request->code);
            }
            if(isset($request->reciver_phone)){
                $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
            }

            if(isset($request->mo7afza)){
                $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
            }
            if(isset($request->client_id) && $request->client_id!='الكل'){
                $u = User::where('name_',$request->client_id)->first();
                $shipments = $shipments->where('Delivery_take_shipment_ID', '=', $u->code_);
            }
            // if(isset($request->Commercial_name)){
            //     $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            //     }
            $all_shipments = $shipments;

            if(isset( request()->date_from))
                $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
            if(isset( request()->date_to))
                $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

            if(isset( request()->hala_date_from))
                $shipments= $shipments->where('tarikh_el7ala' ,'>=',DATE( request()->hala_date_from) );
            if(isset( request()->hala_date_to))
                 $shipments= $shipments->where('tarikh_el7ala' ,'<=',DATE( request()->hala_date_to) );

        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }

        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $all_shipments->sum('tas3ir_mandoub_estlam');
        $allCount = $all_shipments->count();
        $netCost =  $totalCost-$tawsilCost;
        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'netCost'=>$netCost, 'allCount'=>$allCount];
        $all = $all_shipments->skip($limit*$page)->limit($limit)->get();
        if(isset(request()->lodaMore)){

            return response()->json([
                'status' => 200,
                'data' => $all,
                'message' => 'sucecss',
                'sums'=>$sums
            ], 200);
        }
        $mo7afazat =$this->getAllMo7afazat();
        $clients =User::where('type_','مندوب استلام')->where('branch',$user->branch)->get();
        $filtered_clients = User::where('type_','مندوب استلام')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        // dd($counter);
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='الشحنات الغير مسددة لمندوب الاستلام';
        if(isset(request()->pdf)){
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                // dd(request()->pdf);
                $all=Shipment::whereIn('code_',$codes);
                // dd($all);


            }
            $all=$all->get();
                $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tas3ir_mandoub_estlam');
        $alSafiCost = $all->sum('total_');

        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'mandoub' => 2];
         $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            $mpdf = PDF::loadView('accounting.mandoubestlam.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('accounting.mandoubestlam.notmosadad',compact('sums','all','mo7afazat','waselOnly','page_title',
        'clients','status_color' ,'css_prop','Commercial_names'));
    }
    public function mandoubestlamTasdid(Request $request){
        $user=auth()->user();
        // if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch)
        // {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'لم يتم التسديد',
        //     ], 404);
        // }
        //case 1
        $row = DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        //->where('add_shipment_tb_.status_', '!=',8)
        ->where('add_shipment_tb_.status_', '=',7)
        ->where('add_shipment_tb_.elmandoub_elmosadad_estlam','')
        ->where('Ship_area_', '=', $user->branch)
         ->update(['tarikh_tasdid_mandoub_elestlam'=>Carbon::now(),
            'add_shipment_tb_.elmandoub_elmosadad_estlam' =>'مسدد'
            ]);

            return response()->json([
                'status' => 200,
                'count' => $row,
                'message' => 'تم التسديد',
            ], 200);
    }
    public function mandoubestlamMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('mosadadMandoubEstlam-accounting')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = 'الفرع الرئيسى';
        if(isset($request->branch))
            $brach_filter= $request->branch;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;
        $shipments = Shipment::select('*')
            ->where('elmandoub_elmosadad_estlam','مسدد')
            ->where('Delivery_take_shipment_ID', '!=',null)
            ->where('Delivery_take_shipment_ID', '!=',0)
            ->where('Ship_area_', '=', $user->branch)->with(['client']);
            $shipments = $shipments->where('status_' ,'!=',8) ;
            //saif = shipmnt_cost  - t7weel

        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;
        else
            $shipments = $shipments->where('status_' ,'!=',8) ;

        if(isset($request->code)){
            $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
        }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
        }
        if(isset($request->client_id) && $request->client_id!='الكل'){
            $u = User::where('name_',$request->client_id)->first();
            $shipments = $shipments->where('Delivery_take_shipment_ID', '=', $u->code_);
        }
        // if(isset($request->Commercial_name)){
        //     $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
        //     }


        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

        if(isset( request()->tasdid_date_from))
            $shipments= $shipments->where('tarikh_tasdid_mandoub_elestlam' ,'>=',DATE( request()->tasdid_date_from) );
        if(isset( request()->tasdid_date_to))
                $shipments= $shipments->where('tarikh_tasdid_mandoub_elestlam' ,'<=',DATE( request()->tasdid_date_to) );

            $all_shipments = $shipments;
        if(request()->showAll == 'on'){

            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }
        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $all_shipments->sum('tas3ir_mandoub_estlam');
        $allCount = $all_shipments->count();
        $netCost =  $totalCost-$tawsilCost;
        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'netCost'=>$netCost, 'allCount'=>$allCount];
        $all = $all_shipments->skip($limit*$page)->limit($limit)->get();

        if(isset(request()->lodaMore)){

            return response()->json([
                'status' => 200,
                'data' => $all,
                'message' => 'sucecss',
                'sums'=>$sums
            ], 200);
        }
        $mo7afazat =$this->getAllMo7afazat();
        $clients =User::where('type_','مندوب استلام')->where('branch',$user->branch)->get();
        $filtered_clients = User::where('type_','مندوب استلام')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        // dd($counter);
        $setting =Setting::all()->keyBy('name')->pluck('val','name');
        $status_color=['status_6_color' => $setting['status_6_color'],'status_1_color' => $setting['status_1_color'],
        'status_2_color' => $setting['status_2_color'],'status_3_color' => $setting['status_3_color']
        ,'status_4_color' => $setting['status_4_color'],'status_7_color' => $setting['status_7_color'],
        'status_8_color' => $setting['status_8_color'],'status_9_color' => $setting['status_9_color']];
        $css_prop = $setting['status_css_prop'];
         //dd();
        $page_title='الشحنات  المسددة لمندوب الاستلام';
        if(isset(request()->pdf)){
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                // dd(request()->pdf);
                $all=Shipment::whereIn('code_',$codes);
                // dd($all);


            }
            $all=$all->get();
        $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tas3ir_mandoub_estlam');
        $alSafiCost = $all->sum('total_');

        $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'mandoub' => 2];
         $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            $mpdf = PDF::loadView('accounting.mandoubestlam.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('accounting.mandoubestlam.mosadad',compact('sums','all','mo7afazat','waselOnly','page_title',
        'clients','css_prop' ,'status_color' ,'Commercial_names'));
    }
    public function mandoubestlamcanselTasdid(Request $request){

        $user=auth()->user();
        // if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch)
        // {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'لم يتم التسديد',
        //     ], 404);
        // }
        //case 1
        $row = DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        //->where('add_shipment_tb_.status_', '!=',8)
        ->where('add_shipment_tb_.status_', '=',7)
        ->where('add_shipment_tb_.elmandoub_elmosadad_estlam','مسدد')
        ->where('Ship_area_', '=', $user->branch)
         ->update(['tarikh_tasdid_mandoub_elestlam'=>'',
            'add_shipment_tb_.elmandoub_elmosadad_estlam' =>''
            ]);

            return response()->json([
                'status' => 200,
                'count' => $row,
                'message' => 'تم التسديد',
            ], 200);
    }
     //end acc


     public function loadMore(Request $request)
    {

        $user=auth()->user();
        $limit=Setting::get('items_per_page');
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;
        $shipments = Shipment::select('*')
                                    ->where('status_','!=',8)

                                    ->where('el3amil_elmosadad','')    // مسدد
                                    ->where('branch_', '=', $user->branch)->with(['client']);

        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;
        else
            $shipments = $shipments->where('status_' ,'!=',8) ;

        if(isset($request->code)){
           $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
         }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
         }
       if(isset($request->client_id)){
        $shipments = $shipments->where('client_name_', '=', $request->client_id);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            }
        $all_shipments = $shipments;

        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

        if(isset( request()->hala_date_from))
            $shipments= $shipments->where('tarikh_el7ala' ,'>=',DATE( request()->hala_date_from) );
        if(isset( request()->hala_date_to))
            $shipments= $shipments->where('tarikh_el7ala' ,'<=',DATE( request()->hala_date_to) );

        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }
        $all = $all_shipments->skip($limit*request()->page)->limit($limit)->get();
        return response()->json([
            'status' => 200,
            'data' => $all,
            'message' => 'sucecss',
        ], 200);

        
    }
}
