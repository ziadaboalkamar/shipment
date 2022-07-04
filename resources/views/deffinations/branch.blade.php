@extends('layout.app')

@section('content')
<div class="content">
                <!-- BEGIN: Top Bar -->
                @include('layout.partial.topbar')
                <!-- END: Top Bar -->
                <div class="intro-y   mt-8" >
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
                    <h2 class="text-lg font-medium mr-auto" >
                        أضافة فرع
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-6">
                        <!-- BEGIN: Form Layout -->
                    <form method="POST" action="{{route('storeBranch')}}">
                        <div class="intro-y box p-5">
                            <div> 
                                  <label for="regular-form-1" class="form-label">
                                   اسم الفرع
                                  </label> 
                                  <input id="regular-form-1" type="text" class="form-control" name="name_"> 
                               </div>
                               <div class="mt-3"> 
                                    <label for="regular-form-2" class="form-label">اسم الفرع انجليزى</label> 
                                    <input id="regular-form-2" type="text" class="form-control" name="name_E" >
                                </div>
                               <div class="mt-3"> 
                                    <label for="regular-form-2" class="form-label">عنوان  الفرع</label> 
                                    <input id="regular-form-2" type="text" class="form-control" name="address_">
                                </div>
                               <div class="mt-3"> 
                                    <label for="regular-form-2" class="form-label">التليفون</label> 
                                    <input id="regular-form-2" type="text" class="form-control" name="Tel_">
                                </div>
                               <div class="mt-3"> 
                                    <label for="regular-form-5" class="form-label">
                                   ملاحضات</label> 
                                    <textarea id="regular-form-5" type="text" class="form-control"  name="notes_"> 
                                    </textarea>
                                </div>
                               @csrf
                            <div class="text-right mt-5">
                                
                                <button type="submit" class="btn btn-primary w-24">حفظ</button>
                            </div>
                        </div>
                    </form>
                        <!-- END: Form Layout -->
                    </div>
                </div>
            </div>
@endsection
