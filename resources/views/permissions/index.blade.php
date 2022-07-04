@extends('layout.app')

@section('content')

<div class="content">
    <!-- BEGIN: Top Bar -->
    @include('layout.partial.topbar')
    <!-- END: Top Bar -->
    <div id="msg_modal" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body px-5 py-10">
                    <div class="text-center">
                        
                        
                          <div class="form-inline" style="font-size: 24px; align-items:center;">
                            <p id='msg_modal_text' style="margin: auto;"></p>
                          </div>
                         <button type="button" data-tw-dismiss="" id='msg_modal_close' class="btn btn-primary w-24 mt-5">استمرار</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modal Toggle --> <!-- BEGIN: Modal Content --> 
    
<div id="type_modal" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body px-5 py-10">
                <div class="text-center">
                    <div class="mb-5" style="font-size: 25px">اسم الموظف</div>
                    <div class="form-inline">
                        @if(!empty($users))
                        <select class=" form-select-lg sm:mt-2 sm:mr-2 mb-5 tom-select  w-full" id='select_type' aria-label=".form-select-lg example">
                            @foreach ($users as $user)
                             <option value="{{$user->code_}}">{{$user->name_}}</option>
                             @endforeach
                        </select>
                        @endif
                      </div>
                      
                     <button type="button" data-tw-dismiss="" id='modal_close' class="btn btn-primary w-24 mt-5">استمرار</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Modal Content -->
@if(isset(request()->user_id ))
<div class="intro-y flex items-center mt-8" >
    <h2 class="text-lg font-medium ml-auto" >
        اسم الموظف: {{$selected_user->name_}}
    </h2>
</div>
<form method="post" action="{{route('permissions.store')}}">
    <input type="submit" class="btn btn-primary" value="حفظ">
    @csrf
    <input type="hidden" value="{{$selected_user->code_}}" name="user_id">
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Input -->
            <div class="grid grid-cols-12 gap-6 mt-5">
                @foreach($permisssions as $key=> $permisssion)
                <div class="intro-y col-span-12 lg:col-span-6">
                    <div class="intro-y box">
                        <div class="overflow-x-auto mt-5 " style="padding: 20px;">
                            <div> 
                                <label>{{$key}}</label>
                                @foreach($permisssion as $perm)
                                    <div class="form-check mt-2"> 
                                        <input id="checkbox-switch-1" class="form-check-input" type="checkbox" name='{{$perm->name}}' @if($selected_user->isAbleTo($perm->name))checked @endif> 
                                        <label class="form-check-label mr-3" for="checkbox-switch-1" >{{$perm->display_name}}</label>
                                     </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
              

                
                
            </div>
        </div>

    </div>
</form>
@endif
    {{-- <div class="intro-y  grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Item List -->
         
 
        <div class="intro-y col-span-12 lg:col-span-12">
            
            
            
           
        </div>
        
        <!-- END: Item List -->
        <!-- BEGIN: Ticket -->
       
        <!-- END: Ticket -->
    </div> --}}
    <!-- BEGIN: New Order Modal -->
    
    <!-- END: Add Item Modal -->
    
    <div class="mt-10">
       
    </div>

