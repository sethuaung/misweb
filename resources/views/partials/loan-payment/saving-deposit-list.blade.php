<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saving Deposit Report</title>
</head>
<body>

    <table class="table" cellspacing="0" cellpadding="5px">
        <tr>
            <th>Reference No</th>
            <th>Account No</th>
            <th>NRC Number</th>
            <th>Name (Eng)</th>
            <th>Name (MM)</th>
            <th>CO Name</th>
            <th>Branches</th>
            <th>Center</th>
            <th>Date</th>
            <th>Deposit Amount</th>
        </tr>
        @php
            //dd($reports);
        @endphp
        @foreach($reports as $key => $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ optional($report->loan)->disbursement_number }}</td>
                <td>{{ optional(optional($report->loan)->client_name)->nrc_number }}</td>
                <td>{{ optional(optional($report->loan)->client_name)->name }}</td>
                <td>{{ optional(optional($report->loan)->client_name)->name_other }}</td>
                <td>{{ optional(optional($report->loan)->officer_name)->name }}</td>
                <td>{{ optional(optional($report->loan)->branch_name)->title }}</td>
                <td>{{ optional(optional($report->loan)->center_leader_name)->title }}</td>
                <td>{{ $report->tran_date }}</td>
                <td>{{ $report->amount }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
