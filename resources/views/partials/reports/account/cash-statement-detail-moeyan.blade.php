<div id="DivIdToPrint">
    <style>
        .right{
            text-align: right;
        }
    </style>
    @if($bals != null)
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
            <th>Type</th>
            <th>Date</th>
            <th>Reference No</th>
            <th>Name</th>
            <th>NRC</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Debit</th>
            <th>Credit</th>
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
            ?>
            @php
                $cash_account = \App\Models\AccountChart::where('section_id',10)->get();

            @endphp
            @foreach($branches as $b_id)
                <tr>

                    <td colspan="9" style="text-align: left"> <b>{{ optional(\App\Models\Branch::find($b_id))->title }}</b> </td>
                </tr>
                @foreach($cash_account as $acc_r)
                    {{--@foreach($bals[10] as $acc_id => $rd)--}}
                    @php
                        $acc_id = $acc_r->id;
                        $rd = isset($bals[$b_id][10][$acc_id])?$bals[$b_id][10][$acc_id]:null;


                    @endphp
                    <?php
                        /*$r_begin = \App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_id)
                            ->whereDate('j_detail_date','<',$start_date)
                            ->where(function ($query) use ($acc_chart_id) {
                                if (is_array($acc_chart_id)) {
                                    if (count($acc_chart_id) > 0) {
                                        return $query->whereIn('acc_chart_id', $acc_chart_id);
                                    }
                                }
                            })
                            ->selectRaw('sum(dr-cr) as bal')
                            ->where('branch_id',$b_id)
                            ->first();*/

                    $r_begin_dr = \App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_id)
                        ->whereDate('j_detail_date','<',$start_date)
                        ->where(function ($query) use ($acc_chart_id) {
                            if (is_array($acc_chart_id)) {
                                if (count($acc_chart_id) > 0) {
                                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                                }
                            }
                        })
                        ->where('branch_id',$b_id)
                        ->sum('dr');

                    $r_begin_cr = \App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_id)
                        ->whereDate('j_detail_date','<',$start_date)
                        ->where(function ($query) use ($acc_chart_id) {
                            if (is_array($acc_chart_id)) {
                                if (count($acc_chart_id) > 0) {
                                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                                }
                            }
                        })
                        ->where('branch_id',$b_id)
                        ->sum('cr');

                    $r_begin = $r_begin_dr - $r_begin_cr;

//                    $begin = $r_begin != null ? $r_begin->bal :0;

                    $begin = $r_begin;

                    $acc = \App\Models\AccountChart::find($acc_id);
                    ?>

                    @if($begin != 0 || $rd != null)
                        @php
                            $total_income +=   $begin;
                        @endphp
                        <tr>
                            <td colspan="6" style="font-weight: bold;padding-left: 60px">{{optional($acc)->code}} - {{optional($acc)->name}}</td>
                            <td>Begin</td>
                            <td style="text-align: right;">{{ $begin>0?numb_format($begin,2):'' }}</td>
                            <td style="text-align: right;">{{ $begin<0?numb_format(-$begin,2):'' }}</td>
                        </tr>
                    @endif

                    @if( $rd != null)
                        <?php
                        $total_line = $begin;
                        ?>


                        @foreach($rd as $row)
                            <?php
                            /*$currency_tran = $row->currency_id;
                            $price = exchangeRate($currency_tran,$base_currency,$row->sale_price);*/
                            $type = $row->tran_type;
                            $total_income += (($row->dr??0) - ($row->cr??0));
                            $total_line += (($row->dr??0) - ($row->cr??0));
                            //$pre = "ABCD";
                            $transfer = optional(\App\Models\Transfer::select('description'))->where('id',$row->tran_id)->first();
                            $transfer_reference_no = optional(\App\Models\Transfer::select('reference_no'))->where('id',$row->tran_id)->first();
                            //dd($row);
                            $reference_no = optional(\App\Models\GeneralJournal::find($row->journal_id))->reference_no;
                            $pre = substr($reference_no, 0, 3);
                            $client = null;
                            if($pre == "RPM"){
                                $l_p = \App\Models\LoanPayment::where('payment_number', $reference_no)->first();
                               
                            }
                            else if($pre == "DBM"){
                                $d_m = \App\Models\PaidDisbursement::where('reference', $reference_no)->first();
                            }

                            $gj_detail = optional(\App\Models\GeneralJournalDetail::where('journal_id',$row->journal_id)->first());
                            if(isset($gj_detail->name)){
                                $client = \App\Models\Client::find($gj_detail->name);
                            }
                                                     
                            ?>
                            <tr>
                                <td style="padding-left: 70px;text-transform: capitalize">{{$row->tran_type}}</td>
                                <td>{{\Carbon\Carbon::parse($row->j_detail_date)->format('d-M-Y')}}</td>
                                <td>
                                    @if (companyReportPart() == 'company.moeyan')
                                         @if ($row->description == 'transfer')
                                            {{optional($transfer_reference_no)->reference_no }}
                                        @else
                                            {{$reference_no}}
                                        @endif
                                    @else
                                        {{$reference_no}}
                                    @endif
                                </td>
                                <td>{{optional($client)->name}}</td>
                                <td>{{optional($client)->client_number}}</td>
                                
                                <td>
                                    @if (companyReportPart() == 'company.moeyan')
                                        @if ($row->description == 'transfer')
                                            {!! optional($transfer)->description !!}
                                        @else
                                            {!! $row->description !!}
                                        @endif
                                    @else
                                        {!! $row->description !!}
                                    @endif
                                </td>
                                
                                <td class="right"></td>
                                <td class="right">{{optional($row)->dr!=0?numb_format($row->dr,2):''}}</td>
                                <td class="right">{{optional($row)->cr!=0?numb_format($row->cr,2):''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" style="font-weight: bold;padding-left: 60px">Total-{{optional($acc)->code}} - {{optional($acc)->name}}</td>
                            <th class="right">{{$total_line>0?numb_format($total_line,2):''}}</th>
                            <th class="right">{{$total_line<0?numb_format(-$total_line,2):''}}</th>
                        </tr>
                    @endif

                @endforeach
            @endforeach
            <tr>
                <td colspan=7" style="font-weight: bold;padding-left: 30px">Total Cash</td>
                <th class="right">{{$total_income>0?numb_format($total_income,2):''}}</th>
                <th class="right">{{$total_income<0?numb_format(-$total_income,2):''}}</th>
            </tr>
            </tbody>
        </table>

    @else
        <h1>No data</h1>
    @endif
</div>
