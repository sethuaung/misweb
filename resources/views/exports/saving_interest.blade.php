@php
    //dd($data);
@endphp
@if($rows != null)
<table>
    <tr>
        @foreach($rows as $v)
            <th>{{$v}}</th>
        @endforeach
    </tr>

    <?php
        $total_loan_amount=0;
        $total_total_interest=0;
        $total_installment_amount=0;
        $total_principle_repay=0;
        $total_interest_repay=0;
        $total_principle_out=0;

    ?>
    @foreach($data as $d)

        @if ($d != Null)
              @php
                  $loan_number = App\Models\Loan::find($d->loan_id);
                  $loan_compulsory = App\Models\LoanCompulsory::find($d->loan_compulsory_id);
                  $branch = App\Models\Branch::find($loan_compulsory->branch_id);
                  $reference_no = App\Models\CompulsorySavingTransaction::where('loan_compulsory_id',$d->loan_compulsory_id)->first();
                  $client = App\Models\Client::find($reference_no->customer_id);
                  $co_name = App\User::find($loan_number->loan_officer_id);
                  $center = App\Models\CenterLeader::find($loan_number->center_leader_id);
                  $date = $reference_no->tran_date;
                  $interest = $reference_no->total_principle;
                  $acrued = $reference_no->amount;
                  $deposit = App\Models\LoanCompulsory::find($d->loan_compulsory_id)->saving_amount;
                  $saving_principle = App\Models\LoanCompulsory::find($d->loan_compulsory_id)->principles;

              @endphp  
        @endif
                
    @endforeach

    <tr>
        
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($reference_no)->id}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($loan_number)->disbursement_number}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->client_number}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->nrc_number}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->name}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->name_other}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($co_name)->name}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($branch)->title}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($center)->title}}</b></td>   
        <td style="white-space: nowrap;text-align: left">
            <b>{{$date}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$interest}}</b></td>  
        <td style="white-space: nowrap;text-align: left">
            <b>{{$acrued}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$deposit}}</b></td>  
        <td style="white-space: nowrap;text-align: left">
            <b>{{$saving_principle}}</b></td>                                 
            
    </tr>

</table>
@endif
