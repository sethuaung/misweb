<div id="DivIdToPrint">
    <style>
        .right{
            text-align: right;
        }
    </style>
        @php
            $branches = \App\Models\Branch::where('id',$branch_id)->get();
            $today = date('Y-m-d');
        @endphp
        @include('partials.reports.header',
        ['report_name'=>'General Leger','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])
        <div>{{$today}}</div>
        @foreach($branches as $branch)
            <div><span class="pull-right" style="margin-bottom:4px;">{{$branch->title}},</span></div>
        @endforeach
       
        <table border="1" class="table-data" id="table-data">
            <thead>
            <th>Date</th>
            <th>Reference No</th>
            <th>Description</th>
            <th>Client Name</th>
            <th>Client Code</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>End Balance</th>
            </thead>
            <tbody>


            <?php
                //dd($acc_chart_id);
                $total_begin = 0;
                $total_dr = 0;
                $total_cr = 0;
                $total = 0;

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


//                $begin_bal = $r_begin_bal != null ? $r_begin_bal->bal :0;

                $begin_bal = $r_begin_bal_dr - $r_begin_bal_cr;

                $total_begin += $begin_bal;

                if ($begin_bal > 0){
                    $total_dr += ($begin_bal>=0?$begin_bal:-$begin_bal);
                }else{
                    $total_cr += ($begin_bal>=0?$begin_bal:-$begin_bal);
                }


            ?>

            @if(!isset($acc_chart_id))
            <tr>
                <td colspan="2" style="border-right: 0px"></td>
                <td colspan="3" style="border-left: 0px">Opening Balance</td>

                <td style="text-align: right">{{$begin_bal>0?numb_format($begin_bal??0,2):''}}</td>
                <td style="text-align: right">{{$begin_bal<=0?numb_format(-$begin_bal??0,2):''}}</td>
                <td style="text-align: right">{{$total_begin>0?numb_format($total_begin??0,2):'('.numb_format(-$total_begin??0,2).')'}}</td>
            </tr>
            @endif

            @foreach($arr_acc as $acc)
                <?php
                    $begin = isset($arr_begin[$acc])?$arr_begin[$acc]:0;
                    $ac_o = optional(\App\Models\AccountChart::find($acc));


                ?>
                <tr>
                    <td colspan="5" style="font-weight: bold;">{{$ac_o->code}}-{{$ac_o->name}}</td>

                    <td class="right" style="font-weight: bold;">{{ $begin>0?numb_format($begin,2):'-' }}</td>
                    <td class="right" style="font-weight: bold;">{{ $begin<0?numb_format(-$begin,2):'-' }}</td>
                    <td></td>
                </tr>

                <?php
                    $current = isset($arr_leger[$acc])?$arr_leger[$acc]:[];
                    $t_dr = 0;
                    $t_cr = 0;

                    if ($begin > 0){
                        $total_dr += ($begin>=0?$begin:-$begin);
                    }else{
                        $total_cr += ($begin>=0?$begin:-$begin);
                    }
                ?>
                @if(count($current)>0)
                    @foreach($current as $row)
                        <?php
                            $begin += (($row->dr??0)- ($row->cr??0));
                            $gj = \App\Models\GeneralJournal::find($row->journal_id);
                            $gj_detail = \App\Models\GeneralJournalDetail::where('journal_id',$row->journal_id)->first();
                            if(isset($gj_detail->name)){
                                $client = \App\Models\Client::find($gj_detail->name);
                            }
                            else{
                                $client = Null;
                            }
                        ?>
                    <tr>
                        <td style="padding-left: 70px">{{\Carbon\Carbon::parse($row->j_detail_date)->format('d-m-Y')}}</td>
                        <td>{{optional($gj)->reference_no}}</td>
                        <td>{{$row->description}}</td>
                        @if($client)
                        <td>{{$client->name_other?$client->name_other:$client->name}}</td>
                        @else
                        <td></td>
                        @endif
                        <td>{{optional($client)->client_number}}</td>
                        <td class="right">{{numb_format($row->dr,2)}}</td>
                        <td class="right">{{numb_format($row->cr,2)}}</td>
                        <td class="right">{{ $begin>0?numb_format($begin,2): "(". numb_format(-$begin,2).")"  }}</td>

                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" style="font-weight: bold;">Total</td>
                        <td class="right" style="font-weight: bold;">{{ $begin>0?numb_format($begin,2):'-' }}</td>
                        <td class="right" style="font-weight: bold;">{{ $begin<0?numb_format(-$begin,2):'-' }}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach

            <?php
                $total = $total_dr - $total_cr;
            ?>

            <tr>
                <td colspan="5" style="font-weight: bold;text-align: right">TOTAL</td>
                <td class="right" style="font-weight: bold;">{{ $total_dr>0?numb_format($total_dr,2): numb_format(-$total_dr,2) }}</td>
                <td class="right" style="font-weight: bold;">{{ $total_cr>0?numb_format($total_cr,2): numb_format(-$total_cr,2) }}</td>
                <td class="right" style="font-weight: bold;">{{ $total>0?numb_format($total,2): '('.numb_format(-$total,2).')' }}</td>
            </tr>

            </tbody>
        </table>

    {{--@else
        <h1>No data</h1>
    @endif--}}
</div>
