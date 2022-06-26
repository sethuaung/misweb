<div id="DivIdToPrint">
@if($rows != null)
<?php
$categories = $rows->groupBy('category_id');
?>
    @include('partials.reports.header',
    ['report_name'=>'Product-List','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">

        <thead>
            <tr>
                <th>Product</th>
                @foreach($warehouse_id as $wh_id)
                <th><b style="color: red;">{{ optional(\App\Models\Warehouse::find($wh_id))->name ?? 'N/A' }}</b></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
                @foreach($categories as $cat => $products)
                    <tr>
                        <td colspan="{{ count($warehouse_id) +1 }}"  style="color: blue;"><b>{{ optional(\App\Models\ProductCategory::find($cat))->title }}</b></td>
                    </tr>
                    @foreach($products as $product)

                        <tr>
                            <td style="padding-left: 40px;">{{$product->product_name}}</td>
                            @foreach($warehouse_id as $wh_id)
                                <?php
                                $sm = isset($stocks[$wh_id][$product->id]) ? $stocks[$wh_id][$product->id] : [];
                                ?>
                            <td style="text-align: right;">
                                @if(is_array($sm))
                                    @if(count($sm)>0)
                                        @foreach($sm as $k => $qty)
                                            <b class="{{ $qty>0?'text-success':'text-danger' }}"> {{ convertUnit($product->id,$qty) }}  </b> <br>
                                        @endforeach
                                    @endif
                                @endif
                            </td>
                            @endforeach

                        </tr>
                    @endforeach
                @endforeach
        </tbody>
    </table>

@else
<h1>No data</h1>
@endif
</div>
