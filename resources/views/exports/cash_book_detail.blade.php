
<table>
    <tr>
        @foreach($rows as $v)
            <th>{{$v}}</th>
        @endforeach
    </tr>
        @if($data)
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
            $acc_chart_id = $data[2];
            }else{
                $acc_chart_id = null;
            }
            if($data[3]){
            $branches = $data[3];
            }else{
                $branches = 1;
            }

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
            @foreach($branches as $b_id)
                <?php
                    $r_begin_bal_dr = \App\Models\GeneralJournalDetail::where('section_id',10)
                        ->where(function ($query) use ($acc_chart_id) {
                            if (is_array($acc_chart_id)) {
                                if ($acc_chart_id[0] != null) {
                                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                                }
                            }
                        })
                        ->whereDate('j_detail_date','<',$start_date)
                        ->whereIn('branch_id',$branches)
                        ->sum('dr');

                    $r_begin_bal_cr = \App\Models\GeneralJournalDetail::where('section_id',10)
                        ->where(function ($query) use ($acc_chart_id) {
                            if (is_array($acc_chart_id)) {
                                if ($acc_chart_id[0] != null) {
                                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                                }
                            }
                        })
                        ->whereDate('j_detail_date','<',$start_date)
                        ->whereIn('branch_id',$branches)
                        ->sum('cr');

                    $r_begin_bal = $r_begin_bal_dr - $r_begin_bal_cr;
                    $begin_bal = $r_begin_bal;
                    $total_begin += $begin_bal;

                    $total+=$total_begin;

                    if ($begin_bal > 0){
                        $total_dr+=$begin_bal;
                    }else{
                        $total_cr+=$begin_bal;
                    }
                $cash_account = \App\Models\GeneralJournalDetail::where('section_id',10)
                    ->where(function ($query) use ($acc_chart_id) {
                        if (is_array($acc_chart_id)) {
                            if ($acc_chart_id[0] != null) {
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
                    <?php
                        $acc_id = $acc_r->acc_id;
                    ?>
                    <?php
                        $acc = \App\Models\AccountChart::find($acc_id);
                    ?>  
                    <?php
                        $transfer = optional(\App\Models\Transfer::select('description'))->where('id',$acc_r->tran_id)->first();
                        $reference_no = optional(\App\Models\GeneralJournal::find($acc_r->journal_id))->reference_no;

                        $pre = substr($reference_no, 0, 3);
                        $client = null;
                        if($pre == "RPM"){
                            $l_p = \App\Models\LoanPayment::where('payment_number', $reference_no)->first();
                            $loan_id = \App\Models\Loan::find($l_p->disbursement_id);
                            $loan_type = \App\Models\LoanProduct::find(optional($loan_id)->loan_production_id);
                            $client =  \App\Models\Client::find(optional($loan_id)->client_id);
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

                        $total += $dr_cr;
                        if ($dr_cr > 0){
                            $total_dr += $dr_cr;
                        }else{
                            $total_cr += $dr_cr;
                        }

                    ?>
                    <tr>
                        <td>{{\Carbon\Carbon::parse($acc_r->j_detail_date)->format('d-M-Y')}}</td>
                        <td>{{$reference_no}}</td>
                        @if (isset($client))
                            <td>{{$client->client_number}}</td>
                            <td>{{$client->name_other}}</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif
                        <td>{{optional($acc)->code}}</td>
                        <td>{{optional($acc)->name}}</td>
                        @if(isset($loan_type))
                        <td>
                            {{ $loan_type->name }}
                        </td>
                        @else
                        <td></td>
                        @endif
                        <td class="right">{{$dr_cr>0?numb_format($dr_cr,2):''}}</td>
                        <td class="right">{{$dr_cr<=0?numb_format(-$dr_cr,2):''}}</td>
                        <td class="right">{{numb_format($total??0,2)}}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="7" style="font-weight: bold;padding-left: 30px;text-align: right">TOTAL</td>
                <th class="right">{{ numb_format($total_dr??0,2) }}</th>
                <th class="right">{{numb_format(-$total_cr??0,2) }}</th>
                <th class="right">{{numb_format($total??0,2)}}</th>
            </tr>
        @endif
</table>
