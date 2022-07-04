@extends('layout.app')

@section('content')
<style>
    tr{margin-top: 12px;}
    .qr svg{
        margin: auto;
    }
    .print-dev{
        margin-right: auto;
    }
    .print-dev .print{
        padding: 12px 45px;

        background: #1e40af;
        color: white;
        border-radius: 11px;
    }
</style>
<div class="content">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.3/dist/js/tom-select.complete.min.js"></script>
                <!-- BEGIN: Top Bar -->
                @include('layout.partial.topbar')
                <!-- END: Top Bar -->
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
        @endif
        @if($errors->any())
            <div class="alert alert-warning">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
             </div>
        @endif
        <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="intro-y col-span-6 lg:col-span-6    ">
                @if($shipments!= null)
{{--                    @php--}}
{{--                        $a = array();--}}
{{--                        @endphp--}}
{{--                    @foreach($shipments as $shipment)--}}
{{--                    @php--}}
{{--                    array_push($a,$shipment->code_)--}}
{{--                    @endphp--}}
{{--             @endforeach--}}
{{--                {{$a}}--}}
                @foreach($shipments as $shipment)
                <div class="intro-y box mt-5 lg:mt-0 mb-10">
                    <div class="relative flex items-center p-5">


                        <div class="ml-6 w-96" >
                            <div class="font-medium text-base" >اسم العميل :  {{$shipment->client_name_}}</div>
                            <div class="text-slate-500">الاسم التجاري : {{$shipment->commercial_name_}}</div>
                            <div class="text-slate-500">الكود : {{$shipment->code_}}</div>

                        </div>
                        <div class="ml-6 items-end print-dev ">
                            <a href="{{route("shiments.print")}}?pdf=1&code={{$shipment->code_}}" class="print">طباعة</a>

                        </div>

                    </div>
                    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                        <table style="font-size: 16px;">
                            <tr><td style="width: 45%">&nbsp;&nbsp; <span>مبلغ الشحنة :</span></td><td><span>{{$shipment->shipment_coast_}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>هاتف المستلم :</span></td><td><span>{{$shipment->reciver_phone_}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>المحافظة :</span></td><td><span>{{$shipment->mo7afza_}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>المنطقة :</span></td><td><span>{{$shipment->mantqa_}}</span></td></tr>

                            <tr><td>&nbsp;&nbsp;<span>العنوان : </span></td><td><span>{{$shipment->el3nwan}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>الفرع :</span></td><td><span>{{$shipment->branch_	}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>مندوب التسليم :</span></td><td><span>{{$shipment->mandoub_taslim	}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>الحالة :</span></td><td><span>{{$shipment->Shipment_status->name_	}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>التاريخ : </span></td><td><span>{{$shipment->date_}}</span></td></tr>
                            <tr><td>&nbsp;&nbsp;<span>تاريخ الحالة : </span></td><td><span>{{$shipment->tarikh_el7ala}}</span></td></tr>

                            <tr><td>&nbsp;&nbsp;<span>الملاحظات : </span></td><td><span>{{$shipment->notes_}}</span></td></tr>
                        </table>

                    </div>
                    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                    <table style="font-size: 16px;">
                        <tr><td style="width: 45%">&nbsp;&nbsp; <span>مكان الشحنة : </span></td><td><span>{{$shipment->Ship_area_}}</span></td></tr>
                        <tr><td>&nbsp;&nbsp;<span>تسديد العميل :</span><span>{{$shipment->el3amil_elmosadad}}</span></td><td>&nbsp;&nbsp;<span>تاريخ التسديد العميل:</span></td><td><span>{{$shipment->tarikh_tasdid_el3amil}}</span></td></tr>
                        <tr><td>&nbsp;&nbsp;<span>تسديد المندوب :</span><span>{{$shipment->elmandoub_elmosadad_taslim}}</span></td><td>&nbsp;&nbsp;<span>تاريخ التسديد المندوب:</span></td><td><span>@if(isset($shipment->tarikh_tasdid_mandoub_eltaslim)){{$shipment->tarikh_tasdid_mandoub_eltaslim}} @elseif(isset($shipment->tarikh_tasdid_mandoub_eltaslim)){{$shipment->tarikh_tasdid_mandoub_eltaslim}}@endif</span></td></tr>
                        <tr><td>&nbsp;&nbsp;<span>تسديد الفرع الاول : </span><span>{{$shipment->elfar3_elmosadad_mno}}</span></td><td>&nbsp;&nbsp;<span>تاريخ التسديد الفرع الاول:</span></td><td><span>{{$shipment->tarikh_tasdid_far3}}</span></td></tr>
                        <tr><td>&nbsp;&nbsp;<span>تسديد الفرع الثاني :</span><span>{{$shipment->elfar3_elmosadad_mno_2}}</span></td><td>&nbsp;&nbsp;<span>تاريخ التسديد الفرع الثاني:</span></td><td><span>{{$shipment->tarikh_tasdid_far3_2}}</span></td></tr>

                    </table>
                    <div class="qr" style="margin-top: 50px; text-align: center">
                        {!! QrCode::size(100)->generate($shipment->code_) !!}

                    </div>
                    </div>
                </div>



                @endforeach
                @else
                    يم يتم العثور على شحنات
                @endif

            </div>
        </div>


</div>

<script>
    var  manteka =new TomSelect("#manteka",{
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	create: false
});
$('#mo7afza').on('change', function() {
        $('#data-error-mo7afza').hide();
                  var mo7afza_id = this.value;
                      $("#manteka").html('');
                      if(mo7afza_id == '') return;
                      $.ajax({
                          url:"{{url('getManateqByMa7afza')}}?mo7afza="+mo7afza_id+"&bycode=1",
                          type: "get",
                          data: {
                          },
                          dataType : 'json',
                          success: function(result){
                              console.log(result.all)
                          $('#manteka').prop('disabled', false);
                          //$('#manteka').html('<option value="">...</option>');
                          manteka.clearOptions();
                          var temp = ''; var f=0;
                          $.each(result.all,function(key,value){
                                if(f==0   ){ f=1;  temp = value.code;  }
                                manteka.addOption({
                                    id: value.name,
                                    title: value.name,

                                });
                                manteka.setValue(temp);
                            });
                          }
                      });
    });
</script>
@endsection
