<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excel</title>
</head>
<body>

<table class="table" cellspacing="0" cellpadding="5px">
    <tr>
        <th>Client Number</th>
        <th>NRC No</th>
        <th>Name (Eng)</th>
        <th>Name (MM)</th>
        <th>Branches</th>
        <th>Center</th>
        <th>Phone No</th>
        <th>Register Date</th>
    </tr>
    @foreach($reports as $key => $report)
        <tr>
            <td>{{ $report->client_number }}</td>
            <td>{{ $report->nrc_number }}</td>
            <td>{{ $report->name }}</td>
            <td>{{ $report->name_other }}</td>
            <td>{{ optional($report->branch_name)->title }}</td>
            <td>{{ optional($report->center_leader)->title }}</td>
            <td>{{ $report->primary_phone_number }}</td>
            <td>{{ $report->created_at }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>