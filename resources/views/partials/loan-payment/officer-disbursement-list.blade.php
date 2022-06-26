<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Disbursement By C.O Report</title>
</head>
<body>

    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Loan Reference</th>
            <th>Customer</th>
            <th>Disburse Date</th>
            <th>First Payment Date</th>
            <th>Interest</th>
            <th>Payment Terms</th>
            <th>Disburse Amount</th>
            <th>Officer</th>
        </tr>
        @foreach($reports as $key => $report)
            <tr>
                <td>{{ optional($report->disbursement)->disbursement_number }}</td>
                <td>{{ optional(optional($report->disbursement)->client_name)->name }}</td>
                <td>{{ $report->paid_disbursement_date }}</td>
                <td>{{ optional($report->disbursement)->first_installment_date }}</td>
                <td>{{ optional($report->disbursement)->interest_rate }}</td>
                <td>{{ optional($report->disbursement)->repayment_term }}</td>
                <td>{{ $report->total_money_disburse }}</td>
                <td>{{ optional(optional($report->disbursement)->officer_name)->name }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
