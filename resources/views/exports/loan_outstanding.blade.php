@if($rows != null)
<table>
    <tr>
        @foreach($rows as $v)
            <th>{{$v}}</th>
        @endforeach
    </tr>

    <?php
        $key = '';
        $total_loan_amount=0;
        $total_total_interest=0;
        $total_installment_amount=0;
        $total_principle_repay=0;
        $total_interest_repay=0;
        $total_principle_out=0;

    ?>
    @foreach($data as $d)

        @if($d != null)

            <?php
                $key = $d->transaction_number;
                $last_repayment = $d->last_repayment_date;
                $installment_amount= $d->installment_amount;
                $total_outstanding = $d->loan_amount;
                $principal_out = $d->principle_outstanding;


                $total_loan_amount+=$d->loan_amount;
                $total_total_interest+= $d->total_interest;
                $total_installment_amount+= $d->installment_amount>0? $d->installment_amount : 0;
                $total_principle_repay+=$d->principle_repayment;
                $total_interest_repay+=$d->interest_repayment;
                $total_principle_out+=$principal_out;
            ?>

            <tr>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->disburse_date}}
                </td>
                <td style="white-space: nowrap;text-align: left">
                    {{$last_repayment}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->client_id}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{$d->loan_number}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->name}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->name_other}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->nrc_number}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->branch}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->center}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->co_name}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{optional($d)->loan_type}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{number_format($d->loan_amount,0)}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{$d->total_interest}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{$installment_amount}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{number_format($d->principle_repay,0)}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{number_format($d->interest_repay,0)}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{number_format($principal_out,0)}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{number_format($d->interest_outstanding,0)}}
                </td>

                <td style="white-space: nowrap;text-align: left">
                    {{$principal_out + $d->interest_outstanding}}
                </td>

            </tr>

        @endif
    @endforeach

    <tr>
        <td colspan="10" style="text-align: right"><b>Total:</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$total_loan_amount>0?numb_format($total_loan_amount,2):''}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$total_total_interest>0?numb_format($total_total_interest,2):''}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$total_installment_amount>0?numb_format($total_installment_amount,2):''}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$total_principle_repay>0?numb_format($total_principle_repay,2):''}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$total_interest_repay>0?numb_format($total_interest_repay,2):''}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$total_principle_out>0?numb_format($total_principle_out,2):''}}</b></td>
        <td></td>
        <td></td>
    </tr>

</table>
    <?php
    \App\Models\LoanOustandingTem::where('transaction_number',$key)->delete();
    ?>
@endif
