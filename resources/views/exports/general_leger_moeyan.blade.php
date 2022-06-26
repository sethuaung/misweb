
<table border="1" class="table-data" id="table-data">
    <tr>
        @foreach($rows as $v)
            <th>{{$v}}</th>
        @endforeach
    </tr>
    <?php
            if($data[0]){
                $start_date = $data[0];
            }else{
                $start_date = null;
            }
            if($data[1]){
            $end_date = $data[1];
            }else{
                $end_date = null;
            }
            if($data[2]){
            $arr_acc = $data[2];
            }else{
                $arr_acc = null;
            }
            if($data[3]){
            $arr_begin = $data[3];
            }else{
                $arr_begin = null;
            }
            if($data[4]){
            $arr_leger = $data[4];
            }else{
                $arr_leger = null;
            }
            if($data[5]){
            $acc_chart_id = $data[5];
            }else{
                $acc_chart_id = null;
            }
            if($data[6]){
            $branch_id = $data[6];
            }else{
                $branch_id = 1;
            }
            if($data[7]){
                $all_balances = $data[7];
            }else{
                $all_balances = null;
            }
            ?>
    @foreach($arr_acc as $acc)
        <?php
            // $begin = isset($arr_begin[$acc])?$arr_begin[$acc]:0;
            $begin = $arr_begin;
            // $begin = 0;
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
            $end = 0;

        ?>
        @if(count($current)>0)
            @foreach($current as $row)
                <?php
                $begin += (($row->dr??0)- ($row->cr??0));
                $end = $arr_begin + (($row->dr??0)- ($row->cr??0));
                $arr_begin = $arr_begin + (($row->dr??0)- ($row->cr??0));
                $gj = \App\Models\GeneralJournal::find($row->journal_id);
                $gj_detail = \App\Models\GeneralJournalDetail::where('journal_id',$row->journal_id)->first();
                $transfer = optional(\App\Models\Transfer::find($row->tran_id));
                if(isset($gj_detail->name)){
                    $client = \App\Models\Client::find($gj_detail->name);
                }
                foreach($all_balances as $acc_bal){
                    if($acc_bal->acc_chart_id == $row->acc_chart_id){
                        $begin = $acc_bal->t_dr - $acc_bal->t_cr;
                    }
                }
                ?>
            <tr>
                <td style="padding-left: 70px">{{\Carbon\Carbon::parse($row->j_detail_date)->format('d-m-Y')}}</td>
                <td>
                    @if ($row->description == 'transfer' && $transfer->reference_no != null)
                        {{$transfer->reference_no}}
                    @else
                        {{optional($gj)->reference_no}}
                    @endif
                </td>
                <td>
                    @if ($row->description == 'transfer' && $transfer->description != null)
                            {!! $transfer->description !!}
                    @else
                        {{$row->description}}
                    @endif
                </td>
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
                <td class="right">{{ $end >0?numb_format($end, 2): "(-". numb_format(-$end,2).")"  }}</td>

            </tr>
            @endforeach
            <tr>
                <td colspan="5" style="font-weight: bold;">Total</td>
                <td class="right" style="font-weight: bold;">{{ $end>0?numb_format($end,2):'-' }}</td>
                <td class="right" style="font-weight: bold;">{{ $end<0?numb_format($end,2):'-' }}</td>
                <td></td>
            </tr>
        @endif
    @endforeach
</table>