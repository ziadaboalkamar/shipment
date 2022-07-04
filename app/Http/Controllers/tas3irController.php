<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MandoubEstlam;
use App\Models\MandoubTaslim;
use App\User;
use App\Models\Mohfza;
use App\Models\Mantikqa;
use App\Models\Tas3ir_3amil;
use App\Models\Tas3ir_3amil_5as;
use App\Models\Tas3ir_ta7wel;
use App\Models\AddClientsMainComp;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class tas3irController extends Controller
{

        public function save_3amel(Request $request)
        {

        	$tas3ir = Tas3ir_3amil::find($request->code);
                $tas3ir->price_= $request->value;
                $tas3ir->save();
        }
        public function save_ta7wel(Request $request)
        {

        	$tas3ir = Tas3ir_ta7wel::find($request->code);
                $tas3ir->price_= $request->value;
                $tas3ir->save();

        }
        public function tas3ir_3amil_5as()
        {
                $user=auth()->user();
                if(!$user->isAbleTo('tas3ir3amel5as-definations')){
                return abort(403);
                }
        	$branch = Auth::user()->branch;
        	$cities=Mohfza::groupBy('name')->get();
            $page_title='تسعير العميل الخاص';
        	$specialClients = User::groupBy('name_')->where('Special_prices','!=','لا' )->where('branch',$branch)->get();
      	        return view('tas3ir.3amil_5as',compact('cities','specialClients','page_title'));
        }
        public function tas3ir_mandouben(Request $request)
    {

        $cities=Mohfza::groupBy('name')->get();
        $page_title='تسعير المندوب';
        return view('tas3ir.mandoub',compact('cities','page_title'));

    }

    public function getNameByType(Request $request,$id=1)
    {
        $branch = Auth::user()->branch;
        if ($request->id == 1){
            $mandoubName = User::groupBy('name_')->where('branch',$branch)->where('type_' , 'مندوب استلام')->get();
            return response()->json($mandoubName);

        }elseif ($request->id == 2){
            $mandoubName = User::groupBy('name_')->where('branch',$branch)->where('type_' , 'مندوب تسليم')->get();
            return response()->json($mandoubName);
        }elseif ($id == 0){

            return response()->json(null);
        }


    }

    public function saveMandobe(Request $request)
    {



        if ($request->mandobeType == 1){
            $tas3ir = MandoubEstlam::where('city_name_',$request->mo7afza)->where('area_name_',$request->manteqa)
            ->where('mandoub_ID',$request->mandobe)->first();

            if (!isset($tas3ir)){
                $mandobe_name = User::where('code_',$request->mandobe)->get();
                $branch = Auth::user()->branch;
                $mandoub=   MandoubEstlam::create([
                    'code_'=>15,
                    'area_name_'=>$request->manteqa,
                    'city_name_'=>$request->mo7afza,
                    'price_'=>$request->value,
                    'branch'=>$branch,
                    'mandoub_name_'=>$mandobe_name[0]['name_'],
                    'mandoub_ID'=>$request->mandobe,

                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'success',
                    'mandoubSerial' => $mandoub->serial_,
                ], 200);
            }else {
                $tas3ir->update([
                    'price_' => $request->value,
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'success',
                ], 200);
            }
        } elseif ($request->mandobeType == 2){

            $tas3ir = MandoubTaslim::where('city_name_',$request->mo7afza)->where('area_name_',$request->manteqa)
            ->where('mandoub_ID',$request->mandobe)->first();
            if (!isset($tas3ir)){
                $mandobe_name = User::where('code_',$request->mandobe)->get();
                $matika_id = 652;
                if( $request->manteqa  =='اطراف')  $matika_id = 4;
                $mo7afaza_id = DB::table('edaft_mo7afzat_iraq_tb')->where('name',$request->mo7afza)->first()->code;
                MandoubTaslim::create([
                    'code_'=>15,
                    'area_name_'=>$request->manteqa,
                    'city_name_'=>$request->mo7afza,
                    'price_'=>$request->value,
                    'branch'=>'الفرع الرئيسى',
                    'mandoub_name_'=>$mandobe_name[0]['name_'],
                    'mandoub_ID'=>$request->mandobe,
                    'mo7afaza_id'=> $mo7afaza_id,


                    'mantika_id'=>$matika_id
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'success',
                ], 200);
            }else{

            $tas3ir->update([
                'price_' => $request->value,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'success',
            ], 200);
            }

        }

    }
    public function save_3amel_5as(Request $request){

        if ($request->serial == 0){
             $specialClient = User::where('code_',$request->specialClient)->first();
             $branch = Auth::user()->branch;
            Tas3ir_3amil_5as::create([
                'area_name_'=>$request->manteqa,
                'city_name_'=>$request->mo7afza,
                'price_'=>$request->value,
                'branch'=>$branch,
                'name'=>$specialClient->name_,
                'mandoub_ID'=>$request->specialClient,
                'type'=>'عملاء'

            ]);
            return response()->json([
                'status' => 200,
                'message' => 'success',
            ], 200);
        }else {

            $tas3ir = Tas3ir_3amil_5as::where('code_',$request->serial)->where('mandoub_ID',$request->specialClient)->first();

            $tas3ir->update([
                'price_' => $request->value,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'success',
            ], 200);
        }

        }

    public function getManateqAndTas3irMandobByMa7afza( )
    {
        $mo7afza=request()->mo7afza;
        $branch = Auth::user()->branch;
        $mandobe = request()->mandobe;
        $mandobeType = request()->mandobeType;
        if ($mandobeType == 1){
            $manatek =Mantikqa::with(['Tas3ir_Mandobe_Estilam'=> function ($query) use($mandobe) {
           $query->where('mandoub_ID' ,$mandobe);
       }])->where('mo7afza',$mo7afza)->where('branch',$branch)->get();
                 $manatekEmpty =Mantikqa::where('mo7afza',$mo7afza)->where('branch',$branch)->get();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'all' => $manatek,
                'manatekEmpty'=>$manatekEmpty,
                'sum' => count($manatek),
                'manbobeType'=>1
            ], 200);
        }elseif ($mandobeType == 2){
          $manatek =Mantikqa::with(['Tas3ir_Mandobe_Taslim'=> function ($query) use($mandobe) {
                $query->where('mandoub_ID' ,$mandobe);
            }])->where('mo7afza',$mo7afza)->where('branch',$branch)->get();
            $manatekEmpty =Mantikqa::where('mo7afza',$mo7afza)->where('branch',$branch)->get();
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'all' => $manatek,
                'manatekEmpty'=>$manatekEmpty,
                'sum' => count($manatek),
                'manbobeType'=>2
            ], 200);
        }






    }

        public function getManateqAndTas3ir5asByMa7afza( )
        {
                $mo7afza=request()->mo7afza;
                $specialClientRequest = request()->specialClient;
                  $specialClientsBranch = User::where('code_',$specialClientRequest )->first();
                $branch = Auth::user()->branch;
             $manatek =Mantikqa::with(['Tas3ir_3amil_5as'=> function ($query) use($specialClientRequest) {
                $query->where('mandoub_ID' ,$specialClientRequest);
            }])->where('mo7afza',$mo7afza)->where('branch',$branch)->get();
            $manatekEmpty =Mantikqa::where('mo7afza',$mo7afza)->where('branch',$branch)->get();
                return response()->json([
                'status' => 200,
                'message' => 'success',
                'all' => $manatek,
                'mandobeEmpty'=>$manatekEmpty,
                'sum' => count($manatek)
                ], 200);



        }


}
