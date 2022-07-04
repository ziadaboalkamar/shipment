<?php

namespace App\Http\Controllers;

use App\CustomClass\response;
use App\Http\Controllers\Api\site\Controller;
use App\Models\BranchInfo;
use App\Models\Mantikqa;
use App\Models\Mohfza;
use App\Models\Shipment;
use App\Models\AllUser;
use App\Models\Shipment_status;
use App\Models\Commercial_name;
use App\Models\Archive;
use App\User;
use App\Models\Branch_user;
use QrCode;

use Carbon\Carbon;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use PDF;
class shipmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getAllMo7afazat(){

        return Mohfza::where('branch',auth()->user()->branch)->get();
    }



    /**
     * status
     * ship_area = user->branch
     * trasfaere accept refuse in [1 , 0]
     */
    public function HomePage(Request $request)
    {

        $user=auth()->user();

        if(!$user->isAbleTo('homePage-shipment')){
            return abort(403);
        }
        $statuses= Shipment_status::orderBy('sort_no')->where('code_' ,'!=',10)
        ->UserTypeFilter($user->type_,$user->code_)
        ->select('code_','name_')->get()->toArray();
        $total=0;
        foreach($statuses as $key=> $status){

            $shipments = Shipment::where('Status_',$status['code_'])->whereIn('TRANSFERE_ACCEPT_REFUSE',[1,0])->where('Ship_area_',$user->branch);
            //->UserType($user->type_,$user->code_);
            if(isset(request()->commercial_name)){
                $shipments=$shipments->where('commercial_name_',request()->commercial_name);
            }
            $shipments=$shipments->select(DB::raw('count(*) as cnt'))
            //->groupBy( 'Status_')
            ->first();
            $statuses[$key]['cnt'] = $shipments ? $shipments->cnt : 0;
            $total+=$statuses[$key]['cnt'];
        }




        //  dd($total);
        return view('shipments.7ala',compact('statuses','total'));
        //return view('shipments.total',compact('statuses','cummercial_names'));


    }
    public function deleteShipment(int $code){
        $user=auth()->user();
        if(!$user->isAbleTo('update-shipment')){
            return abort(403);
        }
        $shipment = Shipment::where('code_',$code)->first();
        if (!$shipment) {
            return redirect()->back()->with('status', 'يوجد خطاء ما!');
        }else{
            $shipment->delete();
            return redirect()->back()->with('status', 'تم الحذف بنجاح');

        }

    }
    public function t7weelArray($ind = null){
        $arr=[];
        $statuses= Shipment_status::orderBy('sort_no')->where('code_' ,'!=',10)->select('code_','name_')->get()->toArray();

        foreach($statuses as $stat){
            if($stat['name_'] == 'الشحنات لدى العميل'){
                $arr[$stat['code_']]  = ['الشحنات فى المخزن','الشحنات الراجعه فى المخزن'];
            }
            elseif($stat['name_'] == 'الشحنات فى المخزن'){
                $arr[$stat['code_']]  = ['شحنات الواصل','الشحنات الراجعه فى المخزن','الشحنات لدى مندوب التسليم','شحنات واصل جزئى'];
            }
            elseif($stat['name_'] == 'الشحنات لدى مندوب الاستلام'){
                $arr[$stat['code_']]  = ['الشحنات فى المخزن'];
            }
            elseif($stat['name_'] == 'الشحنات لدى مندوب التسليم'){
                $arr[$stat['code_']]  = ['شحنات الواصل','شحنات واصل جزئى','الشحنات الراجعه فى المخزن'];
            }
            elseif($stat['name_'] == 'شحنات الواصل'){
                $arr[$stat['code_']]  = ['الشحنات لدى مندوب التسليم'];
            }
            elseif($stat['name_'] == 'شحنات واصل جزئى'){
                $arr[$stat['code_']]  = ['شحنات الواصل'];
            }
            elseif($stat['name_'] == 'الشحنات الراجعه فى المخزن'){
                $arr[$stat['code_']]  = ['الشحنات فى المخزن','شحنات الراجع لدى العميل'];
            }
            elseif($stat['name_'] == 'شحنات الراجع لدى العميل'){
                $arr[$stat['code_']]  = [];
            }


        }
        if($ind != null ) return $arr[$ind];
        dd($statuses);
    }

     public function shipments(int $type,Request $request)
    {
        $user=auth()->user();
        if(!$user->isAbleTo('index-shipment')){
            return abort(403);
        }
           $status=$type;

            $limit=Setting::get('items_per_page');
             $page =0;
             if(isset(request()->page)) $page= request()->page;

            $t7weelTo = $this->t7weelArray( $type);
            $shipments = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }])->whereIn('TRANSFERE_ACCEPT_REFUSE',[1,0])->where('Ship_area_',$user->branch)
            ->where('status_',$status);




            if(isset(request()->commercial_name)){
                $shipments = $shipments->where('add_shipment_tb_.commercial_name_', request()->commercial_name);
            }

        if(isset($request->code)){
           $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
         }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
         }
       if(isset($request->branch_) && $request->branch_!='الكل'){
        $shipments = $shipments->where('branch_', '=', $request->branch_);
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

        if(isset( request()->mandoub_taslim))
            $shipments= $shipments->where('mandoub_taslim' ,request()->mandoub_taslim );
        if(isset( $request->client_id))
            $shipments= $shipments->where('client_name_' ,$request->client_id);
        if(request()->showAll == 'on'){
            $counter= $all_shipments->get();
            $count_all = $counter->count();
            request()->limit=$count_all;
        }
        //  dd($all_shipments->skip(0)->limit(40)->get()[20]);
        $totalCost = $all_shipments->sum('shipment_coast_');
        $tawsilCost = $all_shipments->sum('tawsil_coast_');
        if($status == 4)
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


        // $all->withPath("?mo7afza={$request->mo7afza}&showAll={$request->showAll}
        // &client_id={$request->client_id}");
        $manadeb_taslim= User::where('branch',auth()->user()->branch)->where('type_','مندوب تسليم')->get();
        $mo7afazat =$this->getAllMo7afazat();
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();


        $clients =User::where('type_','عميل')->get();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        //  dd($status_color);
        $page_title=Shipment_status::where('code_',$type)->first()->name_;
        $title=Shipment_status::where('code_',$type)->first()->name_;
        if(isset(request()->pdf)){

            if(isset(request()->codes))
            {
                $codes= explode(',',request()->codes);
                $all=Shipment::whereIn('code_',$codes);
            }

            $all=$all->get();
            $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $all->sum('tawsil_coast_');
            $printPage='shipments.print';

            if(request()->status == 4){
                $printPage='shipments.print_mandoub_taslim';
                $tawsilCost = $all->sum('tas3ir_mandoub_taslim');
            }
            $alSafiCost = $all->sum('total_');

                $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];


            $data = [
                'all'=>$all,
                'title'=>$page_title,
                'sum'=>$sums
            ];
            //return view('shipments.print' ,compact('all','title'));

            $mpdf = PDF::loadView($printPage,$data);
            $mpdf->showImageErrors = true;
            return $mpdf->stream('document.pdf');
        }
        $mandoub_taslims = user::where('branch',$user->branch)->where('type_','مندوب تسليم')->get();
        return view('shipments.index',compact('all','type','mo7afazat','page_title','Commercial_names',
        'clients','status_color','css_prop','sums' ,'t7weelTo','mandoub_taslims','manadeb_taslim'));





    }
    public function shipmentsSearch(Request $request)
    {

        $user=auth()->user();
        if(!$user->isAbleTo('search-shipment')){
            return abort(403);
        }
            $limit=Setting::get('items_per_page');
             $page =0;
             if(isset(request()->page)) $page= request()->page;


            $shipments = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }])->whereIn('TRANSFERE_ACCEPT_REFUSE',[1,0])->where('Ship_area_',$user->branch);




            if(isset(request()->commercial_name)){
                $shipments = $shipments->where('add_shipment_tb_.commercial_name_', request()->commercial_name);
            }

        if(isset($request->code)){
           $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
         }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
         }
       if(isset($request->branch_) && $request->branch_!='الكل'){
        $shipments = $shipments->where('branch_', '=', $request->branch_);
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
        $manadeb_taslim= User::where('branch',auth()->user()->branch)->where('type_','مندوب تسليم')->get();
        $mo7afazat =$this->getAllMo7afazat();
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();


        $clients =User::where('type_','عميل')->get();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        //  dd($status_color);
        $page_title='الاستعلام عن شحنة';
        return view('shipments.search',compact('all','mo7afazat','page_title','Commercial_names',
        'clients','status_color','css_prop','sums','manadeb_taslim'));





    }
    public function shipment_bar_search(Request $request){
        $value=$request->q;
        $shipments = Shipment::with(['Shipment_status'])->where('code_',$value)->orWhere('reciver_phone_',$value)->get();

        $page_title='البحث عن شحنة';
        return view('shipments.searchBar',compact('shipments','page_title'));
    }

    public function t7weel_manual(Request $request){
        //dd($request->all());
        //dd($request->all());
        if($request->t7weel_to=='الشحنات فى المخزن'){  //ta7wel sh7nat fel m5zn
            $status=[3,2,9];
            $updated_array = ['status_'=>1,'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A')];
        }
        if($request->t7weel_to == 'شحنات واصل جزئى'){  //ta7wel sh7nat fel m5zn
            $status=[1,4];
            $updated_array = ['status_'=>6,'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A')];
        }
        if($request->t7weel_to == 'شحنات الواصل'){
            $status=[1,4,6];
            $updated_array = ['status_'=>7,'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A')];
        }
        if($request->t7weel_to == 'الشحنات لدى مندوب التسليم'){


            //to do
            $status=array(1,7);

            $mandob = User::findorfail($request->status);
            $row = DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)

            ->whereIn('add_shipment_tb_.status_', $status)
            ->leftjoin('mandoub_taslim_tas3irtb', function($join){
                $join->on('mandoub_taslim_tas3irtb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
                $join->on('mandoub_taslim_tas3irtb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id');
            })

              ->where('mandoub_taslim_tas3irtb.mandoub_ID', $mandob->code_)
              //->get();
              ->update(['add_shipment_tb_.tas3ir_mandoub_taslim'=> DB::raw("`mandoub_taslim_tas3irtb`.`price_`") ,
              'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A') ,
              'Delivery_Delivered_Shipment_ID'=> $mandob->code_ ,
              'mandoub_taslim' =>$mandob->name_,
              'add_shipment_tb_.status_' => 4
            ]);


              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
                'count' => $row,
            ], 200);
        }
        if($request->t7weel_to=='شحنات الراجع لدى العميل'){   //t7wel rag3 lada 3amel
            $status=array(9);
            $updated_array = ['status_'=>8, 'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
                                'shipment_coast_'=>0 , 'tawsil_coast_'=>0 , 'total_'=>0  ];
        }
        if($request->t7weel_to=='الشحنات الراجعه فى المخزن'){   //t7wel rag3 lada m5zn
            $status=array(1,3,4);
            $updated_array = ['status_'=>9, 'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
                                'Delivery_Delivered_Shipment_ID'=>"" , 'mandoub_taslim'=>"" , 'tas3ir_mandoub_taslim'=>0 ];
        }
        $row = DB::table('add_shipment_tb_')
              ->whereIn('code_', $request->code)
              ->whereIn('status_', $status)
              ->update($updated_array);

              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
                'count' => $row,
            ], 200);
    }

    public function accounting(Request $request)
    {
            $data=[];
            $Offset=0; $limit=10;
            if(isset(request()->offset ))   $offset =request()->offset;
            if(isset(request()->limit ))   $limit =request()->limit;
            $user = DB::table("all_users")->where('code_' ,request()->user_id)->first();


            $query = DB::table('add_shipment_tb_')
            ->leftJoin('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone' );

            //mosadada
            if($user->type_ =='عميل'){
                $all= $query->where('add_shipment_tb_.client_ID_',$user->code_)
                ->where("el3amil_elmosadad",'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('add_shipment_tb_.Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $query->where('add_shipment_tb_.Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim",'مسدد');
            }
            if(isset($request->commercial_name)){
                $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
            if($user->type_=='عميل'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب استلام'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب تسليم'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'<=',DATE( $request->date_to) );
            }
            $countall = $all->count();


            $ar=[];
            $ar['name_']= 'مسدد';
            $ar['cnt']= $countall;
            $ar['code_']= 1;
            array_push($data,$ar);

            //3*er mosadada
            $query = DB::table('add_shipment_tb_')
            ->leftJoin('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone' );
            $all= $query->where('add_shipment_tb_.Status_',"!=",8);
            if($user->type_ =='عميل'){
                $all= $query->where('add_shipment_tb_.client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('add_shipment_tb_.Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
               $all= $query->where('add_shipment_tb_.Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }
            if(isset($request->commercial_name)){
                $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
            if(isset( $request->date_from))
                $query= $query->where('add_shipment_tb_.date_' ,'>=',DATE($request->date_from) );
            if(isset( $request->date_to))
                $query= $query->where('add_shipment_tb_.date_' ,'<=',DATE( $request->date_to) );
            $countall = $all->count();


            $ar=[];
            $ar['name_']= 'غير مسدد';
            $ar['cnt']= $countall;
            $ar['code_']= 2;
            array_push($data,$ar);

            //mosta7aqa
            $query = DB::table('add_shipment_tb_')

            ->leftJoin('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone' );
            $all_mosta7aqa= $query->where('add_shipment_tb_.Status_',7);

            if($user->type_ =='عميل'){
                $all_mosta7aqa= $query->where('add_shipment_tb_.client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all_mosta7aqa= $query->where('add_shipment_tb_.Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
                $all_mosta7aqa= $query->where('add_shipment_tb_.Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }
            if(isset($request->commercial_name)){
                $all_mosta7aqa = $all_mosta7aqa->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
            if(isset( $request->date_from))
                $query= $query->where('add_shipment_tb_.date_' ,'>=',DATE($request->date_from) );
            if(isset( $request->date_to))
                $query= $query->where('add_shipment_tb_.date_' ,'<=',DATE( $request->date_to) );
            $countall = $all_mosta7aqa->count();

            $ar=[];
            $ar['name_']= 'مستحق';
            $ar['cnt']= $countall;
            $ar['code_']= 3;
            array_push($data,$ar);

            //archive
            $query = DB::table('archive_')

            ->leftJoin('add_branch_users_tb', 'archive_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('archive_.*', 'add_branch_users_tb.phone_ as mabdob phone' );

            if($user->type_ =='عميل'){
                $all= $query->where('archive_.client_ID_',$user->code_);
                ///->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('archive_.Delivery_take_shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $query->where('archive_.Delivery_Delivered_Shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }
            if(isset($request->commercial_name)){
                $all = $all->where('archive_.commercial_name_', $request->commercial_name);
            }
            if(isset( $request->date_from))
                $query= $query->where('archive_.date_' ,'>=',DATE($request->date_from) );
            if(isset( $request->date_to))
                $query= $query->where('archive_.date_' ,'<=',DATE( $request->date_to) );
            $countall = $all->count();
            if(isset(request()->offset ) && isset(request()->limit ))
                $all = $all->skip($offset*$limit)->take($limit);
            $all = $all ->get();
            $ar=[];
            $ar['name_']= 'ارشيف';
            $ar['cnt']= $countall;
            $ar['code_']= 4;
            array_push($data,$ar);



        if(!isset(request()->commercial_name))
        { $cummercial_names = DB::table('commercial_name_for_main_comp')
         ->select('commercial_name_for_main_comp.name_','commercial_name_for_main_comp.code_')
         ->where('code_client', request()->user_id)
         ->groupBy( 'commercial_name_for_main_comp.name_' ,'commercial_name_for_main_comp.code_')
             ->get();
             $cummercial_names_count= $cummercial_names->count();
         }
     else{
         $cummercial_names =  request()->commercial_name;
         $cummercial_names_count  =1;
     }

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'commercial_name_count'=>$cummercial_names_count,
                'commercial_name'=>$cummercial_names,
                "type"=> $user->type_,
                'all' => $data,
            ], 200);


    }
    public $accounting_types=[1=>'mosadada',2=>'not_mosadada',3=>'mosta7aqa',4=>'archive'];
    public function accounting_shipments(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $offset=0; $limit=10;
            if(isset(request()->offset ))   $offset =request()->offset;
            if(isset(request()->limit ))   $limit =request()->limit;

            $shipments_null_date = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }]);
            if(isset(request()->code))
                $shipments_null_date = $shipments_null_date->where('status_',request()->code);
            $shipments_null_date = $shipments_null_date->UserType($user->type_,$user->code_)
            ->where('tarikh_tasdid_el3amil' ,'');

            if(isset($request->commercial_name)){
                $shipments_null_date = $shipments_null_date->where('commercial_name_', $request->commercial_name);
            }

            $shipments = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }]);

            $shipments = $shipments->UserType($user->type_,$user->code_);

            if(isset($request->commercial_name)){
                $shipments = $shipments->where('commercial_name_', $request->commercial_name);
            }
            // if(isset( request()->date_from))
            //     $shipments= $shipments->where('tarikh_tasdid_el3amil' ,'>=',DATE( request()->date_from) );
            // if(isset( request()->date_to))
            //     $shipments= $shipments->where('tarikh_tasdid_el3amil' ,'<=',DATE( request()->date_to) );



            $all_shipments = $shipments;
            //->union($shipments_null_date);

            $all_shipments=$this->filter_accounting_type($all_shipments, $this->accounting_types[request()->type],$user,$request);
            $counter= $all_shipments->get();

            $totalCost = $counter->sum('shipment_coast_');
            if($user->type_=='عميل')
                $tawsilCost = $counter->sum('tawsil_coast_');
            if($user->type_=='مندوب استلام')
                $tawsilCost = $counter->sum('tas3ir_mandoub_estlam');
            if($user->type_=='مندوب تسليم')
                $tawsilCost = $counter->sum('tas3ir_mandoub_taslim');
            $netCost =  $totalCost-$tawsilCost;
            $count_all = $counter->count();
            $all = $all_shipments->paginate($request->limit ?? 10);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => $tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            'data' => $all->items(),
        ]);



    }

    public function estlam_shipments_count(Request $request){
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب استلام")
            return response::falid('user_not_estlam', 403);

            $shipment = Shipment::where('Status_',3)
            ->where('branch_',$user->branch)
            ->UserType($user->type_,$user->code_)
            ->count();
            return response()->json([
                'status' => 200,
                'message' => 'success',
                // 'commercial_name_count'=>$cummercial_names_count,
                // 'commercial_name'=>$cummercial_names,
                "type"=> $user->type_,
                'count' => $shipment,
            ], 200);

    }
    public function estlam_shipments(Request $request){
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب استلام")
            return response::falid('user_not_estlam', 403);

            $shipment = Shipment::with(['Branch_user' => function ( $query) {
                $query->select('code_','phone_');
             }])->where('Status_',3)
            ->where('branch_',$user->branch)
            ->UserType($user->type_,$user->code_);
            // if(isset($request->commercial_name)){
            //     $shipment = $shipment->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            // }
            $totalCost = $shipment->sum('shipment_coast_');
            //$tawsilCost = $shipment->sum('tawsil_coast_');
            $tawsilCost = $counter->sum('tas3ir_mandoub_estlam');
            $netCost =  $totalCost-$tawsilCost;
            $count_all = $shipment->count();
            $all = $shipment->paginate($request->limit ?? 10);
            if(!isset(request()->commercial_name))

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => $tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            // 'commercial_name_count'=>$cummercial_names_count,
            // 'commercial_name'=>$cummercial_names,
            'data' => $all->items(),
        ]);
    }
    public function estlam_commercial_names(Request $request){
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب استلام")
            return response::falid('user_not_estlam', 403);


            $cummercial_names = DB::table('add_commercial_names_tb')
            ->select('add_commercial_names_tb.name','add_commercial_names_tb.code')
            ->where('branch', $user->branch)
            ->groupBy( 'add_commercial_names_tb.name' ,'add_commercial_names_tb.code')
                 ->get();
                 $cummercial_names_count= $cummercial_names->count();

                 return response()->json([
                    'status' => 200,
                    'message' => 'success',
                    'commercial_name_count'=>$cummercial_names_count,
                    'commercial_name'=>$cummercial_names,
                    "type"=> $user->type_,

                ], 200);
    }
    public function get_estlam_commercial_names(Request $request,$user){
            return DB::table('add_commercial_names_tb')
            ->select('add_commercial_names_tb.name','add_commercial_names_tb.code')
            ->where('branch', $user->branch)
            ->distinct()
                ;
    }

    public function get_shipment_delevery(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب تسليم")
            return response::falid('user_not_delevery', 403);

            $shipment = Shipment::where('Status_',4)
            ->where('code_',request()->shipment_code)
            ->where('Delivery_Delivered_Shipment_ID',request()->user_id)
            //->UserType($user->type_,$user->code_)
            ;

            return  $this->handleMultiShipmentelequent( $shipment, $user, $request);


    }
    public function estlm_rag3(Request $request)
    {

        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $shipments = Shipment::where('Status_',9)
            ->whereIn('code_',$request->shipment_code)
            ->where('client_ID_',$request->user_id);

           return  $this->handleMultiShipmentelequent( $shipments, $user, $request);
    }
    public function tanfez_estlm_rag3(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $all = Shipment::whereIn('code_',request()->shipment_code);
            $shipments=$all->get();
            try {
                foreach($shipments as $shipment)
                {
                    $shipment->Status_= 8 ;
                    $shipment->shipment_coast_=0 ;
                    $shipment->total_=0;
                    $shipment->tarikh_el7ala   = Carbon::now()->format('Y-m-d  g:i:s A');
                    $shipment->save();
                }

                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'status' => 500,
                        'message' => 'DB error'.$e,
                    ]);
                }

            return  $this->handleMultiShipmentelequent( $all, $user, $request);
    }
    public function estlam(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }

            $all = Shipment::where('Status_',3)
            ->whereIn( 'code_' ,$request->shipment_code);
            $ret_all=$all;
            $shipments=$all->get();

            try {
                foreach($shipments as $shipment)
            {
                $shipment->Status_=2;
                $shipment->tarikh_el7ala  = Carbon::now()->format('Y-m-d  g:i:s A');
                $shipment->mandoub_estlam = $user->name_;
                $shipment->Delivery_take_shipment_ID = request()->user_id;

                $tas3ir=DB::table('mandoub_estlam_tas3irtb')->select('price_')
                ->where('mandoub_ID',request()->user_id)
                ->where('area_name_',$shipment->mantqa_)
                ->where('city_name_',$shipment->mo7afza_)
                ->first();
                $price=0;
                if(isset($tas3ir)) $price=$tas3ir->price_;
                $shipment->tas3ir_mandoub_taslim = $price;
                $shipment->save();

            }

                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'status' => 500,
                        'message' => 'DB error'.$e,
                    ]);
                }


            if(($shipments->count()  ))
                $ret_all=Shipment::where( 'code_' ,($request->shipment_code)[0]);
            return  $this->handleMultiShipmentelequent( $ret_all, $user, $request);
    }
    public function estlam_check(Request $request)
    {

        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $shipments = Shipment::where('Status_',3)
            ->where('code_',$request->shipment_code);
            //->where('client_ID_',$request->user_id);

           return  $this->handleMultiShipmentelequent( $shipments, $user, $request);
    }
    public function taslim(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,$request->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }

            $all = Shipment::where('Status_',1)
            ->whereIn( 'code_' ,$request->shipment_code);
            $ret_all=$all;
            $shipments=$all->get();
            DB::beginTransaction();

            try {
            foreach($shipments as $shipment)
            {
                $shipment->Status_=4;
                $shipment->tarikh_el7ala  =Carbon::now()->format('Y-m-d  g:i:s A');
                $shipment->mandoub_taslim = $user->name_;
                $shipment->Delivery_Delivered_Shipment_ID = $request->user_id;

                $tas3ir=DB::table('mandoub_taslim_tas3irtb') ->select('price_')
                ->where('mandoub_ID',$request->user_id)
                ->where('area_name_',$shipment->mantqa_)
                ->where('city_name_',$shipment->mo7afza_)
                ->first();
                $price=0;
                if(isset($tas3ir)) $price=$tas3ir->price_;
                $shipment->tas3ir_mandoub_taslim = $price;

                $shipment->save();

            }

                DB::commit();
                // all good
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 500,
                    'message' => 'DB error'.$e,
                ]);
            }


            if(!empty($shipments->count()  ))
                $ret_all=Shipment::where( 'code_' ,($request->shipment_code)[0]);
            return  $this->handleMultiShipmentelequent( $ret_all, $user, $request);
    }
    public function taslim_check(Request $request)
    {

        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }

            $shipments = Shipment::where('Status_',1)
            ->where('code_',$request->shipment_code);
            //->where('client_ID_',$request->user_id);
           return  $this->handleMultiShipmentelequent( $shipments, $user, $request);
    }
    public function taslem_mandob_taslem(Request $request)
    {
        /*UPDATE add_shipment_tb_ SET Status_= 7 ,tarikh_el7ala='${intl.DateFormat('yyyy-MM-dd')}' WHERE code_ = (?);*/
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $all = Shipment::whereIn('code_',request()->shipment_code);
            $shipments=$all->get();
            foreach($shipments as $shipment)
            {
                $shipment->Status_= 7 ;
                $shipment->tarikh_el7ala   =Carbon::now()->format('Y-m-d  g:i:s A') ;
                $shipment->save();
            }
            return  $this->handleMultiShipmentelequent( $all, $user, $request);
    }
    public function wasel_goz2e_mandob_taslem(Request $request)
    {
        /*UPDATE add_shipment_tb_ SET Status_= 7 ,tarikh_el7ala='${intl.DateFormat('yyyy-MM-dd')}' WHERE code_ = (?);*/
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $all = Shipment::whereIn('code_',request()->shipment_code);
            $shipments=$all->get();
            foreach($shipments as $shipment)
            {
                $shipment->Status_= 6 ;
                $shipment->tarikh_el7ala   =Carbon::now()->format('Y-m-d  g:i:s A') ;
                $shipment->save();
            }
            return  $this->handleMultiShipmentelequent( $all, $user, $request);
    }


    public function getShipmentsByRecNum(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_', request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        $all =Shipment::where('reciver_phone_',$request->reciver_phone);

        if ($user->type_ == 'عميل') {
            $all = $all->where(
                [
                    ['add_shipment_tb_.client_ID_', $request->user_id],
                ]
            );
        }
        if ($user->type_ == 'مندوب استلام') {
            $all = $all->where([
                ['add_shipment_tb_.Delivery_take_shipment_ID', $request->user_id],

            ]);
        }
        if ($user->type_ == 'مندوب تسليم') {
            $all = $all->where([
                ['add_shipment_tb_.Delivery_Delivered_Shipment_ID', $request->user_id],
            ]);
        }




        return $this->handleMultiShipmentelequent($all, $user, $request);

    }
    public function handleMultiShipmentelequent( $all, $user, Request $request )
    {
        $totalCost = $all->sum('shipment_coast_');
        if($user->type_=='عميل')
            $tawsilCost = $all->sum('tawsil_coast_');
        if($user->type_=='مندوب استلام')
            $tawsilCost = $all->sum('tas3ir_mandoub_estlam');
        if($user->type_=='مندوب تسليم')
            $tawsilCost = $all->sum('tas3ir_mandoub_taslim');
       $netCost =  $totalCost-$tawsilCost;
        $count_all = $all->count();
        if ($user->type_ == 'عميل') {
            $all = $all->with(['Branch_user' => function ( $query) {
               $query->select('code_','phone_');
            }]);
        }


        if(isset($request->commercial_name)){
            $all = $all->where('commercial_name_', $request->commercial_name);
        }

        $all = $all->paginate($request->limit ?? 10);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => (double)$tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            'data' => $all->items(),
        ]);

    }
    public function handleMultiShipment(Builder $all, $user, Request $request)
    {
        $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tawsil_coast_');
        $netCost = $all->sum('total_');

        $count_all = $all->count();
        if ($user->type_ == 'عميل') {
            $all = $all->Join('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
                ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone');
        }
        if(isset($request->commercial_name)){
            $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
        }

        $all = $all->paginate($request->limit ?? 10);
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => $tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            'data' => $all->items(),
        ]);

    }


    public function filter_accounting_type($query,$type,$user,$request){
        if($type=='mosadada')
         {
            if($user->type_=='عميل'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب استلام'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب تسليم'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'<=',DATE( $request->date_to) );
            }
         }else{
            if($user->type_=='عميل'){
                if(isset( $request->date_from))
                    $query= $query->where('date_' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('date_' ,'<=',DATE( $request->date_to) );
            }else{
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_el7ala' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_el7ala' ,'<=',DATE( $request->date_to) );
            }
         }

         //mosadada
         if($type=='mosadada')
         {
            if($user->type_ =='عميل'){
                $all= $query ->where("el3amil_elmosadad",'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $query->where('Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim",'مسدد');
            }




         }



        //3*er mosadada
        if($type=='not_mosadada')
        {
            $all= $query->where('Status_',"!=",8);
            if($user->type_ =='عميل'){
                $all= $query->where('client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $all->where('Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $all->where('Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }
        }




        //mosta7aqa
        if($type=='mosta7aqa')
        {
            $all= $query->where('Status_',7);
            if($user->type_ =='عميل'){
                $all= $all->where('client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $all->where('Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $all->where('Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }

        }


        //archive
        if($type=='archive')
        {
            $all=Archive::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }]);
            if(isset(request()->code))
                $all = $all->where('status_',request()->code);
            $all = $all->UserType($user->type_,$user->code_);
            if(isset($request->commercial_name)){
                $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }

            if($user->type_ =='عميل'){
                $all= $all->where('client_ID_',$user->code_);
                ///->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $all->where('Delivery_take_shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $all->where('Delivery_Delivered_Shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد');
            }
        }

       return $all;
    }

    public function create(){

        $user=auth()->user();
        if(!$user->isAbleTo('new-shipment')){
            return abort(403);
        }
        $clients = User::where('type_','عميل')->where('branch', $user->branch)->get();
        $mo7afazat =Mohfza::where('branch',$user->branch)->get();
        $now = Carbon::now()->format('Y-m-d  g:i:s A');

        $code_ai=Setting::get('shipment_code_ai');
        $page_title='اضافة شحنة';
        $clearFileds=Setting::whereIn('name',['remove_mantka','remove_mo7fza','remove_client_name','remove_commercial_name'])->get()->pluck('val','name')->toArray();
        // dd($clearFileds);
        $phoneLength =Setting::get('phone_length');
        return view('shipments.create',compact('clients','mo7afazat','now','code_ai','page_title','clearFileds','phoneLength'));
    }
    public function store(Request $request){

        try {
            $validated = $request->validate([
                //'reciver_name_' => 'required',
                'client_id' => 'required',
                'mo7afza' => 'required',
                'manteka' => 'required',
                'date' => 'required',
            ]);
        // dd($request->all());

            $user= auth()->user();
            $shipment = new Shipment();
            $shipment->date_   = Carbon::now()->format('Y-m-d  g:i:s A');
            $shipment->tarikh_el7ala   = Carbon::now()->format('Y-m-d  g:i:s A');
            // $shipment->date_   = $request->date;
            $shipment->reciver_phone_   = $request->reciver_phone_;
            $shipment->client_ID_   = $request->client_id;
            if(!Setting::get('shipment_code_ai') && $request->code!='' && $request->code != null){
                $shipment->code_   = $request->code;
                $shipment->serial_ 	 = $request->code;

            }

            $client = USer::where('code_',$request->client_id)->first();
            $shipment->client_ID_   = $client->code_;
            $shipment->client_name_   = $client->name_;
            $shipment->clinet_phone_   = $client->phone_;
            $shipment->reciver_name_   = $request->reciver_name_;
            $shipment->Commercial_name_ = $request->Commercial_name;

            $mo7afzaCode=Mohfza::where('name',$request->mo7afza)->first()->code;
            $manatekCode=Mantikqa::where('name',$request->manteka )->first()->code;
            $shipment->mo7afaza_id   = $mo7afzaCode;
            $shipment->mantika_id   = $manatekCode;
            $shipment->mo7afza_   = $request->mo7afza;
            $shipment->mantqa_   = $request->manteka;
            $shipment->Ship_area_   = $user->branch;
            $shipment->branch_   = $user->branch;
            $shipment->status_   = 1;
            $shipment->el3nwan=$request->el3nwan;
            $shipment->elmantqa_el3nwan=$request->manteka."/".$request->el3nwan;
            $shipment->shipment_coast_=$request->shipment_coast_;
            $shipment->tawsil_coast_=$request->tawsil_coast_;
            $shipment->total_=$request->total_;
            $shipment->notes_  =    $request->notes_;
            $shipment->save();

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' =>   $e->getMessage(),
            ],500);

        }
        return response()->json([
            'status' => 200,
            'message' => '',
        ],200);
        return redirect()->back()->with('status', 'Settings has been saved.');

    }

    public function isCodeUsed(Request $request){

        if(Shipment::where('code_',$request->code)->get()->count() >0 ){
            if(request()->code != request()->originalCode )
                return response()->json([
                    'status' => 200,
                    'data' => true,
                ], 200);
        }
        return response()->json([
            'status' => 200,
            'data' => false,
        ], 200);
    }
    public function editView(Request $request){
        $user=auth()->user();
        if(!$user->isAbleTo('update-shipment')){
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
        //->where('branch_', '=', $user->branch)
        ->with(['client']);

        if($waselOnly)
            $shipments = $shipments->where('status_' ,'=',7) ;


        if(isset($request->code)){
           $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
         }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
         }
       if(isset($request->branch_) ){
        $shipments = $shipments->where('branch_', '=', $request->branch_);
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


        $clients =User::where('type_','عميل')->get();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
        ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        //  dd($status_color);
        $page_title='تعديل الشحنات';
        return view('shipments.editview',compact('all','mo7afazat','waselOnly','page_title','Commercial_names',
        'clients','status_color','css_prop','sums'));
    }
    public function edit(int $code){
        $user=auth()->user();
        if(!$user->isAbleTo('update-shipment')){
            return abort(403);
        }
        $shipment = Shipment::where('code_',$code)->first();

        $clients = User::where('type_','عميل')->where('branch', $user->branch)->get();
        $mo7afazat =Mohfza::where('branch',$user->branch)->get();
        $now = Carbon::now();
        $code_ai=Setting::get('shipment_code_ai');
        $page_title='تعديل شحنة';

        return view('shipments.edit',compact('shipment' ,'clients','mo7afazat','now','code_ai','page_title'));
        // تحويل اول  => transfere 1
        // استقطاع اول  => transfere_cost_1

        // تحويل تانى  => transfere 2
        // استقطاع تانى  => transfere_cost_2
    }
    public function update(Request $request){

        $validated = $request->validate([
             'code' => 'required',
            'client_id' => 'required',
            'mo7afza' => 'required',
            //'manteka' => 'required',
            'date' => 'required',
        ]);
        // dd($request->all());
        $user= auth()->user();
        $shipment = Shipment::where('code_',$request->code)->first();
//        $shipment->date_   = $request->date;
//        $shipment->tarikh_el7ala   = $request->date;
        // $shipment->date_   = $request->date;

        $shipment->client_ID_   = $request->client_id;
        // if(!Setting::get('shipment_code_ai'))
        //     $shipment->code_   = $request->code;

            // $shipment->serial_ 	 = $request->code;
        $client = USer::where('code_',$request->client_id)->first();
        $shipment->client_ID_   = $client->code_;
        $shipment->client_name_   = $client->name_;
        $shipment->clinet_phone_   = $client->phone_;
        $shipment->reciver_name_   = $request->reciver_name_;
        $shipment->reciver_phone_   = $request->reciver_phone_;
        if(isset($request->Commercial_name)){
           $shipment->Commercial_name_ = $request->Commercial_name;
        }

        $shipment->mo7afza_=$request->mo7afza;
        $mo7afzaCode=Mohfza::where('name',$request->mo7afza)->first()->code;
        $shipment->mo7afaza_id   = $mo7afzaCode;
        if(isset($request->manteka)){
            $shipment->mantqa_   = $request->manteka;
            $manatekcode=Mantikqa::where('name',$request->manteka )->first()->code;
            $shipment->mantika_id   = $manatekcode;
        }else{
            $manatekName= $shipment->mantqa_;
        }


        $shipment->Ship_area_   = $user->branch;
        $shipment->branch_   = $user->branch;
        //$shipment->status_   = 1;
        $shipment->el3nwan=$request->el3nwan;
        $shipment->elmantqa_el3nwan=$request->manteka."/".$request->el3nwan;

        $shipment->shipment_coast_=$request->shipment_coast_;
        $shipment->tawsil_coast_=$request->tawsil_coast_;
        $shipment->total_=$request->total_;
        $shipment->notes_  =    $request->notes_;

        $shipment->transfere_1  =    $request->transfere_1;
        $shipment->transfere_2  =    $request->transfere_2;
        $shipment->transfer_coast_1  =    $request->transfer_coast_1;
        $shipment->transfer_coast_2  =    $request->transfer_coast_2;


        $shipment->save();

        return redirect()->back()->with('status', 'تم حفظ التعديلات');
    }
    public function status(){

    }
      public function print(Request $request){
        $path = explode(",", $request->code);
        $exp = array();
        $exp = array_merge($exp, $path);

        $user=auth()->user();
        if(!$user->isAbleTo('index-shipment')){
            return abort(403);
        }

        $limit=Setting::get('items_per_page');
        $page =0;
        if(isset(request()->page)) $page= request()->page;

        $t7weelTo = $this->t7weelArray(2);
        $shipments = Shipment::with(['Branch_user' => function ($query) {
            $query->select('code_','phone_');
        }]);




        if(isset(request()->commercial_name)){
            $shipments = $shipments->where('add_shipment_tb_.commercial_name_', request()->commercial_name);
        }

        if(isset($request->code)){
            $shipments = $shipments->where('code_', '=', $request->code);
        }
        if(isset($request->reciver_phone)){
            $shipments = $shipments->where('reciver_phone_', '=', $request->reciver_phone);
        }

        if(isset($request->mo7afza)){
            $shipments = $shipments->where('mo7afaza_id', '=', $request->mo7afza);
        }
        if(isset($request->branch_) && $request->branch_!='الكل'){
            $shipments = $shipments->where('branch_', '=', $request->branch_);
        }
        if(isset($request->Commercial_name)){
            $shipments = $shipments->where('commercial_name_', '=', $request->Commercial_name);
        }
        if(isset( $request->client_id))
            $shipments= $shipments->where('client_name_' ,$request->client_id);
        $all_shipments = $shipments;

        if(isset( request()->date_from))
            $shipments= $shipments->where('date_' ,'>=',DATE($request->date_from) );
        if(isset( request()->date_to))
            $shipments= $shipments->where('date_' ,'<=' ,DATE($request->date_to) );

        if(isset( request()->hala_date_from))
            $shipments= $shipments->where('tarikh_el7ala' ,'>=',DATE( request()->hala_date_from) );
        if(isset( request()->hala_date_to))
            $shipments= $shipments->where('tarikh_el7ala' ,'<=',DATE( request()->hala_date_to) );

        if(isset( request()->Status_))
            $shipments= $shipments->where('Status_' ,( request()->Status_) );
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
        $manadeb_taslim= User::where('branch',auth()->user()->branch)->where('type_','مندوب تسليم')->get();
        $mo7afazat =$this->getAllMo7afazat();
        $filtered_clients = User::where('type_','عميل')->where('name_',$request->client_id)->pluck('code_')->toArray();
        $Commercial_names =Commercial_name::whereIn('code_',$filtered_clients)->groupBy('name_')->get();


        $clients =User::where('type_','عميل')->get();
        $status_color=Setting::whereIN('name',['status_6_color','status_1_color','status_2_color','status_3_color'
            ,'status_4_color','status_7_color','status_8_color','status_9_color'])->get()->keyBy('name')->pluck('val','name');
        $css_prop = Setting::get('status_css_prop');
        //  dd($status_color);
        $page_title='طباعة الايصالات';
        $title='طباعة الايصالات';
        if(isset(request()->pdf)){
              $qrNo = array();
            $allData = array();
            foreach ($exp as $code){
            $qrcode=QrCode::encoding('UTF-8')->size(70)->generate($code);
            $qrcode= str_replace('<?"xml version="1.0" encoding="UTF-8?>',"",$qrcode);
            $qrcode= str_replace('<?xml version="1.0" encoding="UTF-8"?>',"",$qrcode);
            // $qrcode=str_replace("\n","",$qrcode);
            // $qrcode=str_replace("\"","",$qrcode);

            // dd($qrcode);
            if(!isset(request()->code)) return ;
            $all = Shipment::where('code_',$code)->get()[0];
                array_push($allData,$all);
                array_push($qrNo,$qrcode);


        }

            $data = [
                'all'=>$allData,
                'title'=>$page_title,
                'qrcode'  =>$qrNo
            ];
                 // return view('shipments.print2',compact('allData','qrNo'));
            //return view('shipments.print2' ,compact('all','title'));
            // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 236]]);
            // $customPaper = array(0,0,567.00,283.80);
            $mpdf = PDF::loadView('shipments.print2',$data);//->setPaper($customPaper, 'landscape');;
            // $mpdf->AddPage('p','','','','',10,10,37,20,10,10);
            return $mpdf->stream('document.pdf');
        }
        $type = 2;
        $statuses = Shipment_status::all();
        return view('shipments.wals_print',compact('all','type','mo7afazat','page_title','Commercial_names',
            'clients','status_color','css_prop','sums' ,'t7weelTo','manadeb_taslim','statuses'));





    }
    public function estlamGet(){

    }
    public function changeToArchive(){

    }
    public function t7wel_qr()
    {
        $page_title='تحويل حالة الشحنات باستخدام qr';
        return view('shipments.t7wel_7ala_qr',compact('page_title'));
    }
    public function t7wel_qr_save(Request $request)
    {
        //dd($request->all());
        if($request->status==1){  //ta7wel sh7nat fel m5zn
            $status=[3,2,9];
            $updated_array = ['status_'=>1,'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A')];
        }
        if($request->status==8){   //t7wel rag3 lada 3amel
            $status=array(9);
            $updated_array = ['status_'=>8, 'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
                                'shipment_coast_'=>0 , 'tawsil_coast_'=>0 , 'total_'=>0  ];
        }
        if($request->status==9){   //t7wel rag3 lada m5zn
            $status=array(1,4);
            $updated_array = ['status_'=>9, 'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A'),
                                'Delivery_Delivered_Shipment_ID'=>"" , 'mandoub_taslim'=>"" , 'tas3ir_mandoub_taslim'=>0 ];
        }


        if(isset(request()->pdf)){
            $codes= explode(',',$request->codes);
            $all = DB::table('add_shipment_tb_')
              ->whereIn('code_', $codes)->get();
            $totalCost = $all->sum('shipment_coast_');
            $tawsilCost = $all->sum('tawsil_coast_');
            $printPage='shipments.print';

            $alSafiCost = $all->sum('total_');

                $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];

            $data = [
                'all'=>$all,
                'title'=>'تحويل حالة الشحنات باستخدام qr',
                'sum'=>$sums
            ];

            $mpdf = PDF::loadView('shipments.print',$data);
            return $mpdf->stream('document.pdf');
        }
        DB::table('add_shipment_tb_')
              ->whereIn('code_', $request->code)
              ->whereIn('status_', $status)
              ->update($updated_array);

              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
            ], 200);

    }

    public function taslim_qr()
    {
        $manadeb_taslim= User::where('branch',auth()->user()->branch)->where('type_','مندوب تسليم')->get();
        $page_title='تسليم الشحنت الى مندوب تسليم';
        return view('shipments.taslim_qr',compact('manadeb_taslim','page_title'));
    }
    public function taslim_qr_save(Request $request)
    {
        $status=array(1);
        $user = $user = auth()->user();
        $codes= explode(',',$request->codes);

        if(isset(request()->pdf)){
            $all =  DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $codes)
            ->whereIN('add_shipment_tb_.status_',[ 1,4])
            ->get();
            $totalCost = $all->sum('shipment_coast_');


            $tawsilCost = $all->sum('tas3ir_mandoub_taslim');
            $alSafiCost = $totalCost - $tawsilCost ;

            $printPage='accounting.mandoubtaslim.print';
                $sums=['totalCost' =>$totalCost, 'tawsilCost' =>$tawsilCost , 'alSafiCost'=>$alSafiCost,'company'=>1];
            $data = [
                'all'=>$all,
                'title'=>'تسليم الشحنة الى مندوب التسليم باستخدام qr',
                'sum'=>$sums
            ];

            $mpdf = PDF::loadView('shipments.print_mandoub_taslim',$data);
            return $mpdf->stream('document.pdf');
        }
        $mandob = User::findorfail($request->status);

        $u =  DB::table('add_shipment_tb_')
         ->whereIn('add_shipment_tb_.code_', $request->code)

          ->whereIn('add_shipment_tb_.status_', [1,4])
        ->leftjoin('mandoub_taslim_tas3irtb', function($join){
            $join->on('mandoub_taslim_tas3irtb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
            $join->on('mandoub_taslim_tas3irtb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id');
        })

              ->where('mandoub_taslim_tas3irtb.mandoub_ID', $mandob->code_)
              //->get();
              ->update(['add_shipment_tb_.tas3ir_mandoub_taslim'=> DB::raw("`mandoub_taslim_tas3irtb`.`price_`") ,
              'tarikh_el7ala'=>Carbon::now()->format('Y-m-d  g:i:s A') ,
              'Delivery_Delivered_Shipment_ID'=> $mandob->code_ ,
              'mandoub_taslim' =>$mandob->name_,
              'add_shipment_tb_.status_' => 4
            ]);


              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
            ], 200);
        }



    public function getShipmentsByCode(Request $request)
    {
        //brach   ship area   //status
        if($request->case=='t7wel_7ala_qr'){

            if($request->status==1){  //ta7wel sh7nat fel m5zn
                $status=[3,2,9];
                $filter_field = 'Ship_area_';
            }
            if($request->status==8){   //t7wel rag3 lada 3amel
                $status=array(9);
                $filter_field = 'branch_';
            }
            if($request->status==9){   //t7wel rag3 lada m5zn
                $status=array(1,4);
                $filter_field = 'Ship_area_';
            }
        }elseif($request->case=='taslim_qr'){
            $status=array(1,4);
            $filter_field = 'Ship_area_';
        }elseif($request->case=='frou3_t7wel_sho7nat_qr'){
            $status=array(1);
            $filter_field = 'Ship_area_';
        }
        elseif($request->case=='frou3_t7wel_rag3_qr'){
            $status=array(9);
            $filter_field = 'Ship_area_';
        }



        $user = auth()->user();

        $shipment =Shipment::where('code_',$request->code)
        ->whereIn('status_', $status)
        ->where($filter_field, $user->branch);
        if($request->case=='frou3_t7wel_sho7nat_qr'){
            $shipment=$shipment->where('transfere_2','');
        }
        if($request->case=='frou3_t7wel_rag3_qr'){
            $shipment=$shipment->where('transfere_1','!=','');
        }

        $shipment= $shipment->with(['Shipment_status'])
        ->get();
        //

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
}
//
