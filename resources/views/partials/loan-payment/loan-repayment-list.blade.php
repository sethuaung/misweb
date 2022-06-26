<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excel</title>
</head>
<body>

    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Date</th>
            <th>Loan Term</th>
            <th>Payment Ref</th>
            <th>Loan ID</th>
            <th>Customer</th>
            <th>Branches</th>
            <th>Center</th>
            <th>By CO</th>
            <th>Due Days</th>
            <th>Owed</th>
            <th>Principal</th>
            <th>Interest</th>
            <th>Saving</th>
            <th>Penalty</th>
            <th>Service</th>
            <th>Total Amount</th>
            <th>New Owed</th>
            <th>Paid By</th>
        </tr>
        @foreach($reports as $key => $report)
            <tr>
                <td>{{ $report->payment_date }}</td>
                <td>{{ optional($report->loan_disbursement)->repayment_term }}</td>
                <td>{{ $report->payment_number }}</td>
                <td>{{ optional($report->loan_disbursement)->disbursement_number }}</td>
                <td>{{ optional(optional($report->loan_disbursement)->client_name)->name }}</td>
                <td>{{ optional(optional($report->loan_disbursement)->branch_name)->title }}</td>
                <td>{{ optional(optional($report->loan_disbursement)->center_leader_name)->title }}</td>
                <td>{{ optional(optional($report->loan_disbursement)->officer_name)->name }}</td>
                <td>{{ $report->over_days }}</td>
                <td>{{ $report->old_owed }}</td>
                <td>{{ $report->principle }}</td>
                <td>{{ $report->interest }}</td>
                <td>{{ $report->compulsory_saving }}</td>
                <td>{{ $report->penalty_amount }}</td>
                <td>{{ $report->other_payment }}</td>
                <td>{{ $report->total_payment }}</td>
                <td>{{ $report->owed_balance }}</td>
                <td>{{ $report->payment_method }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
