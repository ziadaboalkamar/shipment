@extends('layout.app')

@section('content')
<style>
    <?php 
        foreach($status_color as $key => $value){
            echo ".{$key}>td { $css_prop : $value !important;}";
        }
    ?> 
    body {
  display: none;
}
</style>
<div class="content">
    <!-- BEGIN: Top Bar -->
    @include('layout.partial.topbar')
    <!-- END: Top Bar -->
    
    <!-- END: Modal Toggle --> <!-- BEGIN: Modal Content --> 

<!-- END: Modal Content -->
    
    <div class="intro-y  grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Item List -->
        
        <div class="intro-y col-span-12 lg:col-span-12">
            <form action="">
                <div>   
                    <div class="mt-1 grid  grid-cols-3">
                    <div class="col-span-2">
                        <div class="grid grid-cols-3 "> 
                            <div class="form-inline ">
                                <label for="horizontal-form-1" class="form-label " style=" text-align:left; margin-left:15px; margin-top:8px;  width:60px; ">الكود</label>
                                <input type="text" name="code" class="form-control form-select-sm filterByEnter"  aria-label="default input inline 1" style="width: 150px;" > 
                            </div>
                            <div class="form-inline">
                                <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:2px; margin-top:8px; width:30px; ;">تاريخ التسديد</label>
                                <input name="tasdid_date_from" type="date"  class="form-control form-select-sm "  aria-label="default input inline 1" style=""> 
                                <label for="horizontal-form-1" class="form-label" style=" text-align:right!important; margin-right:3px; margin-left:5px; margin-top:8px;  ">الي</label>
                                <input name='tasdid_date_to' type="date"  class="form-control form-select-sm "  aria-label="default input inline 1" style=""> 
                            </div>
                            
                            
                        </div > 
                    </div>
                    
                </div>
                    <div class="mt-1 grid  grid-cols-3">
                        <div class="col-span-2">
                            <div class="grid grid-cols-3 "> 
                                <div class="form-inline ">
                                    <label for="horizontal-form-1" class="form-label " style=" text-align:left; margin-left:15px; margin-top:1px;  width:60px; ">هاتف المستلم</label>
                                    <input type="text" name='reciver_phone'  class="form-control form-select-sm filterByEnter"  aria-label="default input inline 1" style="width: 150px;"> 
                                </div>
                                <div class="form-inline">
                                    <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:2px; margin-top:1px; width:30px; ">تاريخ الشحنه </label>
                                    <input name="hala_date_from" type="date"  class="form-control form-select-sm "  aria-label="default input inline 1" style=""> 
                                    <label for="horizontal-form-1" class="form-label" style=" text-align:right!important; margin-right:3px; margin-left:5px; margin-top:1px;  ">الي</label>
                                    <input name="hala_date_to" type="date"  class="form-control form-select-sm "  aria-label="default input inline 1" style=""> 
                                </div>
                                
                                
                            </div > 
                        </div>
                        
                        <div class="col-span-1">
                            
                        </div>
                    </div>
                    <div class="mt-1 grid  grid-cols-3">
                        <div class="col-span-2">
                            <div class="grid grid-cols-3 "> 
                                
                                
                                
                                <div class="form-inline">
                                    <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:10px; margin-top:8px; margin-right:3px ; width:50px"> </label>

                                    <input type="submit"  class="btn btn-primary  "  value="فلتر">
                                    
                                </div>
                            </div > 
                        </div>
                        <div>
                            @if(request()->get('client_id') != null)
                                <div class="form-inline align-left">
                                    <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:10px; margin-top:8px;  width:400px; "> </label>
                                    <input type="button"  class="btn btn-success  align-left" style="direction: ltr"  value="الغاء تسديد المحدد" id='tasdid' >
                                
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
            <div class="overflow-x-auto mt-5">
                <table class="table table-striped" id="dataTable">
                    <thead class="table-light">
                        <tr>
                                    
                            <th class="whitespace-nowrap">#</th>
                            <th class="whitespace-nowrap">المحافظة</th>
                            <th class="whitespace-nowrap">هاتف المستلم</th>
                            <th class="whitespace-nowrap">الاسم التجارى</th>
                            <th class="whitespace-nowrap">اسم العميل</th>
                            <th class="whitespace-nowrap">تاريخ الشحنه</th>
                            <th class="whitespace-nowrap">تاريخ التسديد</th>
                            <th class="whitespace-nowrap">الفرع</th>
                            <th class="whitespace-nowrap">الصافى</th>
                            <th class="whitespace-nowrap">اجره الشركه</th>
                            <th class="whitespace-nowrap">مبلغ الشحنه</th>
                                    <th class="whitespace-nowrap">الكود</th>
                                    <th class="whitespace-nowrap"></th>
                                    <th class="whitespace-nowrap"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($all as $shipment)
                        
                        <tr  class="status_{!!$shipment->Status_!!}_color"   >
                            <td  class="whitespace-nowrap " ><?php echo $i; $i++?></td>
                            <td  class="whitespace-nowrap " >{{$shipment->mo7afza_}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->reciver_phone_}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->commercial_name_}}</td>
                            <td class="whitespace-nowrap " >@if(isset($shipment->client)){{$shipment->client->name_}} @else {{$shipment->client_name_}}@endif</td>
                            <td class="whitespace-nowrap " >{{$shipment->date_}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->tarikh_tasdid_el3amil}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->branch_}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->total_}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->tawsil_coast_}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->shipment_coast_}}</td>
                            <td class="whitespace-nowrap " >{{$shipment->code_}}</td>
                            <td class="whitespace-nowrap " ><a href="{{route('shiments.edit',['code'=>$shipment->code_])}}"><i data-lucide="edit" class="check_count"
                                data-cost='{{$shipment->shipment_coast_}}'
                                data-t7wel='{{$shipment->tawsil_coast_}}' data-net='{{$shipment->shipment_coast_}}' data-code='{{$shipment->code_}}' data-status='{{$shipment->Status_}}'></i></a>
                                </td>
                                          <td class="whitespace-nowrap " ><a href="{{route('shiments.deleteShipment',['code'=>$shipment->code_])}}"><i data-lucide="trash" class=""
                                                                                                                               ></i></a>
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            
           
        </div>
        
        <!-- END: Item List -->
        <!-- BEGIN: Ticket -->
       
        <!-- END: Ticket -->
    </div>
    <!-- BEGIN: New Order Modal -->
    
    <!-- END: Add Item Modal -->
    
    <div class="mt-10">
       
    </div>
    <div style="background-color:#fff;  opacity: 1;position: fixed; bottom:0px; z-index:999; width:79%;" class="flex h-12 pt-3 rounded ">
        <div class="mr-6" style="margin-left: 10px;">اجمالى مبالخ الشحنات</div>
        <div class="total_cost" style="margin-left: 40px;"><input type="text" disabled class="h-6 w-40" id="total_cost" value="0"></div>
        <div class="f" style="margin-left: 10px;">اجمالى مبالغ التحويل</div>
        <div class="total_tawsil" style="margin-left: 40px;"><input type="text" disabled class="h-6 w-40" id="total_tawsil" value="0"></div>
        <div class=" " style="margin-left: 10px;">اجمالى الصافى</div>
        <div class="total_net" style="margin-left: 40px;"><input type="text" disabled class="h-6 w-40" id='total_net' value="0"></div>
        <div class=" " style="margin-left: 10px;">مجموع عدد الشحنات</div>
        <div class=""> <input type="text" disabled class="h-6 w-16" id="total_cnt" value="0"></div>

        <div style="margin-right:auto; margin-left:10px; margin-bottom:5px;"  class="dropdown inline-block" data-tw-placement="top"> <button class="dropdown-toggle btn btn-primary w-26 mr-1  h-6" aria-expanded="false" data-tw-toggle="dropdown"> اجماليات</button>
            <div class="dropdown-menu w-60">
                <ul class="dropdown-content">
                    <li> <a  class="dropdown-item"><span>{{$sums['totalCost']}}</span> <span style="margin-left:auto;">مبلغ الشحنات </span></a> </li>
                    <li> <a  class="dropdown-item"><span>{{$sums['tawsilCost']}}</span>   <span style="margin-left:auto;">أجرة الشركة</span> </a> </li>
                    <li> <a  class="dropdown-item"><span>{{$sums['netCost']}}</span>   <span style="margin-left:auto;">الصافى</span>   </a> </li>
                    <li> <a  class="dropdown-item"><span>{{$sums['allCount']}}</span>   <span style="margin-left:auto;">عدد الشحنات</span> </a> </li>

                    
                    
                </ul>
            </div>
        </div>
    </div>
