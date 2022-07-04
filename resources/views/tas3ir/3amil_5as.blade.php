@extends('layout.app')

@section('content')



<div class="modal fade fixed top-0 left-0  w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
      <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
        <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalScrollableLabel">
          Modal title
        </h5>
        <button type="button"
          class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
          data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body relative p-4">
        <p>This is a vertically centered modal.</p>
      </div>
      <div
        class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
        <button type="button"
          class="inline-block px-6 py-2.5 bg-purple-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out"
          data-bs-dismiss="modal">
          Close
        </button>
        <button type="button"
          class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
          Save changes
        </button>
      </div>
    </div>
  </div>
</div>






<div class="content">
                <!-- BEGIN: Top Bar -->
    <!-- END: Modal Toggle --> <!-- BEGIN: Modal Content -->
    <div id="type_modal" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-body px-5 py-10">
                    <div class="text-center">
                        <div class="mb-5" style="font-size: 25px">العميل الخاص</div>
                        <div class="form-inline">
                    @isset($specialClients)

                            <select name="3amel5ase"  class="form-select form-select-lg sm:mt-2 sm:mr-2 mb-5" id='3amel5ase' aria-label=".form-select-lg example">
                                <option value="0">اختار العميل الخاص</option>
                                @foreach($specialClients as $client)
                                <option value="{{$client->code_}}">{{$client->name_}}</option>
                                @endforeach
                            </select>

                            @endisset
                        </div>
                        <button type="button" data-tw-dismiss="" id='modal_close' class="btn btn-primary w-24">استمرار</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Modal Content -->
    @include('layout.partial.topbar')
                <!-- END: Top Bar -->
                <div class="intro-y flex items-center mt-8">

                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: Right Basic Table -->
                        <div class="intro-y box">
                            <div class=" p-5 border-b border-slate-200/60">
                                <h2 class="font-medium text-base mr-auto">
                                     المحافظات
                                </h2>

                            </div>
                            <div class="p-5" id="basic-table">
                                <div class="preview">
                                    <div class="overflow-x-auto">
                                        <table class="table" id='mo7afa-table'>
                                            <thead>
                                                <tr>
                                                    <th class="whitespace-nowrap">#</th>
                                                    <th class="whitespace-nowrap">الاسم</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php $i=1; @endphp
                                                @foreach($cities as $city)
                                                <tr class="mo7afza-row" data-id='{{$city->name}}'>
                                                    <td>{{$i}}</td>
                                                    <td>{{$city->name}}</td>

                                                </tr>
                                                @php $i++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- END: Basic Table -->

                    </div>

                     <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: right Basic Table -->
                        <div class="intro-y box">
                            <div class=" p-5 border-b border-slate-200/60">
                                <h2 class="font-medium text-base mr-auto">
                                   المناطق
                                </h2>

                            </div>
                            <div class="p-5" id="basic-table">
                                <div class="preview">
                                    <div class="overflow-x-auto">
                                        <table class="table" id='manteka-table'>
                                            <thead>
                                                <tr class="mantika-row">

                                                    <th class="whitespace-nowrap">الاسم</th>
                                                    <th class="whitespace-nowrap"> تسعير العميل الخاص</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class='mantika-row'>
                                                    <td>s</td>
                                                    <td>s</td>
                                                    <td>s</td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- END: Basic Table -->

                    </div>
                </div>
            </div>

