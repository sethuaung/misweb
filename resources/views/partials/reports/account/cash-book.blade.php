<div id="DivIdToPrint">

<?php

$symbol = '';
?>
    @php
        $today = date('Y-m-d');
    @endphp
    @include('partials.reports.header',
    ['report_name'=>'Cash Book','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
    <div>{{$today}}</div>
        @foreach($branches as $branch)
            <div><span class="pull-right" style="margin-bottom:4px;">{{\App\Models\Branch::find($branch)->title}},</span></div>
        @endforeach
    <table border="1" class="table-data" id="table-data">

        <thead>
            <tr>
                {{--<th>Begin</th>--}}
                <th width="100px">Date</th>
                <th>Reference No</th>
                <th width="100px">Account Code</th>
                <th>Account Name</th>
                <th>Description</th>
                <th>In</th>
                <th>Out</th>
                {{--<th>Cash</th>--}}
                {{--<th>Begin</th>--}}
                {{--@foreach($type as $tran_id => $trans)
                <th><b style="color: red;">{{ strtoupper($tran_id) }}</b></th>
                @endforeach--}}
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $total = 0;
            $total_dr=0;
            $total_cr=0;
            $total_begin=0;

            //$cash_account = \App\Models\AccountChart::where('section_id',10)->get();

        ?>

        @foreach($branches as $b_id)
            <?php

//                    dd($start_date);

                $branch_id=$b_id-0;

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
                        return $query->where('j_detail_date', '>=', $start_date)
                            ->where('j_detail_date', '<=', $end_date);
                    }

                })
                ->selectRaw('acc_chart_id as id')
                ->groupBy('acc_chart_id')
                ->get();


                /*$r_begin_bal = \App\Models\GeneralJournalDetail::where('section_id',10)
                    ->where(function ($query) use ($acc_chart_id) {
                        if (is_array($acc_chart_id)) {
                            if (count($acc_chart_id) > 0) {
                                return $query->whereIn('acc_chart_id', $acc_chart_id);
                            }
                        }
                    })
                    ->whereDate('j_detail_date','<',$start_date)
                    ->where('branch_id',$branch_id)
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
                    ->where('branch_id',$branch_id)
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
                    ->where('branch_id',$branch_id)
                    ->sum('cr');


                $r_begin_bal = $r_begin_bal_dr - $r_begin_bal_cr;

//                dd($r_begin_bal_cr);

//                $begin_bal = $r_begin_bal != null ? $r_begin_bal->bal :0;

                $begin_bal=$r_begin_bal;

                $total += $begin_bal;
                if ($begin_bal > 0){
                    $total_dr += ($begin_bal>=0?$begin_bal:-$begin_bal);
                }else{
                    $total_cr += ($begin_bal>=0?$begin_bal:-$begin_bal);
                }

                $total_begin += $begin_bal;
            ?>

            <tr>

                <td colspan="8" style="text-align: left"> <b>{{ optional(\App\Models\Branch::find($branch_id))->title }}</b> </td>
            </tr>


            <tr>
                <td colspan="3">{{date("Y-m-d", strtotime($start_date))}}</td>
                <td></td>
                <td>Opening Balance</td>
                <td style="text-align: right">{{$begin_bal>0?numb_format($begin_bal??0,2):''}}</td>
                <td style="text-align: right">{{$begin_bal<=0?numb_format(-$begin_bal??0,2):''}}</td>
                <td style="text-align: right"><b>{{numb_format($total_begin??0,2)}}{{$symbol}}</b></td>
            </tr>
        @foreach($cash_account as $acc_c)
            <?php
                $acc_id = $acc_c->id;
                $acc = \App\Models\AccountChart::find($acc_id);
                $j_detail=\App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_id)->first();
//                $journal=\App\Models\GeneralJournal::find(optional($j_detail)->journal_id);

//                dd($acc_c);

                //$b = isset($bal_type[$acc_chart_id])?$bal_type[$acc_chart_id]:null;

                /*$bal = $acc_c->bal != null ? $acc_c->bal :0;

                $total += $bal;
                if ($bal > 0){
                    $total_dr += $bal;
                }else{
                    $total_cr += $bal;
                }*/



            //                $begin = $bal;


                $cash_account_dr = \App\Models\GeneralJournalDetail::where('section_id',10)
                    ->where('acc_chart_id',$acc_id)
                    ->where('branch_id',$branch_id)
                ->where(function ($query) use ($acc_chart_id) {
                    if (is_array($acc_chart_id)) {
                        if (count($acc_chart_id) > 0) {
                            return $query->whereIn('acc_chart_id', $acc_chart_id);
                        }
                    }
                })
                ->where(function ($query) use ($start_date, $end_date) {
                    if ($start_date != null && $end_date != null) {
                        return $query->where('j_detail_date', '>=', $start_date)
                            ->where('j_detail_date', '<=', $end_date);
                    }

                })

                ->sum('dr');

                $cash_account_cr = \App\Models\GeneralJournalDetail::where('section_id',10)
                    ->where('acc_chart_id',$acc_id)
                    ->where('branch_id',$branch_id)
                    ->where(function ($query) use ($acc_chart_id) {
                        if (is_array($acc_chart_id)) {
                            if (count($acc_chart_id) > 0) {
                                return $query->whereIn('acc_chart_id', $acc_chart_id);
                            }
                        }
                    })
                    ->where(function ($query) use ($start_date, $end_date) {
                        if ($start_date != null && $end_date != null) {
                            return $query->where('j_detail_date', '>=', $start_date)
                                ->where('j_detail_date', '<=', $end_date);
                        }

                    })
                    ->sum('cr');

                $bal = ($cash_account_dr-$cash_account_cr);

                $total += $bal;
                /*if ($bal > 0){
                    $total_dr += $bal;
                }else{
                    $total_cr += $bal;
                }*/

                $total_dr += ($cash_account_dr>=0?$cash_account_dr:-$cash_account_dr);
                $total_cr += ($cash_account_cr>=0?$cash_account_cr:-$cash_account_cr);


