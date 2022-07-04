<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">

        <img alt="Midone - HTML Admin Template" class="w-10" src="{{asset('assets/'.\App\Models\CompanyInfo::where('branch_',Auth::user()->branch)->first()->image_data)}}">
        <span class="hidden xl:block text-white text-lg ml-3 mr-3"> {{\App\Models\CompanyInfo::where('branch_',Auth::user()->branch)->first()->name_}} </span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="side-menu-light-inbox.html" class="side-menu">
                <div class="side-menu__icon"> <i data-lucide="inbox"></i> </div>
                <div class="side-menu__title"> لوحة المراقبة </div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="side-menu @if(Request::is('shiment/*') ||Request::is('shiments') ||Request::is('shipment/*')) side-menu--active side-menu--open @endif" style="display: flex;">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    الشحنات
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="@if(Request::is('shiment/*')||Request::is('shiments') ||Request::is('shipment/*'))side-menu__sub-open @endif">
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('homePage-shipment'))

                <li>
                    <a href="{{route('home-page')}}" class="side-menu side-menu--active">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> الشحنات </div>
                    </a>
                </li>
            @endif
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('new-shipment'))
                <li>
                    <a href="{{route('shiments.create')}}" class="side-menu side-menu--active">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اﺿﺎﻓﺔ ﺷﺣﻧﺔ </div>
                    </a>
                </li>
                    @endif
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('update-shipment'))
                <li>
                    <a href="{{route('shiments.editview')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> ﺗﻌدﯾل ﺷﺣﻧﺔ </div>
                    </a>
                </li>
                    @endif
                {{-- @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('status-shipmnt'))
                <li>
                    <a href="side-menu-light-dashboard-overview-3.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> ﺣﺎﻻت اﻟﺷﺣﻧﺎت </div>
                    </a>
                </li>
                    @endif --}}
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('print-shipment'))
                <li>
                    <a href="{{route('shiments.print')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> طﺑﺎﻋﺔ اﻟﺷﺣﻧﺎت </div>
                    </a>
                </li>
                    @endif
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('search-shipment'))

                    <li>
                    <a href="{{route('sipments.search')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اﺳﺗﻌﻼم ﻋن ﺷﺣﻧﺔ </div>
                    </a>
                </li>
                    @endif
                 {{-- @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('archive-shipment'))

                    <li>
                    <a href="side-menu-light-dashboard-overview-4.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اﻟﺗﺣوﯾل ﻟﻸرﺷﯾف </div>
                    </a>
                </li>
                    @endif --}}
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('t7weelQr-shipment'))

                    <li>
                    <a href="{{route('shipment.t7wel_qr')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> تحويل حالة الشحنه باستخدام QR </div>
                    </a>
                </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('tasleemToMandoubtaslim-shipment'))

                    <li>
                    <a href="{{route('shipment.taslim_qr')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> ﺗﺳﻠﯾم ﺷﺣﻧﺔ اﻟﻰ ﻣﻧدوب ﺗﺳﻠﯾم ﺑﺄﺳﺗﺧدام Qr </div>
                    </a>
                </li>
                    @endif
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu  @if(Request::is('frou3/*')) side-menu--active side-menu--open @endif">
                <div class="side-menu__icon"> <i data-lucide="inbox"></i> </div>
                <div class="side-menu__title">
                    اﻟﻔروع
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="@if(Request::is('frou3/*'))side-menu__sub-open @endif">

                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('export-frou3'))

                <li>
                    <a href="{{route('frou3.export')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اﻟﺷﺣﻧﺎت اﻟﺻﺎدرة اﻟﻰ اﻟﻔرع </div>
                    </a>
                </li>
                @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('import-frou3'))

                    <li>
                    <a href="{{route('frou3.import')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> ﻟﺷﺣﻧﺎت اﻟواردة ﻣن اﻟﻔرع</div>
                    </a>
                </li>
                    @endif


                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            ﺗﺣوﯾل اﻟﺷﺣﻧﺎت اﻟﻰ ﻓرع
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('t7welSho7natManual-frou3'))

                        <li>
                            <a href="{{route('frou3_t7wel_sho7nat_manual')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title"> ﺗﺣوﯾل اﻟﺷﺣﻧﺎت اﻟﻰ ﻓرع ﯾدوﯾﺎ</div>
                            </a>
                        </li>
                        @endif
                       @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('t7welsho7natQr-frou3'))

                            <li>
                            <a href="{{route('frou3_t7wel_sho7nat_qr')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">ﺗﺣوﯾل اﻟﺷﺣﻧﺎت اﻟﻰ ﻓرع ﺑﺄﺳﺗﺧدام QR</div>
                            </a>
                        </li>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('acceptT7welsho7natQr-frou3'))

                            <li>
                            <a href="{{route('accept_frou3_t7wel')}} " class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">اﻟﻣواﻓﻘﺔ ﻋﻠﻰ اﻟﺷﺣﻧﺎت اﻟواردة ﻣن اﻟﻔرع</div>
                            </a>
                        </li>
                            @endif
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            ﺗﺣوﯾل اﻟرواﺟﻊ اﻟﻰ ﻓرع
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('t7welRag3Manual-frou3'))

                        <li>
                            <a href="{{route('frou3_t7wel_rag3_manual')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">ﺗﺣوﯾل اﻟرواﺟﻊ اﻟﻰ ﻓرع ﯾدوﯾﺎ
                                </div>
                            </a>
                        </li>
                        @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('t7welRag3Qr-frou3'))

                            <li>
                            <a href="{{route('frou3_t7wel_rag3_qr')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">ﺗﺣوﯾل اﻟرواﺟﻊ اﻟﻰ ﻓرع ﺑﺄﺳﺗﺧدام QR</div>
                            </a>
                        </li>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('acceptT7welRag3Qr-frou3'))

                            <li>
                            <a href="{{route('accept_frou3_rag3')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">اﻟﻣواﻓﻘﺔ ﻋﻠﻰ اﻟرواﺟﻊ اﻟواردة ﻣن اﻟﻔرع</div>
                            </a>
                        </li>
                            @endif

                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            حسابات الفروع
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('notMosadad-frou3'))

                        <li>
                            <a href="{{route('accounting.notmosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">اﻟﺷﺣﻧﺎت اﻟﻐﯾر ﻣﺳددة ﻟﻠﻔرع
                                </div>
                            </a>
                        </li>
                        @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('mosadad-frou3'))
                        <li>
                            <a href="{{route('accounting.mosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">اﻟﺷﺣﻧﺎت اﻟﻣﺳددة ﻟﻠﻔرع</div>
                            </a>
                        </li>
                            @endif


                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu @if(Request::is('accounting/*')) side-menu--active side-menu--open @endif">
                <div class="side-menu__icon"> <i data-lucide="inbox"></i> </div>
                <div class="side-menu__title">
                    اﻟﺣﺳﺎﺑﺎت
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="@if(Request::is('accounting/*'))side-menu__sub-open @endif">

                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            حسابات الشركة
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="side-menu-light-regular-table.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title"> سند قبض</div>
                            </a>
                        </li>
                        <li>
                            <a href="side-menu-light-tabulator.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">سند صرف</div>
                            </a>
                        </li>
                        <li>
                            <a href="side-menu-light-tabulator.html" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">كشف الخزينة</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                           حسابات العملاء
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('notMosadad3amel-accounting'))
                        <li>
                            <a href="{{route('accounting.3amil.notmosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">غير مسدد
                                </div>
                            </a>
                        </li>
                        @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('mosadad3amel-accounting'))

                        <li>
                            <a href="{{route('accounting.3amil.mosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">مسدد</div>
                            </a>
                        </li>
                            @endif
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            ﺣﺳﺎﺑﺎت ﻣﻧدوﺑﯾن اﻟﺗﺳﻠﯾم
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('mosadadMandoubTaslem-accounting'))

                        <li>
                            <a href="{{route('accounting.mandoubtaslim.notmosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">ﻏﯾر ﻣﺳدد ﻣﻧدوب ﺗﺳﻠﯾم
                                </div>
                            </a>
                        </li>
                        @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('notMosadadMandoubTaslem-accounting'))

                            <li>
                            <a href="{{route('accounting.mandoubtaslim.mosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">ﻣﺳدد ﻣﻧدوب ﺗﺳﻠﯾم</div>
                            </a>
                        </li>
                            @endif
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            ﺣﺳﺎﺑﺎت ﻣﻧدوﺑﯾن اﻻﺳﺗﻼم
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('notMosadadMandoubEstlam-accounting'))

                        <li>
                            <a href="{{route('accounting.mandoubestlam.notmosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">ﻏﯾر ﻣﺳدد ﻣﻧدوب اﺳﺗﻼم
                                </div>
                            </a>
                        </li>
                        @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('mosadadMandoubEstlam-accounting'))
                        <li>
                            <a href="{{route('accounting.mandoubestlam.mosadad')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">ﻣﺳدد ﻣﻧدوب اﺳﺗﻼم</div>
                            </a>
                        </li>
                            @endif
                    </ul>
                </li>





            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu @if(Request::is('definations/*')) side-menu--active side-menu--open @endif ">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    التعريفات
                    <div class="side-menu__sub-icon transform "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="@if(Request::is('definations/*'))side-menu__sub-open @endif">
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('companyDefinations-definations'))
                <li>
                    <a href="{{route('company')}}" class="side-menu side-menu--active">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> تعريف الشركة </div>
                    </a>
                </li>
                @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('addManatek-definations'))
                <li>
                    <a href="{{route('addCity')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اضافه المحافظات و الفروع </div>
                    </a>
                </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('addBranches-definations'))
                <li>
                    <a href="{{route('addBranch')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اضافة الفروع </div>
                    </a>
                </li>
                    @endif
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu @if(Request::is('tas3ir/*')) side-menu--active side-menu--open @endif ">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    اﻟﺗﺳﻌﯾرات
                    <div class="side-menu__sub-icon transform "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="@if(Request::is('tas3ir/*'))side-menu__sub-open @endif">
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('tas3irMandoub-definations'))
                <li>
                    <a href="{{route('tas3ir.mandouben')}}" class="side-menu side-menu--active">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> ﺗﺳﻌﯾر اﻟﻣﻧدوﺑﯾن </div>
                    </a>
                </li>
                @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('tas3ir3amel5as-definations'))
                <li>
                    <a href="{{route('tas3ir.3amil_5as')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> ﺗﺳﻌﯾر اﻟﻌﻣﯾل اﻟﺧﺎص </div>
                    </a>
                </li>
                    @endif
            </ul>
        </li>
        <li>
            <a href="javascript:;.html" class="side-menu @if(Request::is('users/*')) side-menu--active side-menu--open @endif ">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title">
                    ﺗﻌرﯾف اﻟﻣﺳﺗﺧدﻣﯾن
                    <div class="side-menu__sub-icon transform "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="@if(Request::is('users/*'))side-menu__sub-open @endif">
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('add3amel-userDefinations'))
                <li>
                    <a href="{{route('addClient')}}" class="side-menu side-menu--active">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اﺿﺎﻓﺔ اﻟﻌﻣﻼء                                    </div>
                    </a>
                </li>
                @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('addmandoub-userDefinations'))
                <li>
                    <a href="{{route('addMandoub')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اﺿﺎﻓﺔ اﻟﻣﻧدوﺑﯾن                                    </div>
                    </a>
                </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('adduser-userDefinations'))
                <li>
                    <a href="{{route('addUser')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اﺿﺎﻓﺔ اﻟﻣﺳﺗﺧدﻣﯾن </div>
                    </a>
                </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('registrationRequest-userDefinations'))
                <li>
                    <a href="{{route('registrationRequest')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">طﻠﺑﺎت اﻟﺗﺳﺟﯾل </div>
                    </a>
                </li>
                    @endif
                    @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('commertialName-userDefinations'))
                <li>
                    <a href="{{route('commercialNames')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> ﺗﻌدﯾل اﻻﺳﻣﺎء اﻟﺗﺟﺎرﯾة </div>
                    </a>
                </li>
                    @endif
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu @if(Request::is('settings/*') ||Request::is('settings') ) side-menu--active side-menu--open @endif">
                <div class="side-menu__icon"> <i data-lucide="inbox"></i> </div>
                <div class="side-menu__title">
                    الاعدادات
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="@if(Request::is('settings/*') ||Request::is('settings'))side-menu__sub-open @endif">
                <li>
                    <a href="side-menu-light-tab.html" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            اﺿﺎﻓﺔ اﻻﻋﻼﻧﺎت
                             </div>
                    </a>
                </li>
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('permitions-setting'))
                <li>
                    <a href="{{route('permissions')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> صلاحية المستخدمين</div>
                    </a>
                </li>
                @endif


                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title">
                            اﻋدادات ﻋﺎﻣﺔ
                            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="{{route('Khazna.create')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title"> اضافة خزينة</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('Khazna.adduser')}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
                                <div class="side-menu__title">
                                    رﺑط ﻣﺳﺗﺧدم ﻣﻊ ﺧزﯾﻧﺔ</div>
                            </a>
                        </li>

                    </ul>
                </li>
                @if(\Illuminate\Support\Facades\Auth::user()->isAbleTo('setting-setting'))
                <li>
                    <a href="{{route('settings')}}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> اعدادات الموقع</div>
                    </a>
                </li>

                @endif
            </ul>
        </li>
        <!-- -->
    </ul>
</nav>
