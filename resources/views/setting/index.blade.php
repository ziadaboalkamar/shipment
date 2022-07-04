@extends('layout.app')

@section('content')

<div class="content">
    <!-- BEGIN: Top Bar -->
    @include('layout.partial.topbar')
    <!-- END: Top Bar -->
    

        <div class="col-md-8 col-md-offset-2">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
               
            {{-- <form method="post" action="{{ route('settings.store') }}" class="form-horizontal" role="form">
                {!! csrf_field() !!}

                @if(count(config('setting_fields', [])) )

                    @foreach(config('setting_fields') as $section => $fields)
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <i class="{{ Arr::get($fields, 'icon', 'glyphicon glyphicon-flash') }}"></i>
                                {{ $fields['title'] }}
                            </div>

                            <div class="panel-body">
                                <p class="text-muted">{{ $fields['desc'] }}</p>
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-7  col-md-offset-2">
                                        @foreach($fields['elements'] as $field)
                                       
                                            @includeIf('setting.fields.' .'type' )
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end panel for {{ $fields['title'] }} -->
                    @endforeach

                @endif

                <div class="row m-b-md">
                    <div class="col-md-12">
                        <button class="btn-primary btn">
                            Save Settings
                        </button>
                    </div>
                </div>
            </form> --}}
            <div class="intro-y col-span-12 lg:col-span-8">
                        
                        <div class="post intro-y overflow-hidden box mt-5">
                            
                            <div class="post__content tab-content">
                                <div id="content" class="tab-pane p-5 active" role="tabpanel" aria-labelledby=	"content-tab">
                                    <form method="post" action="{{ route('settings.store') }}" role="form">
                               {!! csrf_field() !!}
                                @foreach($settings as $setting)
                                    @if($setting->name=='status_css_prop' ||  $setting->type=='checkbox') @continue @endif
                                        <div class=" mt-3">
                                            <label for="regular-form-1" class="form-label" style="width: 150px;">{{$setting->read_name}}</label>
                                            <input type="{{$setting->type}}" name="{{$setting->name}}" value="{{$setting->val}}" class="form-control form-select-sm filterByEnter"  aria-label="default input inline 1" style="width: 150px; height:45px;" > 
                                        </div>
                                @endforeach
                               <?php $setting = $settings['status_css_prop']?>
                                <div class=" mt-3">
                                    <label for="regular-form-1" class="form-label" style="width: 150px;">{{$setting->read_name}}</label>
                                    <select name="{{$setting->name}}" id="" class="form-control form-select-sm filterByEnter"  aria-label="default input inline 1" style="width: 150px; height:45px;">
                                        <option value="background-color" @if($settings['status_css_prop']->val=='background-color') selected @endif>background-color </option>
                                        <option value="color" @if($settings['status_css_prop']->val=='color') selected @endif>font color </option>
                                    </select>
                                </div>
                                @foreach($settings as $setting)
                                @if($setting->name=='status_css_prop' ||  $setting->type !='checkbox') @continue @endif
                                <div class=" mt-3">
                                    <label for="regular-form-1" class="form-label" style="width: 150px;">{{$setting->read_name}}</label>
                                    <input type="{{$setting->type}}" name="{{$setting->name}}"  class="form-control form-select-sm filterByEnter"  aria-label="default input inline 1" style="width: 150px; height:45px;" @if($setting->val == 1) checked @endif> 
                                </div>
                                @endforeach
                                <button class="btn btn-primary mt-5">Save</button>  
                            </form>
                                </div>
							   
							   
							</div>
                       
							</div>
                        </div>
                    </div>

           
        
    </div>


     
@endsection