//                dd($cash_account_dr,$cash_account_cr);


            ?>
        {{--@foreach($bal_type as $acc_chart_id => $b)--}}

                <tr>


                    <?php
//                        $total += $total_line;
                       /* if ($begin > 0){
                            $total_dr += $begin;
                        }else{
                            $total_cr += $begin;
                        }*/
//                        $total_dr += optional($begin)->dr??0*1;
//                        $total_cr += optional($begin)->cr??0*1;
//                        $total_begin += $begin;
                    ?>

                    <td></td>
                    <td></td>
                    {{--<td style="text-align: center">{{optional($j_detail)->j_detail_date!=null?optional($j_detail)->j_detail_date->format('Y-m-d'):''}}</td>--}}
{{--                    <td style="text-align: center">{{optional($journal)->reference_no}}</td>--}}
                    <td style="text-align: center">{{optional($acc)->code}}</td>
                    <td>{{optional($acc)->name}}</td>
                    <td>{{optional($j_detail)->description}}</td>
{{--                    <td style="text-align: right">{{$bal>0?numb_format($bal??0,2):''}}{{$symbol}}</td>--}}
{{--                    <td style="text-align: right">{{$bal<=0?numb_format(-$bal??0,2):''}}{{$symbol}}</td>--}}
                    <td style="text-align: right">{{$cash_account_dr>=0?numb_format($cash_account_dr??0,2):numb_format(-$cash_account_dr,2)}}{{$symbol}}</td>
                    <td style="text-align: right">{{$cash_account_cr>=0?numb_format($cash_account_cr??0,2):numb_format(-$cash_account_cr,2)}}{{$symbol}}</td>
                    <td style="text-align: right"><b>{{ numb_format($total??0,2) }}{{$symbol}}</b></td>
                </tr>
            {{--@endif--}}
        @endforeach
        @endforeach
        <tr>
            <td colspan="5" style="text-align: right"> <b>TOTAL</b> </td>
            <td style="text-align: right"><b>{{ $total_dr>=0?numb_format($total_dr??0,2):numb_format(-$total_dr,2) }}{{$symbol}}</b></td>
            <td style="text-align: right"><b>{{ $total_cr>=0?numb_format($total_cr??0,2):numb_format(-$total_cr,2) }}{{$symbol}}</b></td>
            <td style="text-align: right"><b>{{ numb_format($total??0,2) }}{{$symbol}}</b></td>
        </tr>
        </tbody>
    </table>


</div>
