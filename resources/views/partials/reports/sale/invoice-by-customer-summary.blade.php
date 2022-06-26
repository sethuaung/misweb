<div id="DivIdToPrint">
@if($sale_orders != null)
<?php
$customer = $sale_orders->groupBy('customer_id');
?>
    @include('partials.reports.header',
    ['report_name'=>'Invoice by Customer Summary','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
        <tr>
            <td>No</td>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total_amount  = [];
        @endphp
        @foreach($customer as $sup => $rows)
            <?php
            $supp = optional(\App\Models\Customer::find($sup));
            $amt = $rows->groupBy('currency_id')->map(function ($row) {
                return $row->sum('grand_total');
            });
            // dd($amt);
            ?>
            <tr>
                <td style="width: 50px">{{ $loop->index +1 }}</td>
                <td>{{ $supp->company??$supp->name }}</td>
                <td>{{ $supp->phone }}</td>
                <td>{{ $supp->address }}</td>
                <td style="width: 200px;text-align: right">
                    @if($amt != null)
                        @foreach($amt as $c => $a)
                            <b>{{ numb_format( $a??0,2) }} {{ optional(\App\Models\Currency::find($c??0))->currency_name}}</b>
                            @php
                                $total_amount[$c??0] = (isset($total_amount[$c??0])?$total_amount[$c??0]:0) + ($a??0);
                            @endphp
                        @endforeach
                    @endif
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" style="text-align: right;">Total</td>
            <td style="text-align: right;">
                @if($total_amount != null)
                    @foreach($total_amount as $c => $a)
                        <b>{{ numb_format( $a??0,2) }} {{ optional(\App\Models\Currency::find($c))->currency_name}}</b>
                    @endforeach
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    @else
<h1>No data</h1>
@endif
</div>
