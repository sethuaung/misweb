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
                  $referenc_no = $d->reference;
                  $saving = App\Models\Saving::find($d->saving_id);
                  $client = App\Models\Client::find($d->client_id);
                  $co_name = "";
                  $branch = App\Models\Branch::find($d->branch_id);
                  $center = "";
                  $date = $d->date;
                  $amount = $saving->total_withdraw;

              @endphp  
        @endif
                
    @endforeach

    <tr>
        
        <td style="white-space: nowrap;text-align: left">
            <b>{{$referenc_no}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$saving->saving_number}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->nrc_number}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->client_number}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->name}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($client)->name_other}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$co_name}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($branch)->title}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{optional($center)->title}}</b></td>   
        <td style="white-space: nowrap;text-align: left">
            <b>{{$date}}</b></td>
        <td style="white-space: nowrap;text-align: left">
            <b>{{$amount}}</b></td>                                 
            
    </tr>

</table>
@endif