<input type="hidden" name="name_of3amel5ase" id="name_of3amel5ase">
  <script type="text/javascript">

                $( ".mo7afza-row" ).click(function() {
                  var mo7afza = ( $(this).data('id'));
                    var specialClient = $('#name_of3amel5ase').val();
                 $.ajax("{{route('getManateqAndTas3ir5asByMa7afza')}}"+"?mo7afza="+mo7afza+"&specialClient="+specialClient,   // request url
                        {
                            success: function (data, status, xhr) {// success callback function
                                $('#manteka-table tr').not(function () {
                                    return !!$(this).has('th').length;
                                }).remove();

                                for (i = 0; i < data.sum; i++) {
                                    if (data.all[i].tas3ir_3amil_5as == null) {
                                        $('#manteka-table tbody ').after(`<tr class='mantika-row' >
                                        <td>` + data.mandobeEmpty[i].name + `</td>

                                        <td class='editable' data-type='mandobe'
                                         data-code='0' data-mo7afaza='` + mo7afza + `' data-manteqa='` + data.mandobeEmpty[i].name + `'>
                                                0
                                        </td>

                                        </tr>`
                                        );
                                    }
                                    else {
                                        // console.log(data.all[i]);
                                        $('#manteka-table tbody ').after(`<tr class='mantika-row' >
                                        <td>` + data.all[i].name + `</td>

                                        <td class='editable' data-type='mandobe'
                                         data-code='` + data.all[i].tas3ir_3amil_5as.code_ + `'
                                         data-mo7afaza='` + mo7afza + `' data-manteqa='` + data.all[i].name + `'>`
                                            + data.all[i].tas3ir_3amil_5as.price_ + `
                                        </td>

                                        </tr>`
                                        );

                                    }
                                }
                            }
                    });
                  $('#mo7afa-table tr').removeClass('selected');
                 $(this).addClass('selected');
                });


$('#manteka-table').on('dblclick', 'td', function(){

    var $this = $(this);
    var serial = ( $(this).data('code'));
    var type = ( $(this).data('type'));
    var url = "{{route('save_3amel_5as')}}" ;
    var mo7afza = ( $(this).data('mo7afaza'));
    var mnateqaName = ( $(this).data('manteqa'));
    var specialClient = $('#name_of3amel5ase').val();
    console.log(type);
    var $input = $('<input>', {
        value: $this.text(),
        type: 'text',

        blur: function() {
            if(this.value !='' )
            $.ajax({
            url: url ,
            type: 'post',
            data:{ serial:serial, value:this.value ,manteqa:mnateqaName,mo7afza:mo7afza ,specialClient:specialClient, _token: "{{ csrf_token() }}"},
            error: function(e){
                console.log(e);
            },
              success: function(res) {

                }
              });
           $this.text(this.value);
        },
        keyup: function(e) {
           if (e.which === 13) $input.blur();
        }
    }).appendTo( $this.empty() ).focus();
});
            </script>

<script type="text/javascript">
    let  shipments=[];
    let  selected=[];
    let cnt=1;

    let current_status=0;
    $( document ).ready(function() {
        const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
       // myModal.show();
    });

    $( "#modal_close" ).click(function() {

        $('#type').val($( "#select_type option:selected" ).text());
        current_status=$( "#select_type option:selected" ).val();
        const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
        myModal.hide();
    });
    $( "#qr_new" ).click(function() {
        $('#manteka-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
        cnt=1;
        shipments=[];
        $('#shipment_form').find("input[type=text], textarea").val("");
        const myModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#type_modal"));
        myModal.show();
    });

    $('#mandoube_name').hide();

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
        cnt=0;
        $('#manteka-table tr').each(function( index ) {
            if($( this ).hasClass('selected') )
            {
                //selected.push($(this).data('code'));
                $( this ).remove();
                var code=$(this).data('code')
                shipments.splice(shipments.indexOf(code)-1, 1)
                console.log(shipments);
            }else{
                $( this ).find("td:first").text(cnt);
                cnt++;
            }
        });
    });

    // Department Change



    $('#modal_close').click(function () {
        var amel5ase = $('#3amel5ase').val();
        $('#name_of3amel5ase').attr('value',amel5ase) ;

    });

    $('#manteka-table').on('click', 'tr', function(){
        var code = $(this).data('code')

        $(this).toggleClass('selected');

    });

</script>
@endsection
