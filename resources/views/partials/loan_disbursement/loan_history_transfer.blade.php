<?php
$uid = time() . rand(1, 9999) . rand(1, 9999);
?>
<style>
    table {
        border-collapse: collapse;
    }

    .my-table tr td{
        border: 1px solid rgba(188, 188, 188, 0.96);
        padding: 5px;
    }

    .my-table tr td {
        font-size: 13px;
    }
</style>

@if($row != null)
    {{--{{dd($row)}}--}}

    <div class="clear-{{  $uid }}">
        <table width="100%" class="my-table">
            <?php
            $client = optional(\App\Models\Client::find($row->client_id));
            $group_loan = optional(\App\Models\GroupLoan::find($row->group_loan_id));
            $loan_transfer = \App\Models\LoanTransfer::where('loan_id',optional($row)->id)->first();
            $branch = optional(\App\Models\Branch::find($loan_transfer->old_branch));
            $center = optional(\App\Models\CenterLeader::find($loan_transfer->center_id));
            $user = optional(\App\User::find($loan_transfer->co_id));
            $loan_product = optional(\App\Models\LoanProduct::find($row->loan_production_id));
            $installment_amount = \App\Models\LoanCalculate::select('total_s')->where('disbursement_id', $row->id)
                    ->where('payment_status','pending')
                    ->where('date_p', NULL)
                    ->first();
            $principal_p = optional($row)->principle_repayment;
            $principal_out = optional($row)->loan_amount - $principal_p;
            $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $row->id)->sum('interest_s');
            $interest_p = optional($row)->interest_repayment;
            $interest_out = $total_interest - $interest_p;
            $total_outstanding = 0;
            if($interest_out < 0){
                    $interest_out = 0;
                }
            $total_outstanding = $principal_out + $interest_out;
            $total_line_charge = 0;
            $charges = \App\Models\LoanCharge::where('status','Yes')->where('loan_id',$row->id)->get();
            if($charges != null){
                foreach ($charges as $c){
                    $amt_charge = $c->amount;
                    $total_line_charge += ($c->charge_option == 1?$amt_charge:(($row->loan_amount*$amt_charge)/100));
                }
            }
            $total_compulsory = 0;
            $compulsory = \App\Models\LoanCompulsory::where('loan_id',$row->id)->where('status','Yes')->first();
            if($compulsory != null){
                $amt_compulsory = $compulsory->saving_amount;
                $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($row->loan_amount*$amt_compulsory)/100));
            }
            ?>
            <tr>
                <td colspan="2">Transfer Date</td>
                <td colspan="2">{{$loan_transfer->transfer_date?date("Y-m-d", strtotime($loan_transfer->transfer_date)):date("Y-m-d", strtotime($loan_transfer->updated_at))}}</td>
            </tr>
            <tr>
                <td>Loan Number</td>
                <td>{{$row->disbursement_number}}</td>
                <td>Service Charge</td>
                <td>{{$total_line_charge}}</td>
            </tr>
            <tr>
                <td>Client ID</td>
                <td>{{$client->client_number}}</td>
                <td>Compulsory Saving</td>
                <td>{{$total_compulsory}}</td>
            </tr>
            <tr>
                <td>Name Eng</td>
                <td>{{$client->name??$client->name_other}}</td>
                <td>First Installment Date</td>
                <td>{{$row->first_installment_date}}</td>
            </tr>
            <tr>
                <td>NRC Number</td>
                <td>{{$client->nrc_number}}</td>

                <td>Application Date</td>
                <td>{{$row->loan_application_date}}</td>

            </tr>
            <tr>
                <td>Group Loan</td>
                <td>{{$group_loan->group_code}}</td>
                <td>Disbursement Date</td>
                <td>{{$row->status_note_date_activated??"-"}}</td>
            </tr>
            <tr>
                <td>Branch</td>
                <td>{{$branch->title}}</td>
                <td>Loan Product</td>
                <td>{{$loan_product->name}}</td>
            </tr>
            <tr>
                <td>Center</td>
                <td>{{$center->title}}</td>
                <td>CO Name</td>
                <td>{{$user->name}}</td>
            </tr>
            <tr>
                <td>Interest</td>
                <td>{{$row->interest_rate}}</td>
                <td>Repayments Terms</td>
                <td>{{$row->repayment_term}}</td>
            </tr>
            <tr>
                <td>Terms Period</td>
                <td>{{$row->loan_term_value}}</td>
                <td>Loan Amount</td>
                <td>{{number_format($row->loan_amount,0)}}</td>
            </tr>
            <tr>
                <td>Installment Amount</td>
                <td>{{number_format($installment_amount->total_s??0,0)}}</td>
                <td>Principle Repay</td>
                <td>{{$row->principle_repayment}}</td>
            </tr>
            <tr>
                <td>Interest Repay</td>
                <td>{{$row->interest_repayment}}</td>
                <td>Principal Outstanding</td>
                <td>{{number_format($principal_out, 0)}}</td>
            </tr>
            <tr>
                <td>Interest Outstanding</td>
                <td>{{number_format($interest_out)}}</td>
                <td>Total Outstanding</td>
                <td>{{number_format($total_outstanding,0)}}</td>
            </tr>
            <tr>
                <td>Remark</td>
                <td>{{$row->remark}}</td>
                <td>Cycle</td>
                <td>{{$row->loan_cycle}}</td>
            </tr>
        </table>
    </div>
@endif





