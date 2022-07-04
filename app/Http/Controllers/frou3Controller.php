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
use App\Models\Shipment_status;
use App\Models\Commercial_name;
use App\Models\Archive;
use App\Setting;
use App\User;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class frou3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getAllMo7afazat(){

        return Mohfza::where('branch',auth()->user()->branch)->get();
    }
    public function export(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('export-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = '';
        if(isset($request->branch_)  && $request->branch_!='الكل')
            $brach_filter= $request->branch_;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        //if(isset(request()->limit ))   $limit =request()->limit;


        if( $brach_filter != '')
        {
            $shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 = '{$brach_filter}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 = '{$brach_filter}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
            $shipments = $shipments->where(function ($query) use($request,$user,$brach_filter){
                $query->where(function ($query) use($request,$user,$brach_filter){
                    $query->where('branch_', '=', $user->branch)
                    ->where('transfere_1', $brach_filter)
                    ->where('elfar3_elmosadad_mno','');

                    })
                    ->orWhere(function ($query) use($request,$user,$brach_filter){
                        $query->where('transfere_1', '=', $user->branch)
                        ->where('transfere_2',$brach_filter )
                        ->where('elfar3_elmosadad_mno_2','');
                    });
            });
        }
            else
            {$shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 !=  '' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 != '' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
                $shipments = $shipments->where(function ($query) use($request,$user,$brach_filter){
                    $query->where(function ($query) use($request,$user,$brach_filter){
                        $query->where('branch_', '=', $user->branch)
                        ->where('transfere_1', '!=', '')
                        ->where('elfar3_elmosadad_mno','=','');

                        })
                        ->orWhere(function ($query) use($request,$user,$brach_filter){
                            $query->where('transfere_1', '=', $user->branch)
                            ->where('transfere_2', '!=', '')
                            ->where('elfar3_elmosadad_mno_2','=' ,'');
                        });
                });
            }
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
       if(isset($request->client_id)){
        $shipments = $shipments->where('client_name_', '=', $request->client_id);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            }


        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

            $all_shipments = $shipments;
            $ta7weel=0;
            foreach($all_shipments->get() as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
            // dd($ta7weel);
        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }

        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $ta7weel;
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
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        $branches =BranchInfo::all();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='الشحنات الصادرة الي الفرع';
        if(isset(request()->pdf)){

            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);
                if( $brach_filter != '')
                {
                    $all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 = '{$brach_filter}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 = '{$brach_filter}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
                }
                else
                {     $all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 !=  '' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 != '' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));

                }

            }

            $all=$all->get();
            $ta7weel=0;
            foreach($all as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
            $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $ta7weel;
            $printPage='frou3.accounting.print';
            $alSafiCost = $totalCost - $tawsilCost;
            $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];


            $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            //return view('shipments.print' ,compact('all','title'));
            $mpdf = PDF::loadView($printPage,$data);
            return $mpdf->stream('document.pdf');
        }
        return view('frou3.export',compact('all','branches','mo7afazat','brach_filter','waselOnly','page_title',
     'css_prop','status_color' ,'sums'));
    }
    public function import(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('import-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = '';
        if(isset($request->branch_)  && $request->branch_!='الكل')
            $brach_filter= $request->branch_;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        //if(isset(request()->limit ))   $limit =request()->limit;


        if( $brach_filter != '')
        {
            $shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$brach_filter }' and  transfere_1 = '{$user->branch}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$brach_filter }' and  transfere_2 = '{$user->branch}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
            $shipments = $shipments->where(function ($query) use($request,$user,$brach_filter){
                $query->where(function ($query) use($request,$user,$brach_filter){
                    $query->where('branch_', '=', $brach_filter)
                    ->where('transfere_1', $user->branch)
                    ->where('elfar3_elmosadad_mno','');

                    })
                    ->orWhere(function ($query) use($request,$user,$brach_filter){
                        $query->where('transfere_1', '=', $brach_filter)
                        ->where('transfere_2',$user->branch )
                        ->where('elfar3_elmosadad_mno_2','');
                    });
            });
        }
            else
            {$shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ != '' and  transfere_1 =  '{$user->branch}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 != '' and  transfere_2 = '{$user->branch}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
                $shipments = $shipments->where(function ($query) use($request,$user,$brach_filter){
                    $query->where(function ($query) use($request,$user,$brach_filter){
                        $query->where('branch_', '!=', '')
                        ->where('transfere_1', '=', $user->branch)
                        ->where('elfar3_elmosadad_mno','=','');

                        })
                        ->orWhere(function ($query) use($request,$user,$brach_filter){
                            $query->where('transfere_1', '!=', '')
                            ->where('transfere_2', '=', $user->branch)
                            ->where('elfar3_elmosadad_mno_2','=' ,'');
                        });
                });
            }
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
       if(isset($request->client_id)){
        $shipments = $shipments->where('client_name_', '=', $request->client_id);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            }


        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

            $all_shipments = $shipments;
            $ta7weel=0;
            foreach($all_shipments->get() as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
            // dd($ta7weel);
        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }

        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $ta7weel;
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
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        $branches =BranchInfo::all();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='الشحنات الواردة من الفرع';
        if(isset(request()->pdf)){

            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);
                if( $brach_filter != '')
                {
                    $all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                    WHEN ( branch_ = '{$brach_filter }' and  transfere_1 = '{$user->branch}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                    WHEN ( transfere_1 = '{$brach_filter }' and  transfere_2 = '{$user->branch}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                    END) AS t7weel_cost"));
                }
                else
                {     $all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                    WHEN ( branch_ != '' and  transfere_1 =  '{$user->branch}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                    WHEN ( transfere_1 != '' and  transfere_2 = '{$user->branch}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                    END) AS t7weel_cost"));

                }

            }

            $all=$all->get();
            $ta7weel=0;
            foreach($all as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
            $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $ta7weel;
            $printPage='frou3.accounting.print';
            $alSafiCost = $totalCost - $tawsilCost;
            $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];


            $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            //return view('shipments.print' ,compact('all','title'));
            $mpdf = PDF::loadView($printPage,$data);
            return $mpdf->stream('document.pdf');
        }
        return view('frou3.import',compact('all','branches','mo7afazat','brach_filter','waselOnly','page_title',
     'css_prop','status_color' ,'sums'));
    }
    //t7wel sho7nat
    public function frou3_t7wel_sho7nat_manual(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('t7welSho7natManual-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = '';
        if(isset($request->branch_)  && $request->branch_!='الكل')
            $brach_filter= $request->branch_;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;


            $shipments = Shipment::select('*');
            $shipments = $shipments->where('Ship_area_', '=', $user->branch)
                    ->where('transfere_2','')
                    ->where('status_',1);



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


        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

            $all_shipments = $shipments;
            $ta7weel=0;
            foreach($all_shipments->get() as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
            // dd($ta7weel);
        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }

        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $ta7weel;
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
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        $branches =BranchInfo::all();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='تحويل الشحنات يدويا الى فرع';
        return view('frou3.t7wel_sho7nat.manual',compact('all','branches','mo7afazat','brach_filter','waselOnly','page_title',
     'css_prop','status_color' ,'sums'));
    }
    public function frou3_t7wel_sho7nat_manual_save(Request $request){
        // dd($request->all());
        return ($this->frou3_t7wel_sho7nat_qr_save( $request));
    }
    public function frou3_t7wel_sho7nat_qr(Request $request){
        $user=auth()->user();
        if(!$user->isAbleTo('t7welsho7natQr-frou3')){
            return abort(403);
        }
        $branches=DB::table('branch_info_tb')
        ->select('serial_','name_')
        ->get();
        $page_title='تحويل الشحنات الى فرع باستخدام qr';
        return view('frou3.t7wel_sho7nat.qr',compact('branches','page_title'));
    }
    public function frou3_t7wel_sho7nat_qr_save(Request $request){
        $user = $user = auth()->user();

        $status=array(1);
        if(isset(request()->pdf)){
            $codes= explode(',',$request->codes);
            $all =  DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $codes)
            ->whereIN('add_shipment_tb_.status_',$status)
            ->get();
            $totalCost = $all->sum('shipment_coast_');


            $tawsilCost = $tawsilCost = $all->sum('tawsil_coast_');
            $alSafiCost = $totalCost - $tawsilCost ;

            $printPage='accounting.mandoubtaslim.print';
                $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];
            $data = [
                'all'=>$all,
                'title'=>'تحويل الشحنات بين الفروع باستخدام qr',
                'sum'=>$sums
            ];

            $mpdf = PDF::loadView('shipments.print',$data);
            return $mpdf->stream('document.pdf');
        }
        $branch=DB::table('branch_info_tb')
        ->where('serial_',$request->status)
        ->select('serial_','name_')->first();
        if($user->branch == $branch->name_ ){
            return response()->json([
                'status' => 403,
                'msg' => 'لا يمكن تحويل الشحنه الى نفس الفرع',
            ], 403);
        }
        $t1 =DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_1', '')
            ->where('add_shipment_tb_.status_', 1)
            ->where('add_shipment_tb_.branch_', $user->branch)->get();

        $t2 = DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_1','!=', '')
            ->where('add_shipment_tb_.status_', 1)->get();


         $q1 =DB::table('add_shipment_tb_')
         ->whereIn('add_shipment_tb_.code_', $request->code)
         ->where('add_shipment_tb_.transfere_1', '')
         ->where('add_shipment_tb_.status_', 1)
         ->where('transfer_prices_main_tb.branch', $user->branch)
        ->join('transfer_prices_main_tb', function($join){
            $join->on('transfer_prices_main_tb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
            $join->on('transfer_prices_main_tb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id');
         });

            $q2 = DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_1','!=', '')
            ->where('add_shipment_tb_.status_', 1)
            ->where('transfer_prices_main_tb.branch', $user->branch)
           ->join('transfer_prices_main_tb', function($join){
               $join->on('transfer_prices_main_tb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
               $join->on('transfer_prices_main_tb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id');
            });
            if(isset(request()->pdf)){

                $data = [
                    'all'=>$q2->union($q1)->get(),
                    'title'=>'تحويل الشحنات بين الفروع باستخدام qr'
                ];

                $mpdf = PDF::loadView('shipments.print',$data);
                return $mpdf->stream('document.pdf');
            }
            Tempo::insert(json_decode(json_encode($t2), true));
        Tempo::insert(json_decode(json_encode($t1), true));
            $q2 =  $q2->update(['add_shipment_tb_.transfere_2'=>$branch->name_,
            'add_shipment_tb_.transfer_coast_2' =>DB::raw("`transfer_prices_main_tb`.`price_`"),
            'TRANSFERE_ACCEPT_REFUSE'=>2,
            'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
            'Ship_area_'=>$branch->name_
            ]);



            $q1=$q1->update(['add_shipment_tb_.transfere_1'=>$branch->name_,
            'add_shipment_tb_.transfer_coast_1' =>DB::raw("`transfer_prices_main_tb`.`price_`") ,
            'TRANSFERE_ACCEPT_REFUSE'=>2,
            'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
            'Ship_area_'=>$branch->name_]);


              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
                'count'  => $q1+$q2,
            ], 200);
        }



    public function accept_frou3_t7wel(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('acceptT7welsho7natQr-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;

         $shipments = Shipment::with(['Branch_user' => function ($query) {
             $query->select('code_','phone_');
         }])
         ->where('Ship_area_', '=', $user->branch)
         ->where('TRANSFERE_ACCEPT_REFUSE',2);

         if(isset($request->branch)){
             $shipments = $shipments->where(function ($query) use($request){
                $query->where('branch_', '=', $request->branch)
                      ->orWhere('transfere_1', '=', $request->branch);
            });
         }
         if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
        }

         $all_shipments = $shipments;

         if($user->type_ =='عميل'){

             if(isset( request()->date_from))
                 $all_shipments= $all_shipments->where('date_' ,'>=',DATE($request->date_from) );
             if(isset( request()->date_to))
                 $all_shipments= $all_shipments->where('date_' ,'<=' ,DATE($request->date_to) );

         }else{
             if(isset( request()->date_from))
                 $all_shipments= $all_shipments->where('tarikh_el7ala' ,'>=',DATE( request()->date_from) );
             if(isset( request()->date_to))
                 $all_shipments= $all_shipments->where('tarikh_el7ala' ,'<=',DATE( request()->date_to) );

         }

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

        $page_title='الموافقة على تحويل رواجع الفروع';
       $branches =BranchInfo::all();
       $mo7afazat =Mohfza::all();
       if(isset(request()->pdf)){
        //return view('shipments.print' , compact('all'));
        $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $all->sum('tawsil_coast_');
            $printPage='shipments.print';

            $alSafiCost = $all->sum('total_');

                $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];


            $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
        $mpdf = PDF::loadView('shipments.print',$data);
        return $mpdf->stream('document.pdf');
    }
    $page_title='الموافقة على تحويل رواجع الفروع';
         return view('frou3.t7wel_sho7nat.accept',compact('all','branches','mo7afazat','page_title'));

    }
    public function accept_frou3_t7wel_save(Request $request){
        $user=auth()->user();
        $t2 = Tempo::where('code_', $request->code)
        ->first();

        $shipment=Shipment::where('code_',$request->code)
        ->where('TRANSFERE_ACCEPT_REFUSE',2)
        ->where('Ship_area_', '=', $user->branch);


        if($request->type=='accept'){

            $shipment=$shipment->first();
            if(!isset($shipment)) return ;
            $shipment->TRANSFERE_ACCEPT_REFUSE =1;
            $shipment->save();
        }
        elseif($request->type=='cancel'){
            $shipment->first()->delete();
            if(isset($t2))
            Shipment::insert(json_decode(json_encode($t2), true));
            else{
                return response()->json([
                    'status' => 404,
                ], 404);
            }
            
        }
        if(isset($t2))
         $t2->delete();
    }
    public function accept_frou3_t7wel_qr_save(Request $request){
        //dd($request->all());
        $user = auth()->user();
        DB::table('add_shipment_tb_')
        ->whereIN('code_',$request->code)
        ->where('TRANSFERE_ACCEPT_REFUSE',2)
        ->where('Ship_area_', '=', $user->branch)
        ->update([
            'TRANSFERE_ACCEPT_REFUSE' =>1,

        ]);

        DB::table('add_shipment_tempo')
        ->whereIN('code_',$request->code)
        ->delete();
        return response()->json([
            'status' => 200,
            'message' => 'تم الموافقة',
        ], 200);

    }
    public function accept_t7wel_get(Request $request)
    {
        $user = auth()->user();
        $shipment =Shipment::where('code_',$request->code)
        ->where('Ship_area_', '=', $user->branch)
        ->where('TRANSFERE_ACCEPT_REFUSE',2);
        $shipment= $shipment->with(['Shipment_status'])
        ->get();

        if($shipment->count()== 1)
        {
            $status=200;
            $data=$shipment;
        }
        else
        {
            $status=404;
            $data=[];
        }
        return response()->json([
            'status' => $status,
            'data' => $data,
        ], $status);
    }
    //end t7weel sho7nat

    //rag3
    public function frou3_t7wel_rag3_manual(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('t7welRag3Manual-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = '';
        if(isset($request->branch_)  && $request->branch_!='الكل')
            $brach_filter= $request->branch_;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;


            $shipments = Shipment::select('*');
            $shipments = $shipments->where('Ship_area_', '=', $user->branch)
                    ->where('transfere_1','!=','')
                    ->where('status_',9);



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


        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

            $all_shipments = $shipments;
            $ta7weel=0;
            foreach($all_shipments->get() as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
            // dd($ta7weel);
        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }

        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $ta7weel;
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
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        $branches =BranchInfo::all();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='تحويل الرواجع يدويا الى فرع';
        return view('frou3.t7wel_rag3.manual',compact('all','branches','mo7afazat','brach_filter','waselOnly','page_title',
     'css_prop','status_color' ,'sums'));
    }
    public function frou3_t7wel_rag3_manual_save(Request $request){
        // dd($request->all());
        return ($this->frou3_t7wel_rag3_qr_save( $request));
    }
    public function frou3_t7wel_rag3_qr(Request $request){
        $user=auth()->user();
        if(!$user->isAbleTo('t7welRag3Qr-frou3')){
            return abort(403);
        }
        $branches=DB::table('branch_info_tb')
        ->select('serial_','name_')
        ->get();
        $page_title=' تحويل رواجع الفروع باستخدام qr';
        return view('frou3.t7wel_rag3.qr',compact('branches','page_title'));
    }
    public function frou3_t7wel_rag3_qr_save(Request $request){
        $status=array(1);

        if(isset(request()->pdf)){
            $codes= explode(',',$request->codes);
            $all =  DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $codes)
            ->whereIN('add_shipment_tb_.status_',$status)
            ->get();
            $totalCost = $all->sum('shipment_coast_');


            $tawsilCost = $tawsilCost = $all->sum('tawsil_coast_');
            $alSafiCost = $totalCost - $tawsilCost ;

            $printPage='accounting.mandoubtaslim.print';
                $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];
            $data = [
                'all'=>$all,
                'title'=>'تحويل الراجع بين الفروع باستخدام qr',
                'sum'=>$sums
            ];

            $mpdf = PDF::loadView('shipments.print',$data);
            return $mpdf->stream('document.pdf');
        }
        $branch=DB::table('branch_info_tb')
        ->where('serial_',$request->status)
        ->select('serial_','name_')->first();

        $user = $user = auth()->user();
        if($user->branch == $branch->name_ ){
            return response()->json([
                'status' => 403,
                'msg' => 'لا يمكن تحويل الشحنه الى نفس الفرع',
            ], 403);
        }

            $t1 =DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_2' ,'')
            ->where('add_shipment_tb_.transfere_1' ,'!=','')
            ->where('add_shipment_tb_.status_', 9)
            ->where('add_shipment_tb_.Ship_area_', $user->branch)->get();

            $t2 = DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_2','!=' ,'')
            ->where('add_shipment_tb_.status_', 9)
            ->where('add_shipment_tb_.Ship_area_', $user->branch)->get();

            $u1 =  DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_2' ,'')
            ->where('add_shipment_tb_.transfere_1' ,'!=','')
            ->where('add_shipment_tb_.status_', 9)
            ->where('add_shipment_tb_.Ship_area_', $user->branch);
            $u2 =   DB::table('add_shipment_tb_')
               ->whereIn('add_shipment_tb_.code_', $request->code)
               ->where('add_shipment_tb_.transfere_2','!=' ,'')
               ->where('add_shipment_tb_.status_', 9)
               ->where('add_shipment_tb_.Ship_area_', $user->branch);
            if(isset(request()->pdf)){

                $data = [
                    'all'=>$u2->union($u1)->get(),
                    'title'=>'تحويل الشحنات بين الفروع باستخدام qr'
                ];

                $mpdf = PDF::loadView('shipments.print',$data);
                return $mpdf->stream('document.pdf');
            }

            Tempo::insert(json_decode(json_encode($t2), true));
            Tempo::insert(json_decode(json_encode($t1), true));



            $u1=$u1->update(['add_shipment_tb_.transfere_1'=>'',
                'add_shipment_tb_.transfer_coast_1' =>'',
                'add_shipment_tb_.TRANSFERE_ACCEPT_REFUSE'=>3,
                'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
                'Ship_area_'=>$branch->name_ ]);



               $u2=$u2->update(['add_shipment_tb_.transfere_2'=>'',
                  'add_shipment_tb_.transfer_coast_2' =>'',
                  'add_shipment_tb_.TRANSFERE_ACCEPT_REFUSE'=>3,
                  'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
                  'Ship_area_'=>$branch->name_ ]);




              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
                'count' => $u1 +$u2,
            ], 200);
        }


    public function accept_frou3_rag3(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('acceptT7welRag3Qr-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $shipments = Shipment::with(['Branch_user' => function ($query) {
            $query->select('code_','phone_');
        }])
        ->where('Ship_area_', '=', $user->branch)
        ->where('TRANSFERE_ACCEPT_REFUSE',3);

        if(isset($request->branch)){
            $shipments = $shipments->where(function ($query) use($request){
               $query->where('branch_', '=', $request->branch)
                     ->orWhere('transfere_1', '=', $request->branch);
           });
        }
        if(isset($request->mo7afza)){
           $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
       }
        $all_shipments = $shipments;
        if($user->type_ =='عميل'){
            if(isset( request()->date_from))
                $all_shipments= $all_shipments->where('date_' ,'>=',DATE($request->date_from) );
            if(isset( request()->date_to))
                $all_shipments= $all_shipments->where('date_' ,'<=' ,DATE($request->date_to) );
        }else{
            if(isset( request()->date_from))
                $all_shipments= $all_shipments->where('tarikh_el7ala' ,'>=',DATE( request()->date_from) );
            if(isset( request()->date_to))
                $all_shipments= $all_shipments->where('tarikh_el7ala' ,'<=',DATE( request()->date_to) );
        }

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

        $page_title='الموافقة على تحويل رواجع الفروع';
        $branches =BranchInfo::all();
        $mo7afazat =Mohfza::all();
        if(isset(request()->pdf)){
            $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $all->sum('tawsil_coast_');
            $printPage='shipments.print';

            $alSafiCost = $all->sum('total_');

                $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];


            $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            $mpdf = PDF::loadView('shipments.print',$data);
            return $mpdf->stream('document.pdf');
        }
        $page_title='الموافقة على تحويل رواجع الفروع';
        return view('frou3.t7wel_rag3.accept',compact('all','branches','mo7afazat','page_title'));
    }
    public function accept_frou3_rag3_save(Request $request){
        $user=auth()->user();

        $t2 = Tempo::where('code_', $request->code)
        ->first();


        $shipment=Shipment::where('code_',$request->code)
        ->where('TRANSFERE_ACCEPT_REFUSE',3)
        ->where('Ship_area_', '=', $user->branch);


        if($request->type=='accept'){

            $shipment=$shipment->first();
            if(!isset($shipment)) return ;
            $shipment->TRANSFERE_ACCEPT_REFUSE =1;
            $shipment->save();
        }

        elseif($request->type=='cancel'){
            $shipment->first()->delete();
            if(isset($t2))
                Shipment::insert(json_decode(json_encode($t2), true));
            else{
                return response()->json([
                    'status' => 404,
                ], 404);
            }
            
        }
        if(isset($t2))
            $t2->delete();
    }
    public function accept_frou3_rag3_qr_save(Request $request){

         $user = auth()->user();
         DB::table('add_shipment_tb_')
         ->whereIN('code_',$request->code)
         ->where('TRANSFERE_ACCEPT_REFUSE',3)
         ->where('Ship_area_', '=', $user->branch)
         ->update([
             'TRANSFERE_ACCEPT_REFUSE' =>1,

         ]);

         DB::table('add_shipment_tempo')
         ->whereIN('code_',$request->code)
         ->delete();
         return response()->json([
             'status' => 200,
             'message' => 'تم الموافقة',
         ], 200);

     }
    public function accept_rag3_get(Request $request)
     {
         $user = auth()->user();
         $shipment =Shipment::where('code_',$request->code)
         ->where('Ship_area_', '=', $user->branch)
         ->where('TRANSFERE_ACCEPT_REFUSE',3);
         $shipment= $shipment->with(['Shipment_status'])
         ->get();

         if($shipment->count()== 1)
         {
             $status=200;
             $data=$shipment;
         }
         else
         {
             $status=404;
             $data=[];
         }
         return response()->json([
             'status' => $status,
             'data' => $data,
         ], $status);
     }
    //end rag3




    //acc
    public function AccountingNotMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('notMosadad-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = '';
        if(isset($request->branch_)  && $request->branch_!='الكل')
            $brach_filter= $request->branch_;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        //if(isset(request()->limit ))   $limit =request()->limit;


        if( $brach_filter != '')
        {
            $shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 = '{$brach_filter}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 = '{$brach_filter}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
            $shipments = $shipments->where(function ($query) use($request,$user,$brach_filter){
                $query->where(function ($query) use($request,$user,$brach_filter){
                    $query->where('branch_', '=', $user->branch)
                    ->where('transfere_1', $brach_filter)
                    ->where('elfar3_elmosadad_mno','');

                    })
                    ->orWhere(function ($query) use($request,$user,$brach_filter){
                        $query->where('transfere_1', '=', $user->branch)
                        ->where('transfere_2',$brach_filter )
                        ->where('elfar3_elmosadad_mno_2','');
                    });
            });
        }
            else
            {$shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 !=  '' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 != '' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
                $shipments = $shipments->where(function ($query) use($request,$user,$brach_filter){
                    $query->where(function ($query) use($request,$user,$brach_filter){
                        $query->where('branch_', '=', $user->branch)
                        ->where('transfere_1', '!=', '')
                        ->where('elfar3_elmosadad_mno','=','');

                        })
                        ->orWhere(function ($query) use($request,$user,$brach_filter){
                            $query->where('transfere_1', '=', $user->branch)
                            ->where('transfere_2', '!=', '')
                            ->where('elfar3_elmosadad_mno_2','=' ,'');
                        });
                });
            }
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
       if(isset($request->client_id)){
        $shipments = $shipments->where('client_name_', '=', $request->client_id);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            }


        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

            $all_shipments = $shipments;
            $ta7weel=0;
            foreach($all_shipments->get() as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
            // dd($ta7weel);
        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }

        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $ta7weel;
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
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        $branches =BranchInfo::all();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='الشحنات الغير مسددة للفرع';
        if(isset(request()->pdf)){
            //return view('shipments.print' , compact('all'));
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                if( $brach_filter != '')
                {
                    $all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 = '{$brach_filter}' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 = '{$brach_filter}' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));

                }
                else
                {$all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 !=  '' and elfar3_elmosadad_mno = '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 != '' and elfar3_elmosadad_mno_2 = '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));

                }

                // dd(request()->pdf);

                // dd($all);


            }

            $all=$all->get();
                    $ta7weel=0;
            foreach($all as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }


            //return view('shipments.print' , compact('all'));
            $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $ta7weel;
            $alSafiCost = $totalCost - $tawsilCost;

            $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'alfer3' => 1];
            $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];

            $mpdf = PDF::loadView('frou3.accounting.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('frou3.accounting.notmosadad',compact('all','branches','mo7afazat','brach_filter','waselOnly','page_title',
     'css_prop','status_color' ,'sums'));
    }

    public function tasdid(Request $request){

        $user = $user = auth()->user();
        if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch)
        {
            return response()->json([
                'status' => 404,
                'message' => 'لم يتم التسديد',
            ], 404);
        }
        //case 1

        $row1 = DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        ->where('add_shipment_tb_.status_', 7)
        ->where('add_shipment_tb_.branch_' ,$user->branch)
        ->where('add_shipment_tb_.transfere_1' ,$request->brach_filter)
        ->where('add_shipment_tb_.elfar3_elmosadad_mno', '')
            ->update(['add_shipment_tb_.tarikh_tasdid_far3'=>Carbon::now(),
            'add_shipment_tb_.elfar3_elmosadad_mno' =>'مسدد',
            ]);
        $row2 = DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.status_', 7)
            ->where('transfere_1', '=', $user->branch)
            ->where('transfere_2',$request->brach_filter )
            ->where('add_shipment_tb_.elfar3_elmosadad_mno_2', '')
                ->update(['add_shipment_tb_.tarikh_tasdid_far3_2'=>Carbon::now(),
                'add_shipment_tb_.elfar3_elmosadad_mno_2' =>'مسدد',
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'تم التسديد',
                'count' => $row1+$row2,
            ], 200);
    }


    public function AccountingMosadad(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('mosadad-frou3')){
            return abort(403);
        }
        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;
        $brach_filter = '';
        if(isset($request->branch_) && $request->branch_!='الكل')
            $brach_filter= $request->branch_;
        $waselOnly=0;
        if(isset($request->waselOnly))
            $waselOnly= 1;

        if(isset(request()->limit ))   $limit =request()->limit;

        if($brach_filter != '')
        {
            $shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 = '{$brach_filter}' and elfar3_elmosadad_mno != '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 = '{$brach_filter}' and elfar3_elmosadad_mno_2 != '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
            $shipments= $shipments->where(function ($query) use($request,$user,$brach_filter){
                $query->where(function ($query) use($request,$user,$brach_filter){
                    $query->where('branch_', '=', $user->branch)
                    ->where('transfere_1', $brach_filter)
                    ->where('elfar3_elmosadad_mno',  '!=' ,'');
                    })
                    ->orWhere(function ($query) use($request,$user,$brach_filter){
                        $query->where('transfere_1', '=', $user->branch)
                        ->where('transfere_2',$brach_filter )
                        ->where('elfar3_elmosadad_mno_2','!=' ,'');
                    });
            });
        }
        else{
            $shipments = Shipment::select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 != '' and elfar3_elmosadad_mno != '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 != '' and elfar3_elmosadad_mno_2 != '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
            $shipments= $shipments->where(function ($query) use($request,$user,$brach_filter){
                $query->where(function ($query) use($request,$user,$brach_filter){
                    $query->where('branch_', '=', $user->branch)
                    ->where('transfere_1', '!=', '')
                    ->where('elfar3_elmosadad_mno',  '!=' ,'');
                    })
                    ->orWhere(function ($query) use($request,$user,$brach_filter){
                        $query->where('transfere_1', '=', $user->branch)
                        ->where('transfere_2','!=','')
                        ->where('elfar3_elmosadad_mno_2','!=' ,'');
                    });
            });
        }
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
       if(isset($request->client_id) ){
        $shipments = $shipments->where('client_name_', '=', $request->client_id);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
            }


        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

            $all_shipments = $shipments;
            $ta7weel=0;
            foreach($all_shipments->get() as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }
        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }

        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $ta7weel;
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
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();
        $branches =BranchInfo::all();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        // dd($counter);
        $page_title='الشحنات  المسددة للفرع';
        if(isset(request()->pdf)){
            //return view('shipments.print' , compact('all'));
            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);

                if($brach_filter != '')
                {
                    $all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 = '{$brach_filter}' and elfar3_elmosadad_mno != '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 = '{$brach_filter}' and elfar3_elmosadad_mno_2 != '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));

                }
                else{
                    $all=Shipment::whereIn('code_',$codes)->select('*',DB::raw("(CASE
                                    WHEN ( branch_ = '{$user->branch}' and  transfere_1 != '' and elfar3_elmosadad_mno != '') THEN  transfer_coast_1
                                    WHEN ( transfere_1 = '{$user->branch}' and  transfere_2 != '' and elfar3_elmosadad_mno_2 != '') THEN transfer_coast_2
                                    END) AS t7weel_cost"));
                }

                // dd($all);


            }

            $all=$all->get();
                 $ta7weel=0;
            foreach($all as $ship){
                $ta7weel += $ship->t7weel_cost ;
            }



            //return view('shipments.print' , compact('all'));
            $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $ta7weel;
            $alSafiCost = $totalCost -  $tawsilCost;

            $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'alfer3' => 1];
            $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];            $mpdf = PDF::loadView('frou3.accounting.print',$data);
            return $mpdf->stream('document.pdf');
        }
        return view('frou3.accounting.mosadad',compact('sums','all','branches','mo7afazat','brach_filter','waselOnly','page_title','status_color'
        ,'css_prop'));
    }


    public function cancelTasdid(Request $request){

        $user = $user = auth()->user();
        if($user->branch !='الفرع الرئيسى' && $request->brach_filter!=$user->branch)
        {
            return response()->json([
                'status' => 404,
                'message' => 'لم يتم التسديد',
            ], 404);
        }
        //case 1

        $row1 = DB::table('add_shipment_tb_')
        ->whereIn('add_shipment_tb_.code_', $request->code)
        ->where('add_shipment_tb_.status_', 7)
        ->where('add_shipment_tb_.branch_' ,$user->branch)
        ->where('add_shipment_tb_.transfere_1' ,$request->brach_filter)
        ->where('add_shipment_tb_.elfar3_elmosadad_mno','!=', '')
            ->update(['add_shipment_tb_.tarikh_tasdid_far3'=>'',
            'add_shipment_tb_.elfar3_elmosadad_mno' =>'',
        ]);
        $row2= DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.status_', 7)
            ->where('transfere_1', '=', $user->branch)
            ->where('transfere_2',$request->brach_filter )
            ->where('add_shipment_tb_.elfar3_elmosadad_mno_2','!=' ,'')
                ->update(['add_shipment_tb_.tarikh_tasdid_far3_2'=>'',
                'add_shipment_tb_.elfar3_elmosadad_mno_2' =>'',
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'تم التسديد',
                'count' => $row1+$row2,
            ], 200);
    }
     //end acc
}
