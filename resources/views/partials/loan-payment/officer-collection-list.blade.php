<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>C.O Collection Report</title>
</head>
<body>

    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Customer</th>
            <th>Loan Number</th>
            <th>Date</th>
            <th>Principle Collection</th>
            <th>Interest Collection</th>
            <th>Service Fee</th>
            <th>Penalty Collection</th>
            <th>Total Collection</th>
        </tr>
        @foreach($reports as $key => $report)
            <tr>
                <td>{{ optional($report->client_name)->name }}</td>
                <td>{{ optional($report->loan_disbursement)->disbursement_number }}</td>
                <td>{{ $report->payment_date }}</td>
                <td>{{ $report->principle }}</td>
                <td>{{ $report->interest }}</td>
                <td>{{ $report->other_payment }}</td>
                <td>{{ $report->penalty_amount }}</td>
                <td>{{ $report->total_payment }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
