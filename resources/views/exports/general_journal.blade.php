
@if($data != null)
    <?php
    $total_dr = 0;
    $total_cr = 0;
    $journal_id=0;
    $g_journals = $data;
    ?>
    <tr>
        @foreach($rows as $v)
            <th class="font-weight:bold;">{{$v}}</th>
        @endforeach
    </tr>
    @foreach($g_journals as $g)
        <?php
            $data = \App\Models\GeneralJournalDetail::where('journal_id',$g->id)->get();
        ?>

        @foreach($data as $g)


            <?php

            if(isset($_REQUEST['client_id']) && $_REQUEST['client_id'] != 0){
                $client_id = $_REQUEST['client_id'];
                $g_d = \App\Models\GeneralJournalDetail::where('id',$g->id)->where('name', $client_id)->first();
            }
            else {
                $g_d = \App\Models\GeneralJournalDetail::where('id',$g->id)->first();
            }

            $branch_id = optional($g_d)->branch_id;
            $t_dr = 0;
            $t_cr = 0;

            ?>
            @if($g_d != null)
                <?php
                    $ref = '';
                    $type = $g_d->tran_type;
                    $cus = optional(\App\Models\Customer::find(optional($g_d)->name));
                    $acc_chart_name = \App\Models\AccountChart::where('code',$g_d->acc_chart_code)->first();
                    if($type == 'loan-deposit'){
                        $deposit = \App\Models\LoanDeposit::find($g_d->tran_id);
                        $ref = optional($deposit)->referent_no;
                    }
                    else if($type == 'loan-disbursement'){
                        $deposit = \App\Models\PaidDisbursement::find($g_d->tran_id);
                        $ref = optional($deposit)->reference;
                    }
                    else if($type == 'payment'){
                        $deposit = \App\Models\LoanPayment::find($g_d->tran_id);
                        $ref = optional($deposit)->payment_number;
                    }
                    else if($type == 'transfer'){
                        $deposit = \App\Models\Transfer::find($g_d->tran_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    else if($type == 'expense'){
                        $deposit = \App\Models\Expense::find($g_d->tran_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    else if($type == 'profit'){
                        $deposit = \App\Models\JournalProfit::find($g_d->tran_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    else {
                        $deposit = \App\Models\GeneralJournal::find($g_d->journal_id);
                        $ref = optional($deposit)->reference_no;
                    }
                    $j_id=$g_d->journal_id;
                    $branch = \App\Models\Branch::find($branch_id);
                    $branch_acc_code = \App\Models\AccountChart::find(optional($branch)->cash_account_id);
                    //dd($j_id,$journal_id);
                ?>
                <tr>
                    <td style="white-space: nowrap;text-transform: capitalize">
                        @if ($j_id != $journal_id)
                            {{$g_d->tran_type}}
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        @if ($j_id != $journal_id)
                            {{$g_d->j_detail_date->format('Y-m-d')}}
                        @endif
                    </td>
                    <td style="text-align: center;white-space: nowrap;">
                        @if ($j_id != $journal_id)
                            {{$ref}}
                        @endif
                    </td>
                    <td style="white-space: nowrap;">

                        {{$ref}}</td>
                    <td style="white-space: nowrap;">

                    {{optional($branch)->code}} - {{optional($branch)->title}}</td>
                    <td style="white-space: nowrap;">

                        {{Auth()->user()->user_code}}
                    </td>
                    <td style="white-space: nowrap;">
                        {{optional($cus)->client_number}}
                    </td>
                    <td style="white-space: nowrap;">
                        {{optional($cus)->name_other}}
                    </td>
                    <td style="white-space: nowrap;">{{$g_d->external_acc_chart_code}}</td>
                    @if (isset($g_d->acc_chart_code))
                        <td style="white-space: nowrap;">{{$g_d->acc_chart_code}}</td>
                    @else
                        <td style="white-space: nowrap;">{{$branch_acc_code->code}}</td>
                    @endif
                    @if (isset($acc_chart_name->name))
                        <td>{{$acc_chart_name->name}}</td>
                    @else
                        <td style="white-space: nowrap;">{{optional($branch_acc_code)->name}}</td>
                    @endif
                    <td>{{ $g_d->description }}</td>
                    <td style="text-align: right">{{$g_d->dr>0?numb_format($g_d->dr,2):''}}</td>
                    <td style="text-align: right">{{$g_d->cr>0?numb_format($g_d->cr,2):''}}</td>
                </tr>
                @if ($j_id != $journal_id)
                    <?php
                    $journal_id=$j_id;
                    ?>
                @endif
                <?php
                $total_dr += $g_d->dr;
                $total_cr += $g_d->cr;
                ?>
            @endif


        @endforeach


    @endforeach

    <tr>
        <td colspan="12"><b>Total</b></td>
        <td style="text-align: right"><b>{{$total_dr>0?numb_format($total_dr,2):''}}</b></td>
        <td style="text-align: right"><b>{{$total_cr>0?numb_format($total_cr,2):''}}</b></td>
        <td></td>
        <td></td>
    </tr>

@endif