</div>

        <script type="text/javascript">
       
            let  shipments=[];
            let cnt=1;
           
            let current_status=0;
            $( document ).ready(function() {
                $("body").fadeIn(50);
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));

                @if(!isset(request()->user_id ))
                     myModal.show();
                @endif
            });
            
            $( "#modal_close" ).click(function() {
                
                
                current_status=$( "#select_type" ).val();
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
                var noClientFilter = $('#noClientFilter').is(':checked');
                let user_id = current_status;
                
                    myModal.hide();

               
                        
                    window.location.href = "{{route('permissions')}}?user_id="+user_id;
                    
            });
            $( "#msg_modal_close" ).click(function() {
                const msg_Modal = tailwind.Modal.getOrCreateInstance(document.querySelector("#msg_modal"));
                msg_Modal.hide();
            });
            $( "#qr_new" ).click(function() {
                $('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
                    cnt=1;
                    shipments=[];
                $('#shipment_form').find("input[type=text], textarea").val("");
                const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
                myModal.show();
            });
             

            
                $( "#tanfez" ).click(function() {
                 
                    $.ajax({
                        url: "{{route('shipment.t7wel_qr_save')}}" ,
                        type: 'post',
                        data:{ code:shipments, status:current_status, _token: "{{ csrf_token() }}"},
                        error: function(e){
                            console.log(e);
                        },
                        success: function(res) {
                            alert('تم التحويل بناح');
                        }
                    });
                     
                });
                $( "#cancel" ).click(function() {
                    $('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
                    cnt=1;
                    shipments=[];
                });
                
                $( "#QR" ).keyup(function(e){
                    if(e.keyCode == 13)
                    {
                        var qr = ( $('#QR').val());
                        //  console.log(qr);
                        if(shipments.includes(qr)) return;
                        if(qr=='') return;
                        $.ajax("{{route('getShipmentsByCode')}}"+"?code="+qr+"&status="+current_status+"&case=t7wel_7ala_qr",   // request url
                            {
                            
                                success: function (data, status, xhr) {
                                    
                                    
                                    if(shipments.includes(qr)) return;
                                    shipments.push(qr);
                                    //sconsole.log(shipments.includes(qr) ,shipments);
                                    var res = (data.data)[0];
                                    $('#rakam_tawsel').val(res.code_);
                                    $('#3amel_name').val(res.client_name_);
                                    $('#commercial_name').val(res.commercial_name_);
                                    $('#mostalem_phone').val(res.reciver_phone_);
                                    $('#mo7afza').val(res.mo7afza_);
                                    $('#manteka').val(res.mantqa_);
                                    $('#3nwan').val(res.el3nwan);
                                    $('#cost').val(res.shipment_coast_);
                                    $('#tawsil_cost').val(res.tawsil_coast_);
                                    $('#safi').val(res.total_);
                                
                                
                                    // success callback function
                                    //$('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
                                    
                                    
                                        $('#manteka-table   tr:last').after(`<tr class='' >
                                            <td>`+cnt+`</td>
                                            <td>`+res.code_+`</td>
                                            <td >`+(res.client_name_)+`   </td> 
                                            <td >`+(res.reciver_phone_)+`   </td> 
                                            
                                            <td  >`+(res.mo7afza_)+`</td>
                                            <td  >`+(res.shipment_coast_)+`</td>
                                        <td>
                                        
                                            </td>
                                            </tr>`
                                            );
                                            cnt++;
                            },
                            error: function (request, status, error) {
                                alert("خطأ فى ادخال الشحنة");
                            }
                        });
                    }
                });

                
                $( "#tasdid" ).click(function() {
                    
                 var codes =[]
                 
                 $('.check_count').each(function() {
                    if($(this).is(':checked')){
                        codes.push($(this).data('code'));
                    }
                });
                
                 $.ajax({
                     url: "{{route('accounting.3amil.canceltasdid')}}" ,
                     type: 'post',
                     data:{ code:codes,  _token: "{{ csrf_token() }}"},
                     error: function(e){
                         console.log(e);
                     },
                     success: function(res) {
                        
                         rowsAffected =  codes.length - res['count']
                         msg =" تم تسديد " +res['count']+   " شحنة  "  +" تم رفض " + rowsAffected + " شحنة ";
                         let msg_modal = tailwind.Modal.getOrCreateInstance(document.querySelector("#msg_modal"));
                        $('#msg_modal_text').text(msg)
                         msg_modal.show();
                        let total_cost=parseInt($('#total_cost').val());
                        let total_cnt=parseInt($('#total_cnt').val());
                        let total_tawsil=parseInt($('#total_tawsil').val());
                        let total_net= parseInt($('#total_net').val($('#total_cost').val()-$('#total_tawsil').val()));
                        var i=1; 
                        $('.check_count').each(function() {
                            
                            if($(this).is(':checked') && $(this).data('status')==7){
                                
                                total_cnt--;
                                total_cost-= $(this).data('cost');
                                total_tawsil-= parseInt($(this).data('t7wel'));
                                total_net-= $(this).data('net');
                                $('#total_cost').val(total_cost);
                                $('#total_tawsil').val(total_tawsil);
                                $('#total_net').val($('#total_cost').val()-$('#total_tawsil').val());
                                $('#total_cnt').val(total_cnt);
                                
                                $(this).parent().parent().remove();
                                
                                
                            }else{
                                $(this).parent().parent().children('td:first').text(i)
                                i++;

                            }
                      });
                     }
                 });
                  
             });


                    
             $(document).on('change', '.check_count', function(){ 
                
                let total_cost=parseInt($('#total_cost').val());
                let total_cnt=parseInt($('#total_cnt').val());
                let total_tawsil=parseInt($('#total_tawsil').val());
                let total_net= parseInt($('#total_net').val($('#total_cost').val()-$('#total_tawsil').val()));
                if($(this).is(':checked'))
                {
                    total_cnt++;
                    total_cost+= $(this).data('cost');
                    total_tawsil+= parseInt($(this).data('t7wel'));
                    total_net+= $(this).data('net');
                }
                else 
                {
                    total_cnt--;
                    total_cost-= $(this).data('cost');
                    total_tawsil-= parseInt($(this).data('t7wel'));
                    total_net-= $(this).data('net');
                }
                $('#total_cost').val(total_cost);
                $('#total_tawsil').val(total_tawsil);
                $('#total_net').val($('#total_cost').val()-$('#total_tawsil').val());
                $('#total_cnt').val(total_cnt);
        });
                

                    $("#checkAll").click(function(){
                        $('.wasel_goz2y').css("background-color", "yellow");
                       // $('table tbody input:checkbox').not(this).prop('checked', this.checked);
                        let total_cost=parseInt($('#total_cost').val());
                        let total_cnt=parseInt($('#total_cnt').val());
                        let total_tawsil=parseInt($('#total_tawsil').val());
                        let total_net= parseInt($('#total_net').val($('#total_cost').val()-$('#total_tawsil').val()));

                        if($(this).is(':checked'))
                            var items=$('table tbody input:checkbox:not(:checked)')  
                        else
                            var items= $('table tbody input:checkbox:checked') 
                            items.each(function(){
                               
                            
                        if(!$(this).is(':checked'))
                        {
                            total_cnt++;
                            total_cost+= parseInt($(this).data('cost'));
                            total_tawsil+= parseInt($(this).data('t7wel'));
                            total_net+= parseInt($(this).data('net'));
                            $(this).prop('checked', 1);
                        }
                        else 
                        {
                            total_cnt--;
                            total_cost-= $(this).data('cost');
                            total_tawsil-= parseInt($(this).data('t7wel'));
                            total_net-= $(this).data('net');
                            $(this).prop('checked', 0);
                        }
                        

                        });
                        $('#total_cost').val(total_cost);
                        $('#total_tawsil').val(total_tawsil);
                        $('#total_net').val($('#total_cost').val()-$('#total_tawsil').val());
                        $('#total_cnt').val(total_cnt);
                });
                        
                $( ".filterByEnter" ).keyup(function(e){
                    if(e.keyCode == 13)
                    {
                        $('#filter_form').submit();
                    }
                });

                $('#client_id').on('change', function() {
                  
                    var client_id = this.value;
                        $("#Commercial_name").html('');
                        $.ajax({
                            url:"{{url('getCommertialnameBy3amil')}}?client_id="+client_id,
                            type: "get",
                            data: {
                                
                            },
                            dataType : 'json',
                            success: function(result){
                            $('#Commercial_name').prop('disabled', false);
                            $('#Commercial_name').html('<option value="">...</option>');
                            
                            $.each(result.all,function(key,value){
                                $("#Commercial_name").append('<option value="'+value.name_+'">'+value.name_+'</option>');
                            });
                            //$('#city_id').html('<option value="">Select city</option>'); 
                            }
                        });
                });    

            var page = 0;
            let cont=0;
       
            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() +1    >= $(document).height()) {
                    page++;
                    cont=$('#dataTable   tr:last td:first-child').text();
                    infinteLoadMore(page);
                }
            });
            function infinteLoadMore(page) {
                $.ajax({
                    url: "{{route('accounting.3amil.mosadad')}}"+ "?lodaMore=1&page=" + page+'&'+window.location.search.substr(1),
                
                    type: "get",
                    beforeSend: function () {
                        
                    }
                })
                .done(function (response) {
                    if (response.length == 0) {
                    
                        return;
                    }
                    $.each(response.data,function(key,value){
                        
                        cont++;
                        var client = '';
                        if (typeof value.client != 'undefined' &&  value.client != null){client = (value.client)['name_'];}else{client =value.client_name_}
                        $('#dataTable   tr:last').after(`<tr  class='status_`+value.Status_+`_color'>
                            <td  class="whitespace-nowrap " >`+cont+`</td>
                            <td  class="whitespace-nowrap " >`+value.mo7afza_+`</td>
                            <td  class="whitespace-nowrap " >`+value.reciver_phone_+`</td>
                            <td  class="whitespace-nowrap " >`+value.commercial_name_+`</td>
                            <td  class="whitespace-nowrap " >`+ client+`</td>
                            <td  class="whitespace-nowrap " >`+value.date_+`</td> 
                            <td  class="whitespace-nowrap " >`+value.tarikh_tasdid_el3amil+`</td> 
                            <td  class="whitespace-nowrap " >`+value.branch_+`</td>
                            <td  class="whitespace-nowrap " >`+value.total_+`</td>
                            <td  class="whitespace-nowrap " >`+value.tawsil_coast_+`</td>
                            <td  class="whitespace-nowrap " >`+value.shipment_coast_+`</td>
                            <td  class="whitespace-nowrap " >`+value.code_+`</td>
                            <td class="whitespace-nowrap " ><input type="checkbox" class="check_count" data-cost='`+value.shipment_coast_+`'
                                        data-t7wel='`+value.tawsil_coast_+`' data-net='`+value.shipment_coast_+`' data-code='`+value.code_+`' data-status='`+value.Status_+`'></td>                
                                            </tr>`
                            );

                            
                            //rows_counter()
                    });
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
            }

               
            </script>
@endsection
