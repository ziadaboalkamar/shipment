@extends('layout.app')

@section('content')

<div class="content">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.3/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.3/dist/js/tom-select.complete.min.js"></script>

     <!-- BEGIN: Notification Content -->
     <div id="basic-non-sticky-notification-content" class="toastify-content hidden flex">
         <div class="font-medium">تم الحفظ بنجاح</div>
         <a class="font-medium text-primary dark:text-slate-400 mt-1 sm:mt-0 sm:ml-40" href=""> </a> </div>
         <!-- END: Notification Content --> <!-- BEGIN: Notification Toggle -->
         <button id="basic-non-sticky-notification-toggle" class="btn btn-primary mr-1" style="display:none;">Show Non Sticky Notification</button>

    <script>

    </script>
                <!-- BEGIN: Top Bar -->
                @include('layout.partial.topbar')
                <!-- END: Top Bar -->

                <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
                    <!-- BEGIN: Post Content -->
                    <div class="intro-y col-span-12 lg:col-span-8">

                        <div class="post intro-y overflow-hidden box mt-5">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                                <div id='msgs' class="alert " style=" display:none;">
                                    <p></p>
                                    <ul class="cerror" id='cerror'>
                                        <li> </li>
                                        {{-- @if($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        @endif --}}
                                    </ul>
                                </div>

                            <div class="post__content tab-content">
                                <form action="{{route('shiments.store')}}" method="POST" id="shipment_form">
                                    @csrf
                                    <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby=	"content-tab">
                                    <div class="form-inline">
                                        <label for="date" class="form-label sm:w-20">التاريخ</label>
                                        <input type="text" name="date" class="form-control"   value="{{$now}}" disabled>
                                        <input type="hidden" name="date" class="form-control"   value="{{$now}}" >

                                    </div>
                                    @if(!$code_ai)
                                    <div class="form-inline mt-3">
                                        <label for="rakam-wasl" class="form-label sm:w-20">رقم الوصل</label>
                                        <input id="rakam-wasl" type="text" class="form-control"  name="code" />

                                    </div>
                                    <small class="warring data-error" id='data-error-code' style="margin-right: 100px;" hidden></small>
                                    @endif
                                    <div class="form-inline mt-3">
                                        <label for="3amil-name" class="form-label sm:w-20">اسم العميل</label>
                                        <select class="form-control client_id " id='client_id' name="client_id" data-clear='{{$clearFileds['remove_client_name']}}'>
                                            <option value=""></option>
                                            @foreach ($clients as $client)
                                             <option value="{{$client->code_}}">{{$client->name_}}</option>
                                             @endforeach
                                        </select>

                                        <script>
                                            let clientSelect = new TomSelect(".client_id",{});
                                        </script>
                                    </div>
                                    <small class="warring data-error" id='data-error-client_id' style="margin-right: 100px;" hidden></small>
                                    <div class="form-inline mt-3">
                                        <label for="commercial-name" class="form-label sm:w-20 ">الاسم التجارى</label>
                                        <select class="Commercial_name form-control" id='Commercial_name' name="Commercial_name" data-clear='{{$clearFileds['remove_commercial_name']}}'>
                                        </select>
                                    </div>
                                    <small class="warring data-error" id='data-error-Commercial_name' style="margin-right: 100px;" hidden></small>

                                    <div class="form-inline mt-3">
                                        <label for="reciver_phone_" class="form-label sm:w-20">هاتف المستلم</label>
                                        <input id="reciver_phone_" type="text" class="form-control"  name="reciver_phone_" maxlength="{{$phoneLength}}" minlength="{{$phoneLength}}"/>
                                    </div>
                                    <small class="warring data-error" id='data-error-reciver_phone_' style="margin-right: 100px;" hidden></small>

                                    <div class="form-inline mt-3">
                                        <label for="reciver_name_" class="form-label sm:w-20">اسم المستلم</label>
                                        <input id="reciver_name_" type="text" class="form-control"  name="reciver_name_" />
                                    </div>
                                    <div class="form-inline mt-3">
                                        <label for="mo7afaza" class="form-label sm:w-20 ">المحافظة</label>
                                        <select name="mo7afza" id='mo7afza' class="form-control mo7afza" data-clear='{{$clearFileds['remove_mo7fza']}}'>
                                            <option value=""></option>
                                            @foreach($mo7afazat as $mo7afaza)
                                            <option value="{{$mo7afaza->name}}"  >{{$mo7afaza->name}}</option>
                                            @endforeach
                                        </select>
                                        <script>
                                           let mo7afazaSelect = new TomSelect(".mo7afza",{});
                                        </script>
                                    </div>
                                    <small class="warring data-error" id='data-error-mo7afza' style="margin-right: 100px;" hidden></small>

                                    <div class="form-inline mt-3">
                                        <label for="horizontal-form-1" class="form-label sm:w-20">المنطقة</label>
                                        <select name="manteka" id='manteka'  class="form-control   mr-1"  style=" width:200px; margin-right:20px;" data-clear='{{$clearFileds['remove_mantka']}}'>

                                        </select>
                                        <label for="horizontal-form-1" class="form-label sm:w-20">العنوان</label>
                                        <input type="text" name="el3nwan" id="el3nwan" class="form-select form-select-sm mr-1" style=" width:400px; ">
                                    </div>
                                    <small class="warring data-error" id='data-error-manteka' style="margin-right: 100px " hidden></small>
                                    <small class="warring data-error" id='data-error-el3nwan' style="margin-right: 550px;" hidden></small>

                                    <div class="form-inline mt-3">
                                        <label for="horizontal-form-1" class="form-label sm:w-20">مبلغ الشحنه</label>

                                        <input id="shipment_cost" type="text" class="form-control   mr-1" name="shipment_coast_" aria-label="default input inline 1">

                                        <label for="tawsil_cost" class="form-label sm:w-20">مبلغ التوصيل</label>
                                        <input id="tawsil_cost" type="text" class="form-control col-span-2" name="tawsil_coast_"  aria-label="default input inline 1">
                                        <label for="total" class="form-label sm:w-20">الصافى</label>
                                        <input  id="total" type="text" class="form-control col-span-2"  aria-label="default input inline 1" name="total_">


                                    </div>
                                    <small class="warring data-error" id='data-error-shipment_coast_' style="margin-right: 100px;" hidden></small>



                                    <div class="form-inline mt-3">
                                        <label for="notes_" class="form-label sm:w-20">ملاحظات</label>
                                        <input id="notes_" type="text" class="form-control"   name="notes_"/>
                                    </div>


                                    <div class="sm:ml-20 sm:pl-5 mt-5">
                                        <button class="btn btn-primary">حفظ</button>
                                    </div>
							    </div>
                            </form>

							</div>
                        </div>
                    </div>
                    <!-- END: Post Content -->
                    <!-- BEGIN: Post Info -->

                    <!-- END: Post Info -->
                </div>
</div>


<script>
  var shipment_cost =   document.getElementById("shipment_cost");
  shipment_cost.onkeyup = function(e) {
        if (e.key == " " ||
            e.code == "Space" ||
            e.keyCode == 32
        ) {
            var cost = shipment_cost.value * 1000
            shipment_cost.value = cost

        }
    }
document.getElementById("client_id").focus();
var  comName =new TomSelect(".Commercial_name",{
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	create: false
});
var  manteka =new TomSelect("#manteka",{
	valueField: 'id',
	labelField: 'title',
	searchField: 'title',
	create: false
});
$('#Commercial_name').on('change', function() {
    $('#data-error-Commercial_name').hide();
})
$('#reciver_phone_').on('change', function() {
    $('#data-error-reciver_phone_').hide();
})

$('#el3nwan').on('change', function() {
    $('#data-error-el3nwan').hide();
})


    $('#data-error-manteka').hide();
    $('#client_id').on('change', function() {
                  $('#data-error-client_id').hide();
                  var client_id = this.value;
                      $("#Commercial_name").html('');
                      if(client_id == '') return;
                      $.ajax({
                          url:"{{url('getCommertialnameBy3amil')}}?client_id="+client_id+"&bycode=1",
                          type: "get",
                          data: {

                          },
                          dataType : 'json',
                          success: function(result){

                          $('#Commercial_name').prop('disabled', false);
                         var temp = ''; var f=0;
                          comName.clearOptions();
                          $.each(result.all,function(key,value){
                            if(f==0){
                                f=1;
                                temp = value.name_;
                            }
                              comName.addOption({
                                id: value.name_,
                                title: value.name_,

                            });
                            comName.setValue(temp);
                          });
                          }
                      });
    });
    $('#mo7afza').on('change', function() {
        $('#data-error-mo7afza').hide();
                  var mo7afza_id = this.value;
                      $("#manteka").html('');
                      if(mo7afza_id == '') return;
                      $.ajax({
                          url:"{{url('getManateqByMa7afza')}}?mo7afza="+mo7afza_id+"&bycode=0",
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
                                if(f==0   ){ f=1;  temp = value.name;  }
                                manteka.addOption({
                                    id: value.name,
                                    title: value.name,

                                });
                                manteka.setValue(temp);
                            });
                          }
                      });
    });
    $('#manteka').on('change', function() {
        $('#data-error-manteka').hide();
                    var manteka_id = this.value;

                    var client_id  = $('#client_id ').find(":selected").val();
                    var mo7afza_id  = $('#mo7afza ').find(":selected").val();

                        $("#tawsil_cost").html('');
                        if(manteka_id == '') return;
                        $.ajax({
                            url:"{{url('getTawsilByManteka')}}?bycode=0&client_id="+client_id+'&mo7afza_id='+ mo7afza_id+'&manteka_id='+manteka_id ,
                            type: "get",
                            success: function(result){

                                  $('#tawsil_cost').val(result.all);
                                  if($('#shipment_cost').val() !='')
                                  var total = (parseInt($('#shipment_cost').val()) - parseInt($('#tawsil_cost').val()));
                                    total = total || 0
                                    $('#total').val(total   )
                            }
                        });
      });
    $('#shipment_cost').on('keyup',function(){
        var total = (parseInt($('#shipment_cost').val()) - parseInt($('#tawsil_cost').val()));
        total = total || 0
            $('#total').val(total   )
    })
    $('#shipment_cost').on('change', function() {
    $('#data-error-shipment_coast_').hide();
})

    $('#shipment_form').on("submit",function(e){
        e.preventDefault();
        save_shipment()
    });
    $('#shipment_cost').on("keyup",function(e){
        if(e.keyCode == 13){
           // save_shipment()
        }
    } )

    $('#rakam-wasl').on("keyup",function(e){
        let code= $(this).val();
        $.ajax({
        url:"{{route('shiments.isCodeUsed')}}?code="+code,
        type: "get",
        success: function(result){
            $('#data-error-code').show()
           if(result.data == true)
            $('#data-error-code').css('color','red').text('هذا الرقم غير متاح');
            else
            $('#data-error-code').css('color','green').text('هذا الرقم  متاح');

        }
    });

    } )

    function save_shipment() {
        // var formData = new FormData($('#shipment_form')[0]);
        var formData = $('#shipment_form').serializeArray();
        $('.data-error').hide();
        var data={}
        var flg=0;
        formData.forEach(element =>
        {

            if(element['id'] != undefined){
                //console.log($('#'+element['id']))
              //  $('#'+element['id']).val('')
            }
            data[element['name']]= element['value'] ;
            if(element['name']!='notes_' && element['name']!='reciver_name_' && element['name']!='code'){

                if(element['value'] =='' || element['value'] == null)
                {
                    //console.log(element['value'] ,element)
                    flg=1;
                    var tag_id= '#data-error-'+element['name'];
                    //console.log(tag_id);
                    $(tag_id).show();
                    $(tag_id).css('color','red').text('هذا الحقل مطلوب');
                    //$("#cerror").append('<li>'+element['name'] +' is required</li>');
                }
            }
        });


       if(flg) {




       // $('msgs').addClass( "alert alert-danger" );
        return;
    };
        $.ajax({
                    url:"{{route('shiments.store')}}",
                    type: "post",
                    data: data,

                    success: function(result){
                        //console.log(result)
                        $('#rakam-wasl').val('');
                        if($('#rakam-wasl').length>0)
                            document.getElementById("rakam-wasl").focus();
                        else
                            document.getElementById("client_id").focus();
                        $('.warring').text('');
                        $("#cerror").css('backgroud-color','green').text('');
                        $("#cerror").append('<li> تم الحفظ بنجاح</li>');
                        if($('#manteka').data('clear'))
                            manteka.clear();
                        if($('#Commercial_name').data('clear'))
                            comName.clear();
                        if($('#client_id').data('clear'))
                            clientSelect.clear();
                        if($('#mo7afza').data('clear'))
                            mo7afazaSelect.clear();
                            $( "#basic-non-sticky-notification-toggle" ).trigger( "click" );

                            $('#notes_').val('');
                            $('#el3nwan').val('');
                            $('#shipment_cost').val('');
                            $('#reciver_phone_').val('');
                            $('#reciver_name_').val('');
                        // $(':input','#shipment_form')
                        // .not(':button, :submit, :reset, :hidden')
                        // .val('');
                        // .prop('checked', false)
                        // .prop('selected', false);

                    },
                    fail: function(result){

                        result.errors.forEach(element => {
                            $("#cerror").text('');
                            $("#cerror").append('<li>'+element+'</li>');
                        });
                    }
                });
    }
</script>
@endsection
