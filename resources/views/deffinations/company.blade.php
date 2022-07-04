@extends('layout.app')

@section('content')
<div class="content">
                <!-- BEGIN: Top Bar -->

                <!-- END: Top Bar -->
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
                <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
                    <!-- BEGIN: Post Content -->
                    <div class="intro-y col-span-12 lg:col-span-8">

                        <div class="post intro-y overflow-hidden box mt-5">

                            <div class="post__content tab-content">
                                <form action="{{route('storeCompany')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby="content-tab">
                                    <div class="mt-3">

                                        <label for="regular-form-1" class="form-label">الشعار</label>
                                        @if ($company->image_data)
                                            <img src="{{asset('assets/'.$company->image_data)}}" height="80px" alt="" class="ml-auto" style="height: 80px!important; margin-bottom: 30px">
                                        @endif
                                        <input type="file" name="logo" class="form-control credit-card-mask" placeholder="الشعار"  />
                                        @error('logo')<span class="text-danger">{{ $message }}</span>@enderror </div>
							   <div class="mt-3">
							      <label for="regular-form-1" class="form-label">
							      أسم الشركة عربى
							      </label>
							      <input id="regular-form-1" @if(isset($company->name_)) value="{{$company->name_}}" @endif name="name_" type="text" class="form-control" >
							   </div>
							   <div class="mt-3">
								   	<label for="regular-form-2" class="form-label">اسم الشركه انجلزي</label>
								   	<input id="regular-form-2"@if(isset($company->name_E)) value="{{$company->name_E}}" @endif  name="name_E" type="text" class="form-control" >
							   	</div>
							   <div class="mt-3">
								   	<label for="regular-form-2" class="form-label">عنوان الشركة</label>
								   	<input id="regular-form-2"@if(isset($company->address_)) value="{{$company->address_}}" @endif  name="address_" type="text" class="form-control" >
							   	</div>
							   <div class="mt-3">
								   	<label for="regular-form-2" class="form-label">التليفون</label>
								   	<input id="regular-form-2"@if(isset($company->Tel_)) value="{{$company->Tel_}}" @endif  name="Tel_" type="text" class="form-control" >
							   	</div>
							   <div class="mt-3">
								   	<label for="regular-form-5" class="form-label">
								   ملاحضات</label>
								   	<textarea id="regular-form-5"  type="text" name="notes_" class="form-control"  >@if(isset($company->notes_)){{$company->notes_}} @endif
								   	</textarea>
							   	</div>
							    <button class="btn btn-primary mt-5">Save</button>
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
@endsection
