
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
            <th>{{_t('Loan Request')}}</th>
            <th>{{_t('Service Amount')}}</th>
            <th>{{_t('Saving Balance')}}</th>
            <th>{{_t('Disbursement Amount')}}</th>

        </tr>
        </thead>
        <tbody>
        @if($rows != null)
            @foreach($rows as $row)
                <tr>
                    <td>{{\Carbon\Carbon::parse($row->loan_application_date)->format('Y/m/d')}}</td>
                    <td>{{$row->loan_term}}</td>
                    <td>{{$row->disbursement_number}}</td>
                    <td>{{optional($row->loan_product)->code}}</td>
                    <td>{{optional($row->client_name)->name}}</td>
                    <td>{{optional($row->branch_name)->title}}</td>
                    <td></td>
                    <td>{{optional($row->officer_name)->name}}</td>
                    <td>{{$row->loan_amount}}</td>
                    <td>{{$row->principle}}</td>
                    <td>{{optional(optional($row->loan_product)->compulsory_product)->saving_amount}}</td>
                    <td></td>

                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@else
    <h1 style="text-align: center;font-weight: bold">Data Not Fund</h1>
@endif