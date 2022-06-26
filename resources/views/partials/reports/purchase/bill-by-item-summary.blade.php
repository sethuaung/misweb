<div id="DivIdToPrint">
@if($purchase_orders != null)
<?php
$products = $purchase_orders->groupBy('product_id');
?>
    @include('partials.reports.header',
    ['report_name'=>'Bill By Item Summary','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>QTY</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
               $total_amount = [];
            @endphp
            @foreach($products as $pro_id => $rows)
                <?php
                $prods = optional(\App\Models\Product::find($pro_id));

                $cat_id = $prods->category_id;
                $cat = optional(\App\Models\ProductCategory::find($cat_id??0));

               // $rd = $rows[0];

                $qty = $rows->groupBy('line_unit_id')->map(function ($row) {
                    return $row->sum('line_qty');
                });

                $amt = $rows->groupBy('currency_id')->map(function ($row) {
                    return $row->sum('line_amount');
                });

               // dd($qty,$amt);
                ?>
                <tr>
                    <td style="width: 50px;">{{ $loop->index +1 }}</td>
                    <td><b>{{ $prods->product_name }} &gt;&gt; {{ $cat->title }}</b></td>


                    <td style="width: 100px;text-align: right">
                        @if($qty != null)
                            @foreach($qty as $u => $q)
                                <b style="display: block">{{ $q }} {{ optional(\App\Models\Unit::find($u))->title }} </b>
                            @endforeach
                        @endif

                    </td>
                    <td style="width: 200px;text-align: right">
                        @if($amt != null)
                            @foreach($amt as $c => $a)
                                <b>{{ numb_format( $a??0,2) }} {{ optional(\App\Models\Currency::find($c))->currency_name}}</b>
                                @php
                                    $total_amount[$c] = (isset($total_amount[$c])?$total_amount[$c]:0) + ($a??0);
                                @endphp
                            @endforeach
                        @endif

                    </td>
                </tr>

            @endforeach
                <tr>
                    <td colspan="3" style="text-align: right;">Total</td>
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