</div>

        <script type="text/javascript">
       
            let  shipments=[];
            let cnt=1;
           
            let current_status=0;
            $( document ).ready(function() {
                $("body").fadeIn(50);
                

                
            });
            
            $( "#modal_close" ).click(function() {
                
                
                current_status=$( "#select_type" ).val();
                
                
                let client_id = current_status;
                
            });
           
            $( "#qr_new" ).click(function() {
                $('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
                    cnt=1;
                    shipments=[];
                $('#shipment_form').find("input[type=text], textarea").val("");
                
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
                                    
                                    console.log((data.data)[0]);
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
                                console.log($(this))
                            
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
                            console.log(result); 
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
                    url: "{{route('shiments.editview')}}"+ "?lodaMore=1&page=" + page+'&'+window.location.search.substr(1),
                
                    type: "get",
                    beforeSend: function () {
                        
                    }
                })
                .done(function (response) {
                    if (response.length == 0) {
                    
                        return;
                    }
                    $.each(response.data,function(key,value){
                        console.log(value.client);
                        cont++;
                        var client = '';
                       
                        var deleteurl = '{{ route("shiments.deleteShipment", ":code") }}';
                        var  editeurl = '{{ route("shiments.edit", ":code") }}';
                        deleteurl = deleteurl.replace(':code', value.code_);
                        editeurl = editeurl.replace(':code', value.code_);
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
                                           
                            <td class="whitespace-nowrap " >
                                <a href=`+editeurl+`>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="edit" data-lucide="edit" class="lucide lucide-edit block mx-auto"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    <i data-lucide="edit" class="check_count"
                                   data-cost=`+value.shipment_coast_+`
                                data-t7wel=`+value.tawsil_coast_+` data-net=`+value.shipment_coast_+` data-code=`+value.code_+` data-status=`+value.Status_+`></i></a>
                                </td>

                                        <td class="whitespace-nowrap " >
                                            <a href=`+deleteurl+`>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="trash" data-lucide="trash" class="lucide lucide-trash block mx-auto"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                                                </a></td>
                                        
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
