
@if(count($rows)>0)
    <table class="table table-bordered" style="width: 100%">
        <thead>
        <tr  style="text-align: center;background:#03a9f4;color: white">
            <th>{{_t('Date')}}</th>
            <th>{{_t('Loan Term')}}</th>
            <th>{{_t('Payment Ref')}}</th>
            <th>{{_t('Loan ID')}}</th>
            <th>{{_t('Customer')}}</th>
            <th>{{_t('Branch')}}</th>
            <th>{{_t('Center')}}</th>
            <th>{{_t('By C.O')}}</th>
            <th>{{_t('Due Days')}}</th>
            <th>{{_t('Owed')}}</th>
            <th>{{_t('Principle')}}</th>
            <th>{{_t('Interest')}}</th>
            <th>{{_t('Penalty')}}</th>
            <th>{{_t('Service')}}</th>
            <th>{{_t('Total Amount')}}</th>
            <th>{{_t('New Owed')}}</th>
            <th>{{_t('Paid By')}}</th>
        </tr>
        </thead>
        <tbody>
        @if($rows != null)
            @foreach($rows as $row)
                <tr>
                    <td>{{\Carbon\Carbon::parse($row->payment_dat)->format('Y/m/d')}}</td>
                    <td>{{optional($row->loan_disbursement)->loan_term}}</td>
                    <td>{{$row->receipt_no}}</td>
                    <td>{{optional($row->loan_disbursement)->disbursement_number}}</td>
                    <td>{{optional($row->client_name)->name}}</td>
                    <td>{{optional(optional($row->loan_disbursement)->branch_name)->title}}</td>
                    <td></td>
                    <td>{{optional($row->credit_officer)->name}}</td>
                    <td></td>
                    <td>{{$row->old_owed}}</td>
                    <td>{{$row->principle}}</td>
                    <td>{{$row->interest}}</td>
                    <td>{{$row->penalty_amount}}</td>
                    <td></td>
                    <td>{{$row->payment}}</td>
                    <td>{{$row->owed_balance}}</td>
                    <td>{{$row->payment_method}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@else
    <h1 style="text-align: center;font-weight: bold">Data Not Fund</h1>
@endif