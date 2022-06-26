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
            <th>Name</th>
            <th>Client Code</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>End Balance</th>
            </thead>
            <tbody>
            @foreach($arr_acc as $acc)
                <?php
                    $begin = isset($arr_begin[$acc])?$arr_begin[$acc]:0;
                    $ac_o = optional(\App\Models\AccountChart::find($acc));
                    //dd($begin);
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
                        ?>
                    <tr>
                        <td style="padding-left: 70px">{{\Carbon\Carbon::parse($row->j_detail_date)->format('d-m-Y')}}</td>
                        <td>{{optional($gj)->reference_no}}</td>
                        <td>{{$row->description}}</td>
                        @if(isset($client->name) && isset($client->name_other))
                        <td>{{optional($client)->name_other}}</td>
                        @elseif(empty($client->name) || isset($client->name_other))
                        <td>{{optional($client)->name_other}}</td>
                        @else(isset($client->name) || empty($client->name_other))
                        <td>{{optional($client)->name}}</td>
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
            </tbody>
        </table>

    {{--@else
        <h1>No data</h1>
    @endif--}}
</div>
