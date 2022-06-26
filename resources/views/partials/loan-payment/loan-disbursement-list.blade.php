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
            <th>Payment Ref</th>
            <th>Loan ID</th>
            <th>Customer</th>
            <th>Branches</th>
            <th>Center</th>
            <th>By CO</th>
            <th>Loan Request</th>
            <th>Service Amount</th>
            <th>Saving</th>
            <th>Disbursement Amount</th>
        </tr>
        @foreach($reports as $key => $report)
            <tr>
                <td>{{ $report->paid_disbursement_date }}</td>
                <td>{{ $report->reference }}</td>
                <td>{{ optional($report->disbursement)->disbursement_number }}</td>
                <td>{{ optional(optional($report->disbursement)->client_name)->name }}</td>
                <td>{{ optional(optional($report->disbursement)->branch_name)->title }}</td>
                <td>{{ optional(optional($report->disbursement)->center_leader_name)->title }}</td>
                <td>{{ optional(optional($report->disbursement)->officer_name)->name }}</td>
                <td>{{ $report->loan_amount }}</td>
                <td>{{ $report->loan_process_fee }}</td>
                <td>{{ $report->compulsory_saving }}</td>
                <td>{{ $report->total_money_disburse }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
