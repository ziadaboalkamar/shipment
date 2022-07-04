@extends('layout.app')

@section('content')
<div class="content">
                <!-- BEGIN: Top Bar -->
                @include('layout.partial.topbar')
                <!-- END: Top Bar -->
                
                <div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
                    <!-- BEGIN: Post Content -->
                    <div class="intro-y col-span-12 lg:col-span-8">
                        
                        <div class="post intro-y overflow-hidden box mt-5">
                            
                            <div class="post__content tab-content">
                                <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby=	"content-tab">
                                <div class="form-inline mt-3">
                                    <label for="username" class="form-label sm:w-20">الاسم</label>
                                    <input id="username" type="text" class="form-control"  name='username' autocomplete="off"/>
                                </div>
                                <div class="form-inline mt-3">
                                    <label for="username" class="form-label sm:w-20">الهاتف</label>
                                    <input id="username" type="text" class="form-control"  name='username' autocomplete="off"/>
                                </div>
                                <div class="form-inline mt-3">
                                    <label for="username" class="form-label sm:w-20">ملاحظات</label>
                                   
                                    <textarea name="" id="" class="form-control"></textarea>
                                </div>
                                   
                                   
                                   
                                    <div class="sm:ml-20 sm:pl-5 mt-5">
                                        <button class="btn btn-primary">حفظ</button>
                                    </div>
							    </div>
                            </div>
							                         
							</div>
                        </div>
                    </div>
                    <!-- END: Post Content -->
                    <!-- BEGIN: Post Info -->
                    
                    <!-- END: Post Info -->
                </div>
</div>
@endsection
