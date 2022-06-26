
<table>
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

            $total_begin = 0;
            $total_dr = 0;
            $total_cr = 0;
            $total = 0;
            //dd($acc_chart_id);
            $r_begin_bal_dr = \App\Models\GeneralJournalDetail::where('section_id',10)
                        ->when($acc_chart_id,function ($query) use ($acc_chart_id) {
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
                ->when($acc_chart_id,function ($query) use ($acc_chart_id) {
                    if (is_array($acc_chart_id)) {
                        if (count($acc_chart_id) > 0) {
                            return $query->whereIn('acc_chart_id', $acc_chart_id);
                        }
                    }
                })
                ->whereDate('j_detail_date','<',$start_date)
                ->where('branch_id',$branch_id)
                ->sum('cr');


            

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
                <td colspan="2"></td>
                <td colspan="3">Opening Balance</td>

                <td style="text-align: right">{{$begin_bal>0?numb_format($begin_bal??0,2):''}}</td>
                <td style="text-align: right">{{$begin_bal<=0?numb_format(-$begin_bal??0,2):''}}</td>
                <td style="text-align: right">{{$total_begin>0?numb_format($total_begin??0,2):'('.numb_format(-$total_begin??0,2).')'}}</td>
            </tr>
        @endif
        @if($arr_acc)
        @foreach($arr_acc as $acc)
            <?php
                $begin = isset($arr_begin[$acc])?$arr_begin[$acc]:0;
                $ac_o = \App\Models\AccountChart::find($acc);

            ?>
            <tr>
                <td colspan="5" style="font-weight: bold;">{{optional($ac_o)->code}}-{{optional($ac_o)->name}}</td>

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
                        $begin += ((optional($row)->dr??0)- (optional($row)->cr??0));
                        $gj = \App\Models\GeneralJournal::find(optional($row)->journal_id);
                        $gj_detail = \App\Models\GeneralJournalDetail::where('journal_id',optional($row)->journal_id)->first();
                        if(isset($gj_detail->name)){
                            $client = \App\Models\Client::find($gj_detail->name);
                        }
                        else{
                            $client = Null;
                        }
                    ?>
                <tr>
                    <td style="padding-left: 70px">{{\Carbon\Carbon::parse(optional($row)->j_detail_date)->format('d-m-Y')}}</td>
                    <td>{{optional($gj)->reference_no}}</td>
                    <td>{{optional($row)->description}}</td>
                    @if($client)
                    <td>{{$client->name_other?$client->name_other:$client->name}}</td>
                    @endif
                    <td>{{optional($client)->client_number}}</td>
                    <td class="right">{{numb_format(optional($row)->dr,2)}}</td>
                    <td class="right">{{numb_format(optional($row)->cr,2)}}</td>
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

        @endif
</table>
