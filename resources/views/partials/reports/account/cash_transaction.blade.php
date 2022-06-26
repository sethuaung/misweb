<div id="DivIdToPrint">
    <style>
        .right{
            text-align: right;
        }
    </style>
    @php
        $today = date('Y-m-d');
    @endphp
    @include('partials.reports.header',
    ['report_name'=>'General Leger','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
    <div>{{$today}}</div>
    @foreach($branches as $branch)
        <div><span class="pull-right" style="margin-bottom:4px;">{{\App\Models\Branch::find($branch)->title}},</span></div>
    @endforeach
    <table border="1" class="table-data" id="table-data">
        <thead>
        <th>Date</th>
        <th>Reference No</th>
        <th>Description</th>
        <th>Debit Amount</th>
        <th>Credit Amount</th>
        <th>End Balance</th>
        </thead>
        <tbody>
        @foreach($arr_acc as $acc)
            <?php
            // $begin = isset($arr_begin[$acc])?$arr_begin[$acc]:0;
            $begin = 0;
            $ac_o = optional(\App\Models\AccountChart::find($acc));
            ?>
            <tr>
                <td colspan="3" style="font-weight: bold;">{{$ac_o->code}}-{{$ac_o->name}}</td>

                <td class="right" style="font-weight: bold;">{{ $begin>0?numb_format($begin,2):'-' }}</td>
                <td class="right" style="font-weight: bold;">{{ $begin<0?numb_format(-$begin,2):'-' }}</td>
                <td></td>
            </tr>

            <?php
            $current = isset($arr_leger[$acc])?$arr_leger[$acc]:[];
            $t_dr = 0;
            $t_cr = 0;
            ?>
            @if(count($current)>0)
                @foreach($current as $row)
                    <?php
                    $begin += (($row->dr??0)- ($row->cr??0));
                    foreach($all_balances as $acc_bal){
                        if($acc_bal->acc_chart_id == $row->acc_chart_id){
                            $begin = $acc_bal->t_dr - $acc_bal->t_cr;
                        }
                    }
                    ?>
                    <tr>
                        <td style="padding-left: 70px">{{\Carbon\Carbon::parse($row->j_detail_date)->format('d-m-Y')}}</td>
                        <td>{{optional(\App\Models\GeneralJournal::find($row->journal_id))->reference_no}}</td>
                        <td>{{$row->description}}</td>
                        <td class="right">{{numb_format($row->dr,2)}}</td>
                        <td class="right">{{numb_format($row->cr,2)}}</td>
                        <td class="right">{{ $begin>0?numb_format($begin,2): "(". numb_format(-$begin,2).")"  }}</td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="font-weight: bold;">Total</td>
                    <td class="right" style="font-weight: bold;">{{ $begin>0?numb_format($begin,2):'-' }}</td>
                    <td class="right" style="font-weight: bold;">{{ $begin<0?numb_format(-$begin,2):'-' }}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    {{--@else
        <h1>No data</h1>
    @endif--}}
</div>
