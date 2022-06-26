<div id="DivIdToPrint">
@if($purchase_orders != null)
<?php
$supplier = $purchase_orders->groupBy('supplier_id');
?>
    @include('partials.reports.header',
    ['report_name'=>'Purchase Orders Detail','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <tr>
                <th>Order Number</th>
                <th>Class</th>
                <th>Description</th>
                <th>Total Qty</th>
                <th>Grand Total</th>
                <th>Currency</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplier as $sup => $rows)
                <?php
                $supp = optional(\App\Models\Supply::find($sup));
                ?>
                <tr>
                    <td colspan="6"><b>{{ $supp->company??$supp->name }} &gt;&gt; {{ $supp->phone }} &gt;&gt; {{ $supp->address }}</b></td>
                </tr>
                @foreach($rows as $row)
                    <tr>
                        <td style="padding-left: 30px;">{{ $row->order_number }}</td>
                        <td>{{ optional(\App\Models\AccClass::find($row->class_id))->name}}</td>
                        <td>{{ $row->description }}</td>
                        <td style="text-align: right;">{{ numb_format($row->total_qty??0,2) }}</td>
                        <td style="text-align: right;">{{ numb_format($row->grand_total??0,2) }}</td>
                        <td style="text-align: center;">{{ optional(\App\Models\Currency::find($row->currency_id))->currency_name}}</td>
                    </tr>
                    @php
                    $purchase_details = $row->purchase_details;
                    @endphp
                    @if($purchase_details != null)
                        <tr>
                            <td colspan="6" style="padding-left: 50px;">
                                <table style="width: 100%;">
                                    <tr style="color: #919191;">
                                        <th>No</th>
                                        <th>Item</th>
                                        <th>QTY</th>
                                        <th>Unit</th>
                                        <th>Cost</th>
                                        <th>Dis.</th>
                                        <th>Tax</th>
                                        <th>Amount</th>
                                    </tr>
                                    @foreach($purchase_details as $rd)
                                    <tr style="background-color: white;">
                                        <td style="width: 50px">{{ $loop->index + 1  }}</td>
                                        <td>{{ optional(\App\Models\Product::find($rd->product_id))->product_name }}</td>
                                        <td style="width: 50px;text-align: right">{{ $rd->line_qty }}</td>
                                        <td style="width: 50px;text-align: center">{{ optional(\App\Models\Unit::find($rd->line_unit_id))->title }}</td>
                                        <td style="width: 100px;text-align: right">{{ numb_format($rd->unit_cost??0,2) }}</td>
                                        <td style="width: 100px;text-align: right">{{ numb_format($rd->line_discount_amount??0,2) }}</td>
                                        <td style="width: 100px;text-align: right">{{ numb_format($rd->line_tax_amount??0,2) }}</td>
                                        <td style="width: 100px;text-align: right">{{ numb_format($rd->line_amount??0,2) }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
