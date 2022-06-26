<div class="#">
    {{-- <div class="">
        test
    </div> --}}
    @if($rows != null)
        @php
        $i = 0;
        @endphp
        <table class="table-data" id="table-data">
            <thead>
                <tr>
                    <th>Loan Number</th>
                    <th>Client Name</th>
                    <th>NRC</th>
                    <th>Phone</th>
                    <th>Due Date</th>
                    <th>Branch</th>
                    <th>Center</th>
                    <th>CO Name</th>
                    <th>Installment Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                   <tr>
                       <td>{{ $row->disbursement_number }}</td>
                       <td>{{ optional($row->client_name)->name }}</td>
                       <td>{{ optional($row->client_name)->nrc_number }}</td>
                       <td>{{ optional($row->client_name)->primary_phone_number.', '.optional($row->client_name)->alternate_phone_number }}</td>
                       <td>@if (!$row->loan_schedule->isEmpty()) {{ $row->loan_schedule[0]->date_s }} @endif</td>
                       <td>{{ optional($row->branch_name)->title }}</td>
                       <td>{{ optional($row->center_leader_name)->title }}</td>
                       <td>{{ optional($row->officer_name)->name }}</td>
                       <td>@if (!$row->loan_schedule->isEmpty()) {{ $row->loan_schedule[0]->principal_s }} @endif</td>
                       <td>{{ $row->id }}</td>
                   </tr>
                @endforeach
            </tbody>
        </table>
        {{-- {{$rows->links()}} --}}
    @endif
</div>
