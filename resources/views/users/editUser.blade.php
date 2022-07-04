@extends('layout.app')

@section('content')
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
                            <div class="intro-y col-span-12 lg:col-span-8">

                                <div class="post intro-y overflow-hidden box mt-5">
                                <form action="{{route('updateUser',['code'=>$manadoub->code_])}}" method="post">
                                    <div class="post__content tab-content">
                                        <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby=  "content-tab">
                                            <div class="form-inline">
                                                <label for="date" class="form-label sm:w-20">اسم المندوب</label>

                                                    <input type="text" class="form-control col-span-4" name="mandoub_name"  value="{{$manadoub->name_}}"   style="width: 350px;">

                                            </div>
                                            <input type="hidden" value="{{$manadoub->code_}}" name="code_">
                                           {{-- <div class="form-inline mt-3">
                                            <label for="date" class="form-label sm:w-20">الفرع</label>
                                            <div class="grid grid-cols-12 gap-2">

                                                <select class="form-control col-span-4">
                                                    <option value=""> </option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        @csrf
                                            <div class="form-inline mt-3">
                                                <label for="username" class="form-label sm:w-20">اسم المستخدم</label>
                                                <input id="username" type="text" class="form-control"  name='username' value="{{$manadoub->username}}" autocomplete="off"/>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="password" class="form-label sm:w-20">الباسورد</label>
                                                <input id="password" type="password" class="form-control"   name='password' value="{{ $manadoub->password}}"/>
                                            </div>
                                            <div class="form-inline mt-3">
                                                
                                                <div class="form-inline mt-3">
                                                   
                                                    <label for="date" class="form-label sm:w-20" >رقم الاهاتف</label>
                                                    <input type="text" class="form-control col-span-4"   aria-label="default input inline 1" name="phone_" value="{{ $manadoub->phone_}}">
                                                </div>
                                            </div>
                                            
                                            <div class="form-inline mt-3">
                                                <label for="mo7afaza" class="form-label sm:w-20">المحافظة</label>
                                                <select name="mo7afza" id='mo7afza' class="form-control mo7afza" name="mo7fza">
                                                    <option value=""></option>
                                                    @foreach($mo7afazat as $mo7afaza)
                                                    <option value="{{$mo7afaza->code}}" @if($mo7afaza->name == $manadoub->mo7fza) selected @endif>{{$mo7afaza->name}}</option>
                                                    @endforeach
                                                </select>
                                                <script>
                                                    let mo7afazaSelect = new TomSelect(".mo7afza",{});
                                                </script>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="horizontal-form-1" class="form-label sm:w-20">المنطقة</label>
                                                <select name="manteka" id='manteka'  class="form-control   mr-1"  style=" " >

                                                </select>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="branch" class="form-label sm:w-20">الفرع</label>
                                                <select name="branch" id='branch' class="form-control branch" name="branch">
                                                    <option value=""></option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{$branch->code_}}" @if( $branch->name_== $manadoub->branch) selected @endif >{{$branch->name_}}</option>
                                                    @endforeach
                                                </select>
                                                <script>
                                                    let mo7afazaSelect = new TomSelect(".branch",{});
                                                </script>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="horizontal-form-1" class="form-label sm:w-20">ملاحظات</label>
                                                <textarea name="notes_" id='notes_'  class="form-control   mr-1"  style=" "  >

                                                </textarea>
                                            </div>


                                            <div class="sm:ml-20 sm:pl-5 mt-5 mb-10">
                                                <button class="btn btn-primary">حفظ</button>
                                            </div>
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

<script>
    var  manteka =new TomSelect("#manteka",{
    valueField: 'id',
    labelField: 'title',
    searchField: 'title',
    create: false
});
$( document ).ready(function() {
    // $('#data-error-mo7afza').hide();
                  var mo7afza_id = $('#mo7afza').val();
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
                               
                                manteka.addOption({
                                    id: value.name,
                                    title: value.name,
                                    
                                });
                                manteka.setValue('{{$manadoub->mantqa}}');
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
