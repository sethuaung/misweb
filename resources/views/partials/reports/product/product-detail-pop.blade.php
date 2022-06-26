<div id="DivIdToPrintPop">
<style>
    table {
        border-collapse: collapse;
    }

    .border th, .border td {
        /*border: 1px solid rgba(188, 188, 188, 0.96);*/
        padding: 10px;
    }



    .right {
        text-align: right;
    }
</style>

<table width="100%">
    <tbody>
        <tr>
            <td>
                <h1>VH</h1>
            </td>
            <td style="text-align: center;">
                <h2>Product Detail Report</h2>
            </td>
        </tr>
    </tbody>
</table>
@if($row != null)
<table width="100%" style="margin-top: 15px">
    <tbody class="border">
    <tr>
        <td><b>Name</b></td>
        <td>{{$row->product_name}}</td>
        <td><b>Category</b></td>
        <td>{{optional($row->category)->title}}</td>
        <td rowspan="5" style="width: 100px;">
            <image style="max-width: 100px;" src="{{asset($row->image)}}" />
        </td>
    </tr>
    <tr>
        <td><b>SKU</b></td>
        <td>{{$row->sku}}</td>
        <td><b>UPC</b></td>
        <td>{{$row->upc}}</td>
    </tr>
    <tr>
        <td><b>Brand Mark</b></td>
        <td>{{optional($row->branch_mark)->title}}</td>
        <td><b>Reorder Point</b></td>
        <td>{{$row->reorder_point}}</td>
    </tr>
    <tr>
        <td><b>Warehouse Unit</b></td>
        <td>{{optional(\App\Models\Unit::find($row->unit_id))->title}}</td>
        <td><b>Purchase Unit</b></td>
        <td>{{optional(\App\Models\Unit::find($row->default_purchase_unit))->title}}</td>

    </tr>
    <tr>
        <td><b>Sale Unit</b></td>
        <td>{{optional(\App\Models\Unit::find($row->default_sale_unit))->title}}</td>
        <td><b>Tax Method</b></td>
        <td>{{$row->tax_method}}</td>
    </tr>

    <tr>
        <?php
            $taxes = $row->taxes;
        ?>
        <td><b>Tax Group</b></td>
        <td>
            @if($taxes != null)
                @foreach($taxes as $tax)
                    {{$tax->name}}<br>
                @endforeach
            @endif
        </td>
        <td><b>Currency</b></td>
        <td colspan="2">{{optional($row->currency)->currency_name}}</td>

    </tr>
    <tr>
        <td><b>Currency Sale</b></td>
        <td>{{optional($row->currency_sale)->currency_name}}</td>
        <td><b>Currency Purchase</b></td>
        <td colspan="2">{{optional($row->currency_purchase)->currency_name}}</td>
    </tr>
    <tr>
        <td><b>Description</b></td>
        <td colspan="4">{!! $row->description !!}</td>
    </tr>





    <tr>
        <td colspan="5"><b>Product Variant</b></td>
        <tbody class="border">
        <?php
            $variants = $row->unit_variant;
        ?>
        @if($variants != null)
            @foreach($variants as $v)
                <?php
                   $p = \App\Models\ProductUnitVariant::where('product_id',$row->id)->where('unit_id',$v->id)->first();
                ?>
                <tr>
                    <td><b>{{$v->title}}</b></td>
                    <td>{{optional($p)->qty}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </tr>
    </tbody>
</table>
@endif
</div>
