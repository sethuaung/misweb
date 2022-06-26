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

    tr.border-red td {
    border:1pt solid red;
    }

</style>
    <?php

use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

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
        <div>
            <center>
            <span style="font-size:16px"><b>STAR MOE YAN MICROFINANCE COMPANY LIMITED</b></span><br>
            <span style="font-size:15px"><b>Loan Customer Ledger</b></span>
            </center>
        </div><br>

        <?php
            $client = optional(\App\Models\Client::find($row->client_id));
            $employee = optional(\App\Models\EmployeeStatus::where('client_id', $row->client_id)->first());
            $histories = PaymentHistory::where('loan_id', $row->id)->get()->groupBy('payment_id');
            $pending_repayments = \App\Models\LoanCalculate::where('disbursement_id', $row->id)->where('payment_status','pending')->get();
            //dd($pending_repayments);
            $loan = \App\Models\Loan::find($row->id);
            $guarantor = \App\Models\Guarantor::find($row->guarantor_id);
            $total_amount= \App\Models\LoanCalculate::where('disbursement_id', $row->id)->sum('total_s');
            $dc = \App\Models\LoanCalculate::where('disbursement_id', $row->id)->orderBy('date_s','ASC')->get();
            $dis = \App\Models\PaidDisbursement::where('contract_id', $row->id)->first();
            $loan_payments = \App\Models\LoanPayment::where('disbursement_id', $row->id)->orderBy('payment_date','ASC')->get();
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
                <td style="padding-bottom:5px;"><strong>Loan amount - {{optional($loan)->loan_amount}}</strong></td>
                <td style="padding-bottom:5px;"><strong>Total amount - {{$total_amount}}</strong></td>
            </tr>
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
                <th colspan="2">Payment Date</th>
                <th>Cash In</th>
                <th>Payment Number</th>
                {{-- <th>Loan Amount</th> --}}
                <th>Principal</th>
                <th>Interest</th>
                {{-- <th>Service</th> --}}
                <th>Penalty</th>
                {{-- <th>Owed Balance</th> --}}
                <th>Total</th>
            </thead>


            <tbody class="border">
            <?php $payment_array = array(); ?>

            @if($histories->count() > 0)

                <?php
                    {{
                        $i = 1;
                        $total_prin = 0;
                        $total_int = 0;
                        $total_service = 0;
                        $total_penalty = 0;
                        $total_owed = 0;
                        $total = 0;
                        $payment_str = null;
                    }}
                ?>

                @foreach($histories as $pay_id => $value)
                    <?php
                        {{
                            $history = $value[0];
                            $payment = \App\Models\LoanPayment::find($pay_id);
                            $last_schedule = \App\Models\LoanCalculate::find(DB::table(getLoanCalculateTable())->where('disbursement_id', $history->loan_id)->where('principal_p', '>', 0)->max('id'));
                            $schedule  = \App\Models\LoanCalculate::find($history->schedule_id);
                            $cash_acc = \App\Models\AccountChart::find(optional($payment)->cash_acc_id);
                            $principle = \App\Models\PaymentHistory::where('payment_id',$pay_id)->where('loan_id',$history->loan_id)->sum('principal_p');
                            $interest = \App\Models\PaymentHistory::where('payment_id',$pay_id)->where('loan_id',$history->loan_id)->sum('interest_p');
                            $service_charge = \App\Models\PaymentHistory::where('payment_id',$pay_id)->where('loan_id',$history->loan_id)->sum('service_charge_p');
                            $penalty = \App\Models\PaymentHistory::where('payment_id',$pay_id)->where('loan_id',$history->loan_id)->sum('penalty_p');
                            $owed_balance = \App\Models\PaymentHistory::where('payment_id',$pay_id)->where('loan_id',$history->loan_id)->sum('owed_balance_p');
                            $total_prin += $principle;
                            $total_int += $interest;
                            $total_service += $service_charge;
                            $total_penalty += $penalty;
                            $total_owed += $owed_balance;
                            $sub_total = $principle + $interest + $service_charge +$penalty;
                            $total += $sub_total;
                            if($schedule == null){
                            //   $loop->first ?  $payment_str = $history->payment_id : $payment_str .= "x". $history->payment_id;
                              array_push($payment_array, $pay_id);
                            }
                         }}
                    ?>
                    @if($schedule == null)
                    <tr class="border-red" style="background: #fffbe8;">
                    @else
                    <tr style="background: #fffbe8;">
                    @endif
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;">{{date('d-m-Y', strtotime($history->payment_date))}}</td>
                        <td class="right">{{optional($cash_acc)->name}}</td>
                        <td class="right">{{optional($payment)->payment_number}}</td>
                        {{-- <td class="right">{{optional($loan)->loan_amount}}</td> --}}
                        <td class="right">{{number_format($principle,0)}}</td>
                        <td class="right">{{number_format($interest,0)}}</td>
                        {{-- <td class="right">{{number_format($history->service_charge_p,0)}}</td> --}}
                        <td class="right">{{number_format($penalty,0)}}</td>
                        {{-- <td class="right">{{number_format($history->owed_balance,0)}}</td> --}}
                        <td class="right">{{number_format($sub_total)}}</td>

                    </tr>

                    @php
                        {{$i++;}}
                    @endphp

                @endforeach
                <tr  style="font-weight:bold;">
                    <td colspan="4" style="text-align:center;"> Total</td>
                    <td class="right">{{number_format($total_prin,0)}}</td>
                    <td class="right">{{number_format($total_int,0)}}</td>
                    {{-- <td class="right">{{number_format($total_service,0)}}</td> --}}
                    <td class="right">{{number_format($total_penalty,0)}}</td>
                    {{-- <td class="right">{{number_format($total_owed,0)}}</td> --}}
                    <td class="right">{{number_format($total,0)}}</td>
                </tr>
            @elseif($dc->isEmpty() && count($loan_payments))
                <?php
                    {{
                        $total_prin = 0;
                        $total_int = 0;
                        $total_service = 0;
                        $total_penalty = 0;
                        $total_owed = 0;
                        $total = 0;
                    }}
                ?>
                @foreach($loan_payments as $lp)
                    <?php
                            $cash_acc = \App\Models\AccountChart::find(optional($lp)->cash_acc_id);
                            $total_prin += optional($lp)->principle;
                            $total_int += optional($lp)->interest;
                            $total_penalty += optional($lp)->penalty_amount;
                            $sub_total = optional($lp)->principle + optional($lp)->interest + optional($lp)->total_service_charge +optional($lp)->penalty_amount;
                            $total += $sub_total;
                    ?>
                    <tr style="background: #fffbe8;">
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;">{{date('d-m-Y', strtotime($lp->payment_date))}}</td>
                        <td class="right">{{optional($cash_acc)->name}}</td>
                        <td class="right">{{optional($lp)->payment_number}}</td>
                        <td class="right">{{number_format($lp->principle,0)}}</td>
                        <td class="right">{{number_format($lp->interest,0)}}</td>
                        <td class="right">{{number_format($lp->penalty_amount,0)}}</td>
                        <td class="right">{{number_format($sub_total)}}</td>
                    </tr>
                @endforeach
                <tr  style="font-weight:bold;">
                    <td colspan="4" style="text-align:center;"> Total</td>
                    <td class="right">{{number_format($total_prin,0)}}</td>
                    <td class="right">{{number_format($total_int,0)}}</td>
                    <td class="right">{{number_format($total_penalty,0)}}</td>
                    <td class="right">{{number_format($total,0)}}</td>
                </tr>
            @else
            <tr>
                <td colspan="9">No Recent Payments</td>
            </tr>
            @endif


            </tbody>
        </table>
        <br>
        <div class="text-right">
            <?php
                // $payment_array = array_unique($payment_array);
                $payment_str = implode('x', array_unique($payment_array));
            ?>
            @if(backpack_user()->can('delete-loan-payment') && count($payment_array))
                <span class="right">
                    <a href="{{url("/api/delete-duplicates")}}" data-payment_str="{{$payment_str}}" class="btn btn-xs btn-danger roll-back">Delete Duplicates</a>
                </span>
            @endif
        </div>
        @if($loanClosed)
            <h4 style="margin-top:20px;">Pending Repayments</h4>
            <table style="width: 100%; margin-top:5px;" class="text-center">
                <thead>
                    <th colspan="2">Payment Schedule Date</th>
                    {{-- <th>Cash In</th> --}}
                    {{-- <th>Loan Amount</th> --}}
                    <th>Principal</th>
                    <th>Interest</th>
                    {{-- <th>Service</th> --}}
                    <th>Penalty</th>
                    {{-- <th>Owed Balance</th> --}}
                    <th>Total</th>
                </thead>


                <tbody class="border">

                @if($pending_repayments->count() > 0)

                    @php
                        {{
                            $i = 1;
                            $total_prin = 0;
                            $total_int = 0;

                            $total_penalty = 0;
                            $total_owed = 0;
                            $total = 0;
                        }}
                    @endphp

                    @foreach($pending_repayments as $pending_repayment)
                        <?php
                        $isset_payment = PaymentHistory::where('schedule_id', $pending_repayment->id)->first();
                        //dd($isset_payment);
                                $total_prin += $pending_repayment->principal_s;
                                $total_int += $pending_repayment->interest_s;
                                $total_penalty += $pending_repayment->penalty_s;
                                $total_owed += $pending_repayment->owed_balance_p;
                                $sub_total = $pending_repayment->principal_s + $pending_repayment->interest_s + $pending_repayment->service_charge_s +$pending_repayment->penalty_s;
                                $total += $sub_total;
                        if($isset_payment != Null){
                            $isset_prin = $total_prin -  $isset_payment->principal_p;
                            $isset_interest = $total_int - $isset_payment->interest_p;
                        }
                        ?>

                        <tr style="background: #fffbe8;">
                            <td style="text-align:center;"></td>
                            <td style="text-align:center;">{{date('d-m-Y', strtotime($pending_repayment->date_s))}}</td>
                            {{-- <td class="right">{{optional($cash_acc)->name}}</td> --}}
                            {{-- <td class="right">{{optional($payment)->payment_number}}</td> --}}
                            {{-- <td class="right">{{optional($loan)->loan_amount}}</td> --}}
                            @if($isset_payment != Null)
                            @php
                                $amount_repayment = $pending_repayment->principal_s - $isset_payment->principal_p;
                                $amount_interest = $pending_repayment->interest_s - $isset_payment->interest_p;
                                $total_int -= $isset_payment->interest_p;
                                $total_prin -= $isset_payment->principal_p;
                            @endphp
                                <td class="right">{{number_format($amount_repayment,0)}}</td>
                                <td class="right">{{number_format($amount_interest,0)}}</td>
                            @else
                                <td class="right">{{number_format($pending_repayment->principal_s,0)}}</td>
                                <td class="right">{{number_format($pending_repayment->interest_s,0)}}</td>
                            @endif

                            {{-- <td class="right">{{number_format($pending_repayment->service_charge_s,0)}}</td> --}}
                            <td class="right">{{number_format($pending_repayment->penalty_s,0)}}</td>
                            {{-- <td class="right">{{number_format($pending_repayment->owed_balance_p,0)}}</td> --}}
                            <td class="right">{{number_format($sub_total)}}</td>

                        </tr>

                        @php
                            {{$i++;}}
                        @endphp

                    @endforeach
                    <tr  style="font-weight:bold;">
                        <td colspan="2" style="text-align:center;"> Total</td>
                        <td class="right">{{number_format($total_prin,0)}}</td>
                        <td class="right">{{number_format($total_int,0)}}</td>
                        <td class="right">{{number_format($total_penalty,0)}}</td>
                        <td class="right">{{number_format($total,0)}}</td>


                        {{-- <td class="right">{{number_format($total_service,0)}}</td> --}}
                        {{-- <td class="right">{{number_format($total_owed,0)}}</td> --}}

                    </tr>
                @else
                <tr>
                    <td colspan="9">No Recent Payments</td>
                </tr>
                @endif


                </tbody>
            </table>
        @endif
    </div>
    <script>
        $('.roll-back').on('click',function (e) {
            e.preventDefault();
            var url = $(this).prop('href');
            var payment_str = $(this).data('payment_str');
            var payments = payment_str.split('x');
            var note = $('.note').val();

            payments.map(function(payment) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        payment_id: payment,
                        note: note,
                    },
                    // success: function (res) {

                    // }

                });
            })
            window.location.reload();

        });
    </script>
@endif
</div>
