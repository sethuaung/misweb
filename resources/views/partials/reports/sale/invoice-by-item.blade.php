<div id="DivIdToPrint">
@if($sale_orders != null)
<?php
$products = $sale_orders->groupBy('product_id');
?>
    
    @include('partials.reports.header',
    ['report_name'=>'Invoice By Item','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <tr>
                <th>No</th>
                <th>Inv Number</th>
                <th>Date</th>
                <th>Class</th>
                {{--<th>Item</th>--}}
                <th>QTY</th>
                <th>Unit</th>
                <th>Cost</th>
                <th>Dis.</th>
                <th>Tax</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Order Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $pro_id => $rows)
                <?php
                $prods = optional(\App\Models\Product::find($pro_id));

                $cat_id = $prods->category_id;
                $cat = optional(\App\Models\ProductCategory::find($cat_id??0));
                ?>
                <tr>
                    <td colspan="13"><b>{{ $prods->product_name }} &gt;&gt; {{ $cat->title }}</b></td>
                </tr>
                @foreach($rows as $rd)
                    <tr>
                        <td>{{ $loop->index +1 }}</td>
                        <td style="padding-left: 30px;">{{ $rd->invoice_number }}</td>
                        <td style="width: 100px">{{ $rd->po_date }}</td>
                        <td>{{ optional(\App\Models\AccClass::find($rd->class_id))->name}}</td>
                        {{--<td>{{ optional(\App\Models\Product::find($rd->product_id))->product_name }}</td>--}}
                        <td style="width: 50px;text-align: right">{{ $rd->line_qty }}</td>
                        <td style="width: 50px;text-align: center">{{ optional(\App\Models\Unit::find($rd->line_unit_id))->title }}</td>
                        <td style="width: 70px;text-align: right">{{ numb_format($rd->unit_cost??0,2) }}</td>
                        <td style="width: 70px;text-align: right">{{ numb_format($rd->line_discount_amount??0,2) }}</td>
                        <td style="width: 70px;text-align: right">{{ numb_format($rd->line_tax_amount??0,2) }}</td>
                        <td style="width: 100px;text-align: right">{{ numb_format($rd->line_amount??0,2) }}</td>
                        <td style="text-align: center;">{{ optional(\App\Models\Currency::find($rd->currency_id))->currency_name}}</td>
                        <td style="padding-left: 30px;">{{ $rd->order_number }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
