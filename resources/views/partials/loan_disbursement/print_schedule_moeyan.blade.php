<div id="DivIdToPrintPop">
<style>
    table {
        border-collapse: collapse;
    }

    .border td {
        border: 1px solid rgba(188, 188, 188, 0.96);
    }

    .right {
        text-align: right;
    }

    tr td {
        font-size: 13px;
    }

    thead th{
        text-align:center;
        font-size:13px;
        background-color:#009900;
        padding: 5px;
        border: 1px solid rgba(188, 188, 188, 0.96);
    }

    body{
        font-size: 13px;
        text-align: center;
    }

    .info{
        text-align: left;
    }

</style>
    <?php
    $m = getSetting();
    $logo = getSettingKey('logo',$m);
    $company = getSettingKey('company-name',$m);
    ?>

@if($row != null)
    {{--{{dd($row)}}--}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3" style="float:left">
                <img src="{{asset($logo)}}" width="200"/>
            </div>

            <div class="col-md-8 text-center" style="font-size:15px">
                <span>No(401-411), Ground Floor, Between 27<sup>th</sup>  28<sup>th</sup> Street, Bogyoke Aung San Road,<br> Pabedan Township,
                    Yangon. Tel : 09-797002155, 09-797002156, 09-797002157. Email:moeyanmfi@moeyantrade.com
                </span>
            </div>
        </div>
        
        <?php
            $product = \App\Models\LoanProduct::find($row->loan_production_id);
        ?>

        <div>
            <center>
            <span style="font-size:16px; margin-left:-120px;" ><b>STAR MOE YAN MICROFINANCE COMPANY LIMITED</b></span><br>
            <span style="font-size:15px;" ><b>Repayment Schedule</b></span>
            </center>
        </div><br>

        <?php
            $client = optional(\App\Models\Client::find($row->client_id));
            $employee = optional(\App\Models\EmployeeStatus::where('client_id', $row->client_id)->first());
            $guarantor = \App\Models\Guarantor::find($row->guarantor_id);

            $dc = \App\Models\LoanCalculate::where('disbursement_id', $row->id)->orderBy('date_s','ASC')->get();
            $dis = \App\Models\PaidDisbursement::where('contract_id', $row->id)->first();
            //dd($dis);

         ?>

        <table style="width:100%;height:30px;border-top: 1px solid black;border-bottom: 1px solid black;margin-top:2px;font-size:11px;font-weight: normal;"> <!-- MSM add 1/12/2017-->
            <tr>
                <td>Name : <b>{{optional($client)->name}}</b></td>
                <td class="text-center">ID No : <b>{{optional($client)->client_number}}</b></td>
                <td class="right">Phone No. :<b> {{optional($client)->primary_phone_number}}</b></td>
            </tr>
        </table>
        
        <table style="width: 100%;font-size:13px; margin:10px;">
            <tr>
                <td>ပေးချေသည့် ပုံစံ</td>
                <td>: <b>{{optional($row)->repayment_term}}</b></td>

                <td>ချေးငွေသက်တမ်း</td>
                <td>: <b>{{optional($row)->loan_term_value}}လ</b></td>

                <td>ထုတ်ချေးသည့်ရက်စွဲ</td>
                <td>: <b>{{ optional($dis)->paid_disbursement_date != null ? date('d-m-Y', strtotime(optional($dis)->paid_disbursement_date)):''}}</b></td>
            </tr>

            <?php $loan_office = App\User::find(optional($row)->loan_officer_id);?>

            <tr>
                <td>အတိုးနှုန်း</td>
                <td>: <b>{{optional($row)->interest_rate}}%</b> </td>

                <td>ချေးငွေတာဝန်ခံအမှတ်</td>
                <td>: <b>{{optional($loan_office)->name}}</b> </td>

                <td>စတင်ပေးချေးရမည့်ရက်စွဲ</td>
                <td>: <b>{{ $row->first_installment_date != null ? date('d-m-Y', strtotime($row->first_installment_date)) : ''}}</b> </td>

            </tr>

            <tr>
                <!-- <td>ငွေချေးသူ လိပ်စာ</td>
                <td>: <b>{{optional($client)->address1}}</b></td> -->

                <td>ကုမ္ပဏီ အမည်</td>
                <td>: <b>{{optional($employee)->company_name}}</b></td>

                <td>Loan Cycle</td>
                <td>: <b>{{optional($row)->loan_cycle}}</b></td>
            </tr>
        </table>

        <table style="width: 100%;" class="text-center">
            <thead>
                <th colspan="2">ပေးချေရမည့် နေ့</th>
                <th>စာရင်းဖွင့် အရင်းငွေ</th>
                <th>အရင်းငွေ</th>
                <th>အတိုးနှုန်း</th>
                <th>လစဉ်သွင်းရန်အရစ်ကျငွေ</th>
                <th>လက်ကျန် အရင်းငွေ</th>
                <th>လက်မှတ်</th>
            </thead>


            <tbody class="border">

            @if($dc != null)

                @php
                    {{
                        $i = 1;
                        $total_prin = 0;
                        $total_int = 0;
                        $ex_total_int = 0;
                        $total_pay = 0;
                        $total_due = 0;
                        $opening_balance = $row->loan_amount;
                    }}
                @endphp

                @foreach($dc as $c)
                    @php
                        {{
                            $total_prin += $c->principal_s;
                            $total_int += $c->interest_s;
                            $ex_total_int += $c->exact_interest;
                            $total_pay += $c->total_s;
                            $due = $c->principal_s + $c->interest_s;
                            $total_due += $due;
                         }}
                    @endphp
                    
                    @if($c->total_p >0)
                    <tr style="background: #fffbe8;">
                        <td style="text-align:center;">{{$c->no}}</td>
                        <td style="text-align:center;">{{date('d-m-Y', strtotime($c->date_s))}}</td>
                        <td class="right">{{number_format($opening_balance,0)}}</td>
                        <td class="right">{{number_format($c->principal_s,0)}}</td>
                        <td class="right">{{number_format($c->interest_s,0)}}</td>
                        <td class="right">{{number_format($c->due,0)}}</td>
                        <td class="right">{{number_format($c->balance_s,0)}}</td>
                        <td></td>
                    </tr>
                    @else
                        <tr>
                            <td style="text-align:center;">{{$c->no}}</td>
                            <td style="text-align:center;">{{date('d-m-Y', strtotime($c->date_s))}}</td>
                            <td class="right">{{number_format($opening_balance,0)}}</td>
                            <td class="right">{{number_format($c->principal_s,0)}}</td>
                            <td class="right">{{number_format($c->interest_s,0)}}</td>
                            <td class="right">{{number_format($due,0)}}</td>
                            <td class="right">{{number_format($c->balance_s,0)}}</td>
                            <td></td>
                        </tr>
                    @endif

                    @php
                        {{
                            $i++;
                            
                            $opening_balance = $opening_balance - $c->principal_s;
                         }}
                    @endphp

                @endforeach
                <tr  style="font-weight:bold;">
                    <td colspan="2" style="text-align:center;"> Total</td>
                    <td> </td>
                    <td class="right">{{number_format($total_prin,0)}}</td>
                    <td class="right">{{number_format($total_int,0)}}</td>
                    <td class="right">{{number_format($total_due,0)}}</td>
                    <td class="right"></td>
                </tr>

            @endif


            </tbody>
        </table>

        <div class="text-center" style="margin-top:5px;">
            <span>အထက်ပါချေးငွေပြန်ဆပ်မှုအစီအစဉ်ဇယားအတိုင်း လစဉ်ချေးငွေပြန်ဆပ်ပါမည်ဟု ဝန်ခံကတိပြုပါသည်။</span class="text-center">
        </div>

        <table style="width: 100%;" class="text-center">
            <tr>
                 <td>
                    Microfinance Sign
                 </td>  
                 <td>
                    ချေးငွေထုတ်ယူသူလက်မှတ်
                 </td> 
                 <td>
                    အာမခံသူလက်မှတ်
                 </td> 
            </td>

            <tr>
                <td></td> 
                 <td>
                    အမည်     -   <?php echo optional($client)->name; ?>
                 </td> 
                 <td>
                    အမည်     -
                 </td> 
            </tr>

            <tr>
                <td></td> 
                 <td>
                    မှတ်ပုံတင်-
                 </td>   
                 <td>
                    မှတ်ပုံတင်-
                 </td> 
            </tr>

            <tr>
                <td></td> 
                 <td>
                    လက်မှတ်-
                 </td>   
                 <td>
                    လက်မှတ်-
                 </td> 
            </tr>
        </table>
        <div>
        ချေး‌‌‌‌ငွေပြန်လည်ပေးသွင်းနိုင်သည့်နေရာများ
        </div>
                <img src="{{asset('/uploads/Logos/kbz.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/cb.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp    
                <img src="{{asset('/uploads/Logos/aya.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/yoma.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/uab.png')}}" alt="" width="80">
                <img src="{{asset('/uploads/Logos/agd.png')}}" alt="" width="80"><br>
                <img src="{{asset('/uploads/Logos/kbz_pay.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/cb_pay.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/onepay.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/wave_money.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/true_money.png')}}" alt="" width="80">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <img src="{{asset('/uploads/Logos/ok.png')}}" alt="" width="80">
        </div>
        <br>
            <div class="info" style="margin-left:279px;">
                <span>CB Bank Acc No.- 0010100500041173</span><br>
                <span>UAB Bank Acc No.- 016010100011135</span><br>
                <span>--------------------------------------</span><br>
                <span>‌‌ ငွေပေး‌ချေရာတွင် လိုအပ်သည့်အချက်အလက်များ</span><br>
                <span> 1. Reference No (ခ) စာချုပ်အမှတ်</span> <span>( ဉပမာ - 12/LaThaNa(N)021666 -> LTN021666)</span><br>
                <span> 2. ချေး‌‌ငွေပေးသွင်းရမည့်သူ၏အမည်</span><br>
                <span> 3. ပေးသွင်းရမည့်ငွေပမာဏ</span>
                <br>
                <span>--------------------------------------</span>
                <P>ဆက်သွယ်ရန်ဖုန်း- , 09-777002150/51/52<br>
            </div>
    </div>


    @elseif($payment_id != null)
    <div class="col-md-12">
        <input type="text" value="{{$payment_id->receipt_no}}" class="form-control" readonly style="font-size:24px;">
    </div>
    @endif
</div>
