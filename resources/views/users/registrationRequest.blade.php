@extends('layout.app')

@section('content')

<div class="content">
    <!-- BEGIN: Top Bar -->
    @include('layout.partial.topbar')
    <!-- END: Top Bar -->

  
        <div id="example-tab-1" class="tab-pane leading-relaxed p-5 active" role="tabpanel" aria-labelledby="example-1-tab">
            <div class="intro-y  grid-cols-12 gap-5 mt-5 ">
                <!-- BEGIN: Item List -->
                
                <div class="intro-y col-span-12 lg:col-span-12 ">
                    <div>   
                        <form>
                        <div class="mt-1 grid  grid-cols-3">
                            <div class="col-span-2">
                                <div class="grid grid-cols-3 "> 
                                    
                                    <div class="form-inline">
                                        <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:15px; margin-top:8px; width:100px; ">الفرع</label>
                                        <select name="branch" class="form-select form-select-sm " aria-label=".form-select-sm example" style=" width:150px">
                                            <option value="">...</option>
                                            @foreach($branches as $branch)
                                            <option value="{{$branch->name_}}" @if(request()->get('branch') ==$branch->name_) selected @endif>{{$branch->name_}}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                    <div class="form-inline">
                                        <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:10px; margin-top:8px;  width:150px; ">المحافظة</label>
                                        <select name="mo7afza" class="form-select form-select-sm " aria-label=".form-select-sm example" style=" width:150px">
                                            <option value="">...</option>
                                            @foreach($mo7afazat as $mo7afaza)
                                            <option value="{{$mo7afaza->code}}"  @if(request()->get('mo7afza') ==$mo7afaza->code) selected @endif>{{$mo7afaza->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                    <div class="form-inline">
                                        {{-- <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:10px; margin-top:8px;  width:150px; ">العدد فى كل صفحة</label>
                                        <input value="{{request()->get('limit')}}" name="limit" type="text" class="form-select form-select-sm " aria-label=".form-select-sm example" style=" width:50px">
                                        <label for="horizontal-form-1" class="form-label" style=" text-align:left; margin-left:10px; margin-top:8px;  width:50px; ">الكل</label>
                                        <input type="checkbox" name='showAll' @if(request()->get('showAll') =='on') checked @endif > --}}
                                            
                                        <input type="submit" class="btn btn-primary mr-2" value="فلتر">
                                    </div > 
                            </div> 
                            
                            
                            <div></div>
                        </div>
                        
                    </div>
                    </form>
                    <div class="overflow-x-auto mt-5">
                        <table class="table table-striped " id='dataTable'>
                            <thead class="table-light">
                                <tr>
                                    <th class="whitespace-nowrap">الغاء</th>
                                    <th class="whitespace-nowrap">موافق</th>
                                    <th class="whitespace-nowrap">الاسم</th>
                                    <th class="whitespace-nowrap">الوظيفة</th>
                                    <th class="whitespace-nowrap">اسم المستخدم</th>
                                    <th class="whitespace-nowrap">المحافظة</th>
                                    <th class="whitespace-nowrap">الفرع</th>
                                    <th class="whitespace-nowrap">الكود</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                               
                                @if(count($all) >0)
                            
                                    
                                @foreach($all as $shipment)
                                <tr data-code="{{ $shipment->code_}}">
                                    <td class="whitespace-nowrap"><button class="btn btn-elevated-rounded-danger w-15   action_btn"  data-type="cancel">الغاء</button></td>
                                    <td class="whitespace-nowrap"><button class="btn btn-elevated-rounded-success w-15 action_btn"  data-type="accept">موافق</button></td>
                                    <td class="whitespace-nowrap">{{$shipment->name_}}</td>
                                    <td class="whitespace-nowrap">{{$shipment->type_}}</td>
                                    <td class="whitespace-nowrap">{{$shipment->username}}</td>
                                    <td class="whitespace-nowrap">{{$shipment->mo7fza}}</td>
                                    <td class="whitespace-nowrap">{{$shipment->branch}}</td>
                                    <td class="whitespace-nowrap">{{$shipment->code_}}</td>
                                </tr>
                                @endforeach
                                @endif
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
            <div class="mt-5">
                
            </div>
            
        </div>
        </div>
        
 


        <script type="text/javascript">
            let  shipments=[];
            let cnt=1;
           
            let current_status=0;
          
            
            
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
                        url: "{{route('accept_frou3_rag3_qr_save')}}" ,
                        type: 'post',
                        data:{ code:shipments,  _token: "{{ csrf_token() }}"},
                        error: function(e){
                            console.log(e);
                        },
                        success: function(res) {
                            alert('تم الموافقة');
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
                        $.ajax("{{route('accept_rag3_get')}}"+"?code="+qr+"&case=accept_rag3_get",   // request url
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

                $( ".action_btn" ).click(function() {
                 var type= $(this).data('type');
                 var code= $(this).parent().parent().data('code');
                 var r = $(this).parent().parent();
                 $.ajax({
                        url: "{{route('registrationRequestSave')}}" ,
                        type: 'post',
                        data:{ code:code, type:type, _token: "{{ csrf_token() }}"},
                        error: function(e){
                            alert('تم رفض العملية');
                            console.log(e);
                        },
                        success: function(res) {
                            r.remove();
                            alert('تمت العملية بنجاح');
                        }
                    });
                  
            })

            var page = 0;
            let cont=0;
       
            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height()     >= $(document).height()) {
                    page++;
                    cont=$('#dataTable   tr:last td:first-child').text();
                    infinteLoadMore(page);
                }
            });
            function infinteLoadMore(page) {
                $.ajax({
                    url: "{{route('registrationRequest')}}"+ "?lodaMore=1&page=" + page+'&'+window.location.search.substr(1),
                
                    type: "get",
                    beforeSend: function () {
                        
                    }
                })
                .done(function (response) {
                    console.log(response);
                    if (response.length == 0) {
                       
                        return;
                    }
                    $.each(response.data,function(key,value){
                        console.log(value.client);
                        cont++;
                        var client = '';
                        if (typeof value.client != 'undefined' &&  value.client != null){client = (value.client)['name_'];}else{client =value.client_name_}
                        $('#dataTable   tr:last').after(`<tr  class='status_`+value.Status_+`_color'>
                            <td class="whitespace-nowrap"><button class="btn btn-elevated-rounded-danger w-15   action_btn"  data-type="cancel">الغاء</button></td>
                            <td class="whitespace-nowrap"><button class="btn btn-elevated-rounded-success w-15 action_btn"  data-type="accept">موافق</button></td>
                            <td  class="whitespace-nowrap " >`+value.shipment_coast_+`</td>
                            <td  class="whitespace-nowrap " >`+value.mo7afza_+`</td>
                            <td  class="whitespace-nowrap " >`+ value.reciver_phone_+`</td>
                            <td  class="whitespace-nowrap " >`+value.commercial_name_+`</td>
                            <td  class="whitespace-nowrap " >`+value.tarikh_el7ala+`</td>
                            <td  class="whitespace-nowrap " >`+value.branch_+`</td>
                            <td  class="whitespace-nowrap " >`+value.code_+`</td>
                                               
                                            </tr>`
                                            );

                                            
                                
                    });
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
            }
                
            </script>
@endsection
