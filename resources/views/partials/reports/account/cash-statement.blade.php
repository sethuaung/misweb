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
                <th>Cash</th>
                <th>Begin</th>
                @foreach($type as $tran_id => $trans)
                <th><b style="color: red;">{{ strtoupper($tran_id) }}</b></th>
                @endforeach
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $total = 0;

            //$cash_account = \App\Models\AccountChart::where('section_id',10)->get();
            $cash_account = \App\Models\GeneralJournalDetail::where('section_id',10)->selectRaw('acc_chart_id as id')
                ->where(function ($query) use ($acc_chart_id) {
                    if (is_array($acc_chart_id)) {
                        if (count($acc_chart_id) > 0) {
                            return $query->whereIn('acc_chart_id', $acc_chart_id);
                        }
                    }
                })
                ->groupBy('acc_chart_id')
                ->get();

        ?>

        @foreach($branches as $b_id)
            <tr>

                <td colspan="{{count($type)+3}}" style="text-align: left"> <b>{{ optional(\App\Models\Branch::find($b_id))->title }}</b> </td>
            </tr>
        @foreach($cash_account as $acc_c)
            @php
                $acc_chart_id = $acc_c->id;
                $acc = \App\Models\AccountChart::find($acc_chart_id);
                //$b = isset($bal_type[$acc_chart_id])?$bal_type[$acc_chart_id]:null;
            @endphp
        {{--@foreach($bal_type as $acc_chart_id => $b)--}}
            <?php
            /*$r_begin = \App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_chart_id)->whereDate('j_detail_date','<',$start_date)
                ->selectRaw('sum(dr-cr) as bal')->where('branch_id',$b_id)->first();
            */
//            $begin = $r_begin != null ? $r_begin->bal :0;

            $r_begin_dr = \App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_chart_id)
                ->whereDate('j_detail_date','<',$start_date)
                ->where('branch_id',$b_id)
                ->sum('dr');
            $r_begin_cr = \App\Models\GeneralJournalDetail::where('acc_chart_id',$acc_chart_id)
                ->whereDate('j_detail_date','<',$start_date)
                ->where('branch_id',$b_id)
                ->sum('cr');

            $r_begin = $r_begin_dr - $r_begin_cr;
            $begin = $r_begin;

            ?>
            <?php
            $total_line = $begin;
            ?>
            @foreach($type as $tran_id => $trans)
                @php
                    $bal = isset($bal_type[$b_id][$acc_chart_id][$tran_id])?$bal_type[$b_id][$acc_chart_id][$tran_id]:0;
                    $total_line += $bal;
                @endphp
            @endforeach
            @if($total_line>0)
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{optional($acc)->code}}-{{optional($acc)->name}}</td>
                    <td style="text-align: right">
                        {{ numb_format($begin??0,2) }}{{$symbol}}
                    </td>
                    <?php
                    $total_line = $begin;
                    ?>
                    @foreach($type as $tran_id => $trans)
                        @php
                            $bal = isset($bal_type[$b_id][$acc_chart_id][$tran_id])?$bal_type[$b_id][$acc_chart_id][$tran_id]:0;
                            $total_line += $bal;
                        @endphp
                        <td style="text-align: right">{{numb_format($bal??0,2)}}{{$symbol}}</td>
                    @endforeach
                    <?php
                        $total += $total_line;
                    ?>
                    <td style="text-align: right"><b>{{ numb_format($total_line??0,2) }}{{$symbol}}</b></td>
                </tr>
            @endif
        @endforeach
        @endforeach
        <tr>
            <td colspan="{{count($type)+2}}" style="text-align: right"> <b>TOTAL</b> </td>
            <td style="text-align: right"><b>{{ numb_format($total,2) }}{{$symbol}}</b></td>
        </tr>
        </tbody>
    </table>


</div>
