<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>C.O Transaction Report</title>
</head>
<body>

    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Customer</th>
            <th>Loan Number</th>
            <th>Application Date</th>
            <th>Approved Date</th>
            <th>Loan Disburse</th>
            <th>Interest</th>
            <th>Term</th>
            <th>Principle Collection</th>
            <th>Interest Collection</th>
            <th>Service Fee</th>
            <th>Penalty Collection</th>
            <th>Total Collection</th>
        </tr>
        @foreach($reports as $key => $report)
            <tr>
                <td>{{ optional($report->client_name)->name }}</td>
                <td>{{ $report->disbursement_number }}</td>
                <td>{{ $report->loan_application_date }}</td>
                <td>{{ $report->status_note_date_approve }}</td>
                <td>{{ $report->loan_amount }}</td>
                <td>{{ $report->interest_rate }}</td>
                <td>{{ $report->repayment_term }}</td>
                <td>{{ optional($report->loan_schedule)->sum('principal_s') }}</td>
                <td>{{ optional($report->loan_schedule)->sum('interest_s') }}</td>
                <td>{{ optional($report->loan_schedule)->sum('service_charge_s') }}</td>
                <td>{{ optional($report->loan_schedule)->sum('penalty_s') }}</td>
                <td>{{ optional($report->loan_schedule)->sum('total_s') }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
