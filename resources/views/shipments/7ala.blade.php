@extends('layout.app')

@section('content')
<div class="content">
                <!-- BEGIN: Top Bar -->
                @include('layout.partial.topbar')
                <!-- END: Top Bar -->
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 2xl:col-span-12">
                        <div class="grid grid-cols-12 gap-6">
                            <!-- BEGIN: General Report -->
                            <div class="col-span-12 mt-8 " >
                                
                                <div class="grid grid-cols-12 gap-6 mt-5">
                                    @foreach($statuses as $status)
                                        
                                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y linky" data-code="{{$status['code_']}}">
                                        <a href="{{route('shiments',['type' =>$status['code_'] ])}}">
                                        <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                        <div class="ml-auto">
                                                            <div class="report-box__indicator bg-success tooltip cursor-pointer" > {{ round(($status['cnt'] /$total )*100)}}%  </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">{{$status['cnt']}}</div>
                                                    <div class="text-base text-slate-500 mt-1">{{$status['name_']}}</div>
                                                </div>
                                            </div>
                                        </a>
                                        </div>
                                    @endforeach
                                    
                                    
                                    
                                </div>
                            </div>
                            <!-- END: General Report -->
                            <!-- BEGIN: Sales Report -->
                            
                            <!-- END: Sales Report -->
                            <!-- BEGIN: Official Store -->
                            {{-- <div class="col-span-12 xl:col-span-8 mt-6">
                                <div class="intro-y block sm:flex items-center h-10">
                                    <h2 class="text-lg font-medium truncate mr-5">
                                        Official Store
                                    </h2>
                                    <div class="sm:ml-auto mt-3 sm:mt-0 relative text-slate-500">
                                        <i data-lucide="map-pin" class="w-4 h-4 z-10 absolute my-auto inset-y-0 ml-3 left-0"></i> 
                                        <input type="text" class="form-control sm:w-56 box pl-10" placeholder="Filter by city">
                                    </div>
                                </div>
                                <div class="intro-y box p-5 mt-12 sm:mt-5">
                                    <div>250 Official stores in 21 countries, click the marker to see location details.</div>
                                    <div class="report-maps mt-5 bg-slate-200 rounded-md" data-center="-6.2425342, 106.8626478" data-sources="/dist/json/location.json"></div>
                                </div>
                            </div> --}}
                            <!-- END: Official Store -->
                            <!-- BEGIN: Weekly Best Sellers -->
                           
                            <!-- END: Weekly Top Products -->
                        </div>
                    </div>
                    
                </div>
                
            </div>


            <script>
                $('.linky').on('click',function(){
                    var code = $(this).data('code');
                    //window.location.replace(window.location.href+'/'+code)
                })
            </script>
@endsection
