<div id="DivIdToPrint">
@if($customer != null)

    @include('partials.reports.header',
    ['report_name'=>'Customer List','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <tr>
                <th>No</th>
                <th>Company</th>
                <th>VAT No</th>
                <th>GST No</th>
                <th>E-mail</th>
                <th>Phone</th>
                <th>Address</th>
                <td>Balance</td>
            </tr>
        </thead>
        <tbody>
            @foreach($customer as  $row)
                <tr>
                <td style="width: 50px;">{{ $loop->index + 1 }}</td>
                <td style="min-width: 130px;">{{ $row->company??$row->name }}</td>
                <td style="width: 100px;">{{ $row->vat_no }}</td>
                <td style="width: 100px;">{{ $row->gst_number }}</td>
                <td style="width: 100px;">{{ $row->email }}</td>
                <td style="width: 100px;">{{ $row->phone }} {{ $row->mobile }}</td>
                <td>{{ $row->address }}</td>
                <td style="text-align: right;width: 100px;">
                    {{ $bals != null ? numb_format($bals->sum(function ($ar) use($row) { return $row->id== $ar->customer_id ?$ar->amount :0; })??0,2) :0 }}
                    {{ optional(\App\Models\Currency::find($row->currency_id))->currency_name }}
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
