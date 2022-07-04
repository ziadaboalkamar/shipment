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


                <ul class="nav nav-tabs" role="tablist">
                    <li id="example-1-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#example-tab-1" type="button" role="tab" aria-controls="example-tab-1" aria-selected="true"> اضافة </button> </li>
                    <li id="example-2-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#example-tab-2" type="button" role="tab" aria-controls="example-tab-2" aria-selected="false"> بحث </button> </li>
                </ul>
                <div class="tab-content border-l border-r border-b">
                    <div id="example-tab-1" class="tab-pane leading-relaxed p-5 active" role="tabpanel" aria-labelledby="example-1-tab">
                        <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">

                            <!-- BEGIN: Post Content -->
                            <div class="intro-y col-span-12 lg:col-span-8">

                                <div class="post intro-y overflow-hidden box mt-5">
                                <form action="{{route('storeClient')}}" method="post">
                                    <div class="post__content tab-content">
                                        <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby=  "content-tab">
                                            <div class="form-inline">
                                                <label for="date" class="form-label sm:w-20">اسم العميل</label>

                                                    <input type="text" class="form-control col-span-4" name="client_name"   aria-label="default input inline 1" style="width: 350px;">

                                            </div>

                                            <div class="form-inline mt-3 mb-2">
                                                <label for="date" class="form-label sm:w-20">الاسم التجارى</label>

                                                    <input type="hidden" name='Commercial_name' value="" id=Hcomname>
                                                    <select class="Commercial_name "   id='Commercial_name' multiple name="" style="width: 350px;">
                                                        {{-- <option value="">...</option> --}}
                                                        @foreach($Commercial_names as $name)
                                                            {{-- <option value="{{$name->name_}}">{{$name->name_}}</option> --}}
                                                        @endforeach
                                                    </select>
                                                    <script>
                                                        let CommercialNameSelect = new TomSelect("#Commercial_name",{
                                                            maxItems: null,
                                                            create: true,
                                                            onDelete: function(values) {
                                                                $('#Hcomname').val($('#Hcomname').val().replace(values+',', ''));
                                                                // return confirm(values.length > 1 ? "Are you sure you want to remove these " + values.length + " items?" : "Are you sure you want to remove " + values[0] + "?");
                                                            },
                                                            onItemAdd: function(values) {
                                                                $('#Hcomname').val($('#Hcomname').val()+values+',');
                                                                // return confirm(values.length > 1 ? "Are you sure you want to remove these " + values.length + " items?" : "Are you sure you want to remove " + values[0] + "?");
                                                            },
                                                            
                                                                   
                                                        });
                                                    </script>

                                            </div>
                                         <hr>
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
                                                <input id="username" type="text" class="form-control"  name='username' autocomplete="off"/>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="password" class="form-label sm:w-20">الباسورد</label>
                                                <input id="password" type="password" class="form-control"   name='password'/>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="date" class="form-label sm:w-20">رقم الهوية</label>
                                                <div class="grid grid-cols-12 gap-2">
                                                    <input type="text" class="form-control col-span-4"   aria-label="default input inline 1" name="ID_">
                                                    <label for="date" class="form-label col-span-4" style="text-align: left; margin-top:8px;">رقم الاهاتف</label>
                                                    <input type="text" class="form-control col-span-4"   aria-label="default input inline 1" name="phone_">
                                                </div>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="phone" class="form-label sm:w-20">عنوان العميل</label>
                                                <input id="phone" type="text" class="form-control"  name="address_"/>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="mo7afaza" class="form-label sm:w-20">المحافظة</label>
                                                <select name="mo7afza" id='mo7afza' class="form-control mo7afza" name="mo7fza">
                                                    <option value=""></option>
                                                    @foreach($mo7afazat as $mo7afaza)
                                                    <option value="{{$mo7afaza->code}}"  >{{$mo7afaza->name}}</option>
                                                    @endforeach
                                                </select>
                                                <script>
                                                    let mo7afazaSelect = new TomSelect(".mo7afza",{});
                                                </script>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="horizontal-form-1" class="form-label sm:w-20">المنطقة</label>
                                                <select name="manteka" id='manteka'  class="form-control   mr-1"  style=" "  name="mantqa">

                                                </select>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="branch" class="form-label sm:w-20">الفرع</label>
                                                <select name="branch" id='branch' class="form-control branch" name="branch">
                                                    <option value=""></option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{$branch->code_}}"  >{{$branch->name_}}</option>
                                                    @endforeach
                                                </select>
                                                <script>
                                                    let mo7afazaSelect = new TomSelect(".branch",{});
                                                </script>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="date" class="form-label sm:w-20">اسعار خاصة</label>
                                                <div class="grid grid-cols-12 gap-2">
                                                    <select class="form-control col-span-4" name="Special_prices">
                                                        <option value="لا">لا</option>
                                                        <option value="نعم">نعم</option>
                                                    </select>
                                                    {{-- <label for="date" class="form-label col-span-4" style="text-align: left; margin-top:8px;">المفتاح</label>
                                                    <input type="text" class="form-control col-span-4"   aria-label="default input inline 1">  --}}
                                                </div>
                                            </div>
                                            <div class="form-inline mt-3">
                                                <label for="date" class="form-label sm:w-20">اضافه شحنات</label>
                                                <div class="grid grid-cols-12 gap-2">
                                                    <input type="checkbox" name="addshipment" />
                                                </div>
                                            </div>






                                            <div class="sm:ml-20 sm:pl-5 mt-5 mb-10">
                                                <button class="btn btn-primary">حفظ</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                </div>
                            </div>
                            <!-- END: Post Content -->
                            <!-- BEGIN: Post Info -->

                            <!-- END: Post Info -->
                        </div>
                    </div>
                    <div id="example-tab-2" class="tab-pane leading-relaxed p-5" role="tabpanel" aria-labelledby="example-2-tab">
                        <div class="overflow-x-auto mt-5">
                            <table class="table table-striped" id="dataTable">
                                <thead class="table-light">
                                    <tr>

                                        <th class="whitespace-nowrap">#</th>
                                        <th class="whitespace-nowrap">الاسم</th>
                                        <th class="whitespace-nowrap">اسم المستخدم</th>
                                       

                                        <th class="whitespace-nowrap">الفرع</th>

                                                <th class="whitespace-nowrap"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($users as $user)

                                    <th class="whitespace-nowrap">{{$i}}</th>
                                    <th class="whitespace-nowrap">{{$user->name_}}</th>
                                    <th class="whitespace-nowrap">{{$user->username}}</th>
                                    

                                    <th class="whitespace-nowrap">{{$user->branch}}</th>


                                    <th class="whitespace-nowrap">
                                        <a href="{{route('editclient',['code' =>$user->code_])}}"><i data-lucide="edit" class="check_count"
                                            ></i></a>
                                    </th>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
</div>

<script>
    var  manteka =new TomSelect("#manteka",{
    valueField: 'title',
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
                                    id: value.code,
                                    title: value.name,

                                });
                                manteka.setValue(temp);
                            });
                          }
                      });
    });
</script>
@endsection
