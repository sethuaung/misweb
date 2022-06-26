<div id="DivIdToPrint">
@if($bal_type != null)
<?php

$symbol = optional(\App\Models\Currency::orderBy('exchange_rate','asc')->first())->currency_symbol;
?>
    @include('partials.reports.header',
    ['report_name'=>'Cash Statement','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">

        <thead>
            <tr>
                <th>Cash</th>
                @foreach($type as $tran_id => $trans)
                <th><b style="color: red;">{{ strtoupper($tran_id) }}</b></th>
                @endforeach
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $total = 0;
        ?>
        @foreach($bal_type as $acc_chart_id => $b)
            <?php
                $chart_ex = \App\Models\AccountChartExternal::find($acc_chart_id);
            ?>
            <tr>
                <td style="padding-left: 60px;">{{optional($chart_ex)->external_acc_code.'-'.optional($chart_ex)->external_acc_name}}</td>
                <?php
                    $total_line = 0
                ?>
                @foreach($type as $tran_id => $trans)
                    @php
                        $bal = isset($bal_type[$acc_chart_id][$tran_id])?$bal_type[$acc_chart_id][$tran_id]:0;
                        $total_line += $bal;
                    @endphp
                    <td style="text-align: right">{{numb_format($bal??0,2)}}{{$symbol}}</td>
                @endforeach
                <?php
                    $total += $total_line;
                ?>
                <td style="text-align: right"><b>{{$total_line}}{{$symbol}}</b></td>
            </tr>

        @endforeach
        <tr>

            <td colspan="{{count($type)+1}}" style="text-align: right"> <b>TOTAL</b> </td>
            <td style="text-align: right"><b>{{$total}}{{$symbol}}</b></td>
        </tr>
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
