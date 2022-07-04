<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>A simple, clean, and responsive HTML invoice template</title>

    <style>
        body{
            font-family: 'XBRiyaz' , Sans-Serif;
        }
        table.tabel_data, .tabel_data td,.tabel_data th {
            border: 2px solid #00000099;
            text-align: center;
        }

        table.tabel_data {
            border-collapse: collapse;
            width: 100%;
        }
        table.data_of_number,.data_of_number th,.data_of_number td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .data_of_number{
            margin-bottom: 25px;
        }
        .data_of_number th,.data_of_number td {

            text-align: right;
            width: 46px;
        }
        .data_of_number td{
            padding-bottom: 0px !important;
        }
        .tabel_data th,.tabel_data  td {
            padding: 15px;
            font-weight: bold;
            font-size: 19px !important;
        }
        .data_of_title{
            text-align: center;
            background: #8080801f;
            padding: 2px;
            margin-bottom: 28px;
            border-radius: 8px;
            color: white;
        }
        span{
            font-size: 15px;
        }

        .data_of_number th{
            background: grey;
            color: white;

        }
        @page {
            header: page-header;
            footer: page-footer;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            font-size: 16px;
            line-height: 24px;
            font-family:'XBRiyaz' , Sans-Serif;
            color: #555;

        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }


        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: 'XBRiyaz' , Sans-Serif;        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: center;
        }
    </style>
</head>

<body>
@php
    $company = App\Models\CompanyInfo::where('branch_',Auth::user()->branch)->first() ;
@endphp
<div class="invoice-box rtl">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <h3>{{$company->name_}} <br><br><span>{{$company->Tel_}} </span> </h3>
                        </td>
                        <td class="title" style="">
                            {{-- <img style="width: 130px;" src="{{asset('assets/'.$company->image_data)}}"  alt="" class="ml-auto" style="height: 130px!important; margin-bottom: 30px"> --}}
                        </td>
                        <td>
                            <h4>
                                {{$company->branch_}}<br />
                                {{$company->address_}}
                            </h4>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>




    </table>
    <div class="data_of_title">
        <h2>{{$title}}</h2>
    </div>
    <div class="number_of_shipment">
        {{--    <div class="data_of_number">--}}
        {{--    <span>عدد الشحنات</span>--}}
        {{--    <span>{{count($all)}}</span>--}}
        {{--    </div>--}}


        <table class="tabel_data" align="center">
            <tr>

                <th >عدد الشحنات</th>
                <th >اجمالي مبلغ الشحنة</th>

                <th >التاريخ</th>

            </tr>


                <tr >
                    <td>{{count($all)}}</td>
                    <td>{{number_format($sum['totalCost'], 0)}}</td>

                    <td>{{Carbon\Carbon::now()->format('Y-m-d  g:i:s A')}}</td>
                </tr>

        </table>
    </div>
    <br><br><br><br>

    <table class="tabel_data" align="center">
        <tr>
            <th>#</th>
            <th >الكود </th>
            <th> مبلغ الشحنة</th>
            <th>الاسم التجارى</th>
            <th >التاريخ </th>
            <th >المحافظة</th>
            <th >العنوان</th>
            <th>هاتف المستلم</th>
            <th>اسم المندوب</th>
            <th>الملاحظات</th>
        </tr>
        @php $i=1; @endphp
        @foreach($all as $shipment)

            <tr >
                <td><?php echo $i; $i++?></td>
                <td  >{{$shipment->code_}}</td>
                <td  >{{number_format($shipment->shipment_coast_, 0)}}</td>
                <td >{{$shipment->commercial_name_}}</td>
                <td  >{{$shipment->date_}}</td>
                <td  >{{$shipment->mo7afza_}}</td>
                <td  >{{$shipment->el3nwan}}</td>
                <td  >{{$shipment->reciver_phone_}}</td>
                <td  >{{$shipment->mandoub_taslim}}</td>
                <td  >{{$shipment->notes_}}</td>


            </tr>
        @endforeach
    </table>

{{--ZIAD ABO ALKAMAR--}}
{{--Omar Yasser--}}

</div>
</body>
</html>

