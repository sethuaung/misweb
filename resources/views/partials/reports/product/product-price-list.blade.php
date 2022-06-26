<div id="DivIdToPrint">
@if($rows != null)
<?php
$categories = $rows->groupBy('category_id');
$priceGroups = \App\Models\PriceGroup::all();
?>
    @if($priceGroups != null)
    @include('partials.reports.header',
    ['report_name'=>'Product Price List','from_date'=>$start_date,'to_date'=>$end_date,'use_date'=>1])

    <table class="table-data" id="table-data">

        <thead>
            <tr>
                <th>Product</th>
                <?php $c_p_g = count($priceGroups) + 1; ?>
                @foreach($priceGroups as $priceG)
                    <th>{{ $priceG->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>

        @foreach($categories as $cat => $products)

            <tr>
                <td colspan="{{ $c_p_g }}"  style="color: blue;"><b>{{ optional(\App\Models\ProductCategory::find($cat))->title }}</b></td>
            </tr>
            @foreach($products as $product)
                <?php
                $u_variants = $product->unit_variant;
                ?>
                <tr>
                    <td style="padding-left: 40px;">{{$product->product_name}}</td>
                    @foreach($priceGroups as $pg)
                        <td style="text-align: right">

                                    @forelse ($u_variants as $unit)
                                        <?php

                                        $m = \App\Models\ProductPriceGroup::where('product_id',$product->id)->where('unit_id',$unit->id)->where('price_group_id',$pg->id)->first();
                                        ?>
                                        <span>{{$unit->title}}  = {{optional($m)->price}} </span> <br>
                                    @empty

                                        <?php
                                        $m = \App\Models\ProductPriceGroup::where('product_id',$product->id)->where('unit_id',0)->where('price_group_id',$pg->id)->first();
                                        ?>
                                        <span>{{optional($m)->price}}</span>
                                    @endforelse


                        </td>
                    @endforeach

                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
    @endif
@else
<h1>No data</h1>
@endif
</div>
