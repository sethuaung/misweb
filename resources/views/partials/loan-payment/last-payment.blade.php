<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excel</title>
</head>
<body>
    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Payment Date</th>
            <th>loan Number</th>
            <th>Principle</th>
            <th>Interest</th>
            <th>Service</th>
            <th>Penalty</th>
            <th>Saving</th>
            <th>Payment</th>
            <th style="width: 80px">Action</th>
        </tr>
        @if($payment != null)
            <?php
                $loan = \App\Models\Loan2::find($payment->disbursement_id);
                $service = \App\Models\PaymentCharge::where('payment_id',$payment->id)->sum('charge_amount');
            ?>
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                <td>{{ optional($loan)->disbursement_number}}</td>
                <td>{{ $payment->principle }}</td>
                <td>{{ $payment->interest }}</td>
                <td>{{ $service }}</td>
                <td>{{ $payment->penalty_amount }}</td>
                <td>{{ $payment->compulsory_saving }}</td>
                <td>{{ $payment->payment }}</td>
                <td style="width: 80px"><a href="{{url("/api/delete-payment?payment_id={$payment->id}")}}"  class="btn btn-xs btn-danger">Roll Back</a></td>
            </tr>
        @endif
    </table>

</body>
</html>
