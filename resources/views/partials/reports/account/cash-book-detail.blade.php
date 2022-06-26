<div id="DivIdToPrint">
    <style>
        .right{
            text-align: right;
        }
    </style>
{{--    @if($bals != null)--}}
        <?php
            $base_currency = optional(\App\Models\Currency::orderBy('exchange_rate','asc')->first())->id;
        ?>
        @php
            $today = date('Y-m-d');
        @endphp
        @include('partials.reports.header',
        ['report_name'=>'Cash Statement Detail','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
        <div>{{$today}}</div>
        @foreach($branches as $branch)
        <div><span class="pull-right" style="margin-bottom:4px;">{{\App\Models\Branch::find($branch)->title}},</span></div>
        @endforeach
        <table border="1" class="table-data" id="table-data">
            <thead>
            {{--<th>Type</th>--}}
            <th width="100px">Date</th>
            <th>Reference No</th>
            @if(companyReportPart() == 'company.mkt')
            <th>Client ID</th>
            <th>Client Name</th>
            @endif
            <th>Account Code</th>
            <th>Account Name</th>
            {{--<th>NRC</th>--}}
            <th>Description</th>
            {{--<th>Amount</th>--}}
            <th>In</th>
            <th>Out</th>
            {{--<th>SUM</th>--}}
            <th>Balance</th>
            </thead>
            <tbody>

            <?php
                $total_income = 0;
                $total_cogs = 0;
                $total_expense = 0;
                $total_net_income = 0;
                $total_net_ordinary = 0;
                $total_other_income= 0;
                $total_other_expense = 0;
                $total_all = 0;
                $gross_profit = 0;
                $total_begin = 0;
                $total=0;
                $total_dr=0;
                $total_cr=0;
            ?>
            @php
                //$cash_account = \App\Models\AccountChart::where('section_id',10)->get();


            @endphp
            @foreach($branches as $b_id)
                <?php
                    /*$r_begin_bal = \App\Models\GeneralJournalDetail::where('section_id',10)
                        ->where(function ($query) use ($acc_chart_id) {
                            if (is_array($acc_chart_id)) {
                                if (count($acc_chart_id) > 0) {
                                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                                }
                            }
                        })
                        ->whereDate('j_detail_date','<',$start_date)
                        ->where('branch_id',$branches)
                        ->selectRaw('sum(dr-cr) as bal')
                        ->first();*/

                    $r_begin_bal_dr = \App\Models\GeneralJournalDetail::where('section_id',10)
                        ->where(function ($query) use ($acc_chart_id) {
                            if (is_array($acc_chart_id)) {
                                if (count($acc_chart_id) > 0) {
                                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                                }
                            }
                        })
                        ->whereDate('j_detail_date','<',$start_date)
                        ->where('branch_id',$branches)
                        ->sum('dr');

                    $r_begin_bal_cr = \App\Models\GeneralJournalDetail::where('section_id',10)
                        ->where(function ($query) use ($acc_chart_id) {
                            if (is_array($acc_chart_id)) {
                                if (count($acc_chart_id) > 0) {
                                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                                }
                            }
                        })
                        ->whereDate('j_detail_date','<',$start_date)
                        ->where('branch_id',$branches)
                        ->sum('cr');


                    $r_begin_bal = $r_begin_bal_dr - $r_begin_bal_cr;

//                    $begin_bal = $r_begin_bal != null ? $r_begin_bal->bal :0;

                    $begin_bal = $r_begin_bal;
                    $total_begin += $begin_bal;

//                    dd($total_begin);

                    $total+=$total_begin;

                    if ($begin_bal > 0){
                        $total_dr+=$begin_bal;
                    }else{
                        $total_cr+=$begin_bal;
                    }



                $cash_account = \App\Models\GeneralJournalDetail::where('section_id',10)
                    ->where(function ($query) use ($acc_chart_id) {
                        if (is_array($acc_chart_id)) {
                            if (count($acc_chart_id) > 0) {
                                return $query->whereIn('acc_chart_id', $acc_chart_id);
                            }
                        }
                    })
                    ->where('branch_id', $b_id)
                    ->where(function ($query) use ($start_date, $end_date) {
                        if ($start_date != null && $end_date != null) {
                            return $query->whereDate('j_detail_date', '>=', $start_date)
                                ->whereDate('j_detail_date', '<=', $end_date);
                        }

                    })
                    ->selectRaw('id, acc_chart_id as acc_id, tran_id, description, journal_id, dr, cr, j_detail_date')
                    ->orderBy('j_detail_date','ASC')
                    ->get();



                ?>

                <tr>

                    <td colspan="10" style="text-align: left"> <b>{{ optional(\App\Models\Branch::find($b_id))->title }}</b> </td>
                </tr>

                <tr>
                    <td colspan="5"></td>
                    <td></td>
                    <td>Opening Balance</td>
                    <td style="text-align: right">{{$begin_bal>0?numb_format($begin_bal??0,2):''}}</td>
                    <td style="text-align: right">{{$begin_bal<=0?numb_format(-$begin_bal??0,2):''}}</td>
                    <td style="text-align: right"><b>{{numb_format($total_begin??0,2)}}</b></td>
                </tr>

                @foreach($cash_account as $acc_r)
                    {{--@foreach($bals[10] as $acc_id => $rd)--}}
                    <?php
                    //dd($start_date);
                        $acc_id = $acc_r->acc_id;
                        //$rd = isset($bals[$b_id][10][$acc_id])?$bals[$b_id][10][$acc_id]:null;

//                        dd($acc_r);


                    ?>
                    <?php
                        /*$r_begin = \App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_id)
                            ->whereDate('j_detail_date','<',$start_date)
                            ->selectRaw('sum(dr-cr) as bal')
                            ->where('branch_id',$b_id)
                            ->where('tran_id',$acc_r->tran_id)
                            ->first();*/

//                        dd($r_begin);

                        $acc = \App\Models\AccountChart::find($acc_id);


                    ?>


{{--                    @if( $rd != null)--}}


                        {{--@foreach($rd as $row)--}}
                            <?php
//                                    dd($row);
                                /*$currency_tran = $row->currency_id;
                                $price = exchangeRate($currency_tran,$base_currency,$row->sale_price);*/
//                                $type = $row->tran_type;
//                                $total_income += (($row->dr??0) - ($row->cr??0));
    //                            $total_line += (($row->dr??0) - ($row->cr??0));
                                //$pre = "ABCD";
                                $transfer = optional(\App\Models\Transfer::select('description'))->where('id',$acc_r->tran_id)->first();
                                //dd($transfer);
                                $reference_no = optional(\App\Models\GeneralJournal::find($acc_r->journal_id))->reference_no;
        
                                $pre = substr($reference_no, 0, 3);
                                $client = null;
                                if($pre == "RPM"){
                                    $l_p = \App\Models\LoanPayment::where('payment_number', $reference_no)->first();
                                    $loan_id = \App\Models\Loan::find($l_p->disbursement_id);
                                    $loan_type = \App\Models\LoanProduct::find(optional($loan_id)->loan_production_id);
                                    $client =  \App\Models\Client::find(optional($loan_id)->client_id);
                                    //dd($loan_type);

                                }
                                else if($pre == "DBM"){
                                    $d_m = \App\Models\PaidDisbursement::where('reference', $reference_no)->first();
                                    $loan_id = \App\Models\Loan::find($d_m->contract_id);
                                    $loan_type = \App\Models\LoanProduct::find(optional($loan_id)->loan_production_id);
                                    $client =  \App\Models\Client::find(optional($loan_id)->client_id);
                                }

                                $gj_detail = optional(\App\Models\GeneralJournalDetail::where('journal_id',$acc_r->journal_id)->first());
                                if(isset($gj_detail->name)){
                                    $client = \App\Models\Client::find($gj_detail->name);
                                }


                                $dr=optional($acc_r)->dr??0;
                                $cr=optional($acc_r)->cr??0;

                                $dr_cr=($dr-$cr);
//                                dd(($dr-$cr));


                                $total += $dr_cr;

//                                $total_dr += $dr;
//                                $total_cr += $cr;

                                if ($dr_cr > 0){
                                    $total_dr += $dr_cr;
                                }else{
                                    $total_cr += $dr_cr;
                                }

                            ?>
                            <tr>
                                {{--<td style="padding-left: 70px;text23 Cash Account(Taungoo)	-transform: capitalize">{{$row->tran_type}}</td>--}}
                                <td>{{\Carbon\Carbon::parse($acc_r->j_detail_date)->format('d-M-Y')}}</td>
                                <td>{{$reference_no}}</td>
                                @if (companyReportPart() == 'company.mkt' && isset($client))
                                    <td>{{$client->client_number}}</td>
                                    <td>{{$client->name_other}}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                                <td>{{optional($acc)->code}}</td>
                                <td>{{optional($acc)->name}}</td>
{{--                                <td>{{optional($client)->name}}</td>--}}
{{--                                <td>{{optional($client)->client_number}}</td>--}}
                                
                                <td>
                                    @if (companyReportPart() == 'company.moeyan')
                                        @if ($acc_r->description == 'transfer')
                                            {!! optional($transfer)->description !!}
                                        @else
                                            {!! $acc_r->description !!}
                                        @endif
                                    @elseif(companyReportPart() == 'company.mkt')
                                        @if(isset($loan_type))
                                            {!! $loan_type->name !!}
                                        @else

                                        @endif       
                                    @else
                                            {!! $acc_r->description !!}
                                    @endif
                                </td>

                                {{--<td class="right">{{$begin}}</td>--}}
                                
                                {{--<td class="right"></td>--}}

                                <td class="right">{{$dr_cr>0?numb_format($dr_cr,2):''}}</td>
                                <td class="right">{{$dr_cr<=0?numb_format(-$dr_cr,2):''}}</td>
{{--                                <td class="right">{{optional($acc_r)->dr!=0?numb_format($acc_r->dr,2):''}}</td>--}}
{{--                                <td class="right">{{optional($acc_r)->cr!=0?numb_format($acc_r->cr,2):''}}</td>--}}

                                <td class="right">{{numb_format($total??0,2)}}</td>
                            </tr>
                        @endforeach
                        {{--<tr>
                            <td colspan="7" style="font-weight: bold;padding-left: 60px">Total-{{optional($acc)->code}} - {{optional($acc)->name}}</td>
                            <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                            <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                        </tr>--}}
                    {{--@endif--}}

                {{--@endforeach--}}
            @endforeach
            <tr>
                <td colspan="5" style="font-weight: bold;padding-left: 30px;text-align: right">TOTAL</td>
                <th class="right">{{ numb_format($total_dr??0,2) }}</th>
                <th class="right">{{numb_format(-$total_cr??0,2) }}</th>
                <th class="right">{{numb_format($total??0,2)}}</th>

            </tr>
            </tbody>
        </table>

    {{--@else--}}
        {{--<h1>No data</h1>--}}
    {{--@endif--}}
</div>
