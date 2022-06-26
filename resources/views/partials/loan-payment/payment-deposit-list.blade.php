<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excel</title>
</head>
<body>

    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Reference No</th>
            <th>Account No</th>
            <th>NRC No</th>
            <th>Name (Eng)</th>
            <th>Name (MM)</th>
            <th>CO Name</th>
            <th>Branches</th>
            <th>Center</th>
            <th>Deposit Date</th>
            <th>Service Pay</th>
            <th>Saving Pay</th>
            <th>Deposit Amount</th>
        </tr>
        @foreach($reports as $key => $report)
            <tr>
                <td>{{ $report->referent_no }}</td>
                <td>{{ optional($report->loan_disbursement)->disbursement_number }}</td>
                <td>{{ optional($report->client)->nrc_number }}</td>
                <td>{{ optional($report->client)->name }}</td>
                <td>{{ optional($report->client)->name_other }}</td>
                <td>{{ optional(optional($report->loan_disbursement)->officer_name)->name }}</td>
                <td>{{ optional(optional($report->loan_disbursement)->branch_name)->title }}</td>
                <td>{{ optional(optional($report->loan_disbursement)->center_leader_name)->title }}</td>
                <td>{{ $report->loan_deposit_date }}</td>
                <td>{{ $report->client_pay }}</td>
                <td>{{ $report->compulsory_saving_amount }}</td>
                <td>{{ $report->total_deposit }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
