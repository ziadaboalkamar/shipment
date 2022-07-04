<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>A simple, clean, and responsive HTML invoice template</title>
  <style>
        @page { sheet-size: 80mm 100mm; }
        h1.bigsection {
            page-break-before: always;
            page: bigger;
        }
        body{
            font-family: 'XBRiyaz' , Sans-Serif;
        }
        table.fatoora,.fatoora th,.fatoora td {
            border: 1px solid #0000003d;
            border-collapse: collapse;
        }
         .fatoora th{
            width: 30%;
            font-size: 12px;
            font-weight: bold;

         }
        .fatoora th,.fatoora td {
            padding: 8px;
            text-align: right;
           font-weight: bold;
        }
        .fatoora td {
            text-align: right !important;
            font-size: 12px;
 font-weight: bold;
        }
        .invoice-box {
            font-size: 7px;
            line-height: 24px;
            font-family: 'XBRiyaz' , Sans-Serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {

            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }


        .invoice-box table tr.top table td.title {
            font-size: 7px;

            color: #333;
        }


        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
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
            font-family: 'XBRiyaz' , Sans-Serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

        .invoice-box.rtl .totalammount{
            text-align: center !important;
        }
        .invoice-box.rtl .totalammount img{
            width: 50px;
            height: 50px;
        }
        .totalammount h5,.totalammount h6 , .totalammount svg{

            margin: 0px;
        }

    </style>
    </head>

    <body>
        @php
            $company = App\Models\CompanyInfo::where('branch_',Auth::user()->branch)->first() ;
        @endphp

          @for ($i = 0; $i < count($all); $i++)
        <div class="invoice-box rtl">
            <table cellpadding="0" cellspacing="0">
                <tr class="">
                    <td colspan="2">
                        <table>
                            <tr>
                              <td class="title">
                                <span style="font-size:13px;padding-top: 10px; font-weight: bold;">{{$company->name_}}</span>

                            </td>
                                <td class="title">
           {{--  <img src="{{asset('assets/'.$company->image_data)}}"  alt=""  style="width:30px; height: 30px!important; margin-bottom: 5px ;margin-right:1%; margin-left:-5px;">--}}

                                    {{-- <img src="https://www.sparksuite.com/images/logo.png" style="width: 100%; max-width: 300px" /> --}}
                                </td>


                            </tr>
                        </table>
                    </td>
                </tr>




            </table>

<table class="fatoora" style="width:90% ;font-size:7px; margin:auto;">

    <tr>
      <th style="width: 60px;">هاتف الزبون:</th>
      <td style="text-align: center;">{{$all[$i]->reciver_phone_}}</td>
    </tr>
    <tr>
      <th>المحافظه:</th>
      <td style="text-align: center;">{{$all[$i]->mo7afza_}}</td>
    </tr>
    <tr>
      <th>العنوان:</th>
      <td style="text-align: center;">{{$all[$i]->el3nwan}}</td>
    </tr>
    <tr>
      <th>اسم المتجر:</th>
      <td style="text-align: center;">{{$all[$i]->commercial_name_}}</td>
    </tr>
    <tr>
      <th>رقم الشحنه:</th>
      <td style="text-align: center;">{{$all[$i]->code_}}</td>
    </tr>


  </table>

  <div class="totalammount" style="font-size: 8px;">
    <h5  style="font-size: 14px">مبلغ الشحنه : {{number_format($all[$i]->shipment_coast_, 2)}}</h5>
<span id='mark'></span>
{!! $qrcode[$i]!!}

    <h6>{{Carbon\Carbon::now()->format('Y-m-d  g:i:s A')}}</h6>
     <p style="font-size: 7px">ust.center</p>
  </div>

        </div>

      <pagebreak></pagebreak>




@endfor

    </body>
    <script>
        var str = `<div></div>
<!-- some comment -->
<p></p>
<!-- some comment -->`
str = str.replace(/<\!--.*?-->/g, "");
// console.log(str);
        // notACommentHere()
        // document.getElementById("mark").nextSibling.remove();
        // document.getElementById("mark").nextSibling.remove();
        // document.getElementById("mark").nextSibling.remove();
     </script>
</html>
