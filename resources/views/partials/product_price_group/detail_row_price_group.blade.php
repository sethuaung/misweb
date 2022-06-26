<?php
    $u_variants = $entry->unit_variant;
    $id = $entry->id;
    $price_groups = \App\Models\PriceGroup::all();
?>
<table class="table">
@if($price_groups != null)
    <thead>
        <tr>
            <th width="150">PriceGroup</th>
            @forelse ($u_variants as $unit)
                <th>{{$unit->title}}</th>
            @empty
                <th></th>
            @endforelse
        </tr>
    </thead>
    <tbody>
        @foreach($price_groups as $pg)
            <tr>
                <td>{{$pg->name}}</td>
                @forelse ($u_variants as $unit)
                    <?php
                        $m = \App\Models\ProductPriceGroup::where('product_id',$id)->where('unit_id',$unit->id)->where('price_group_id',$pg->id)->first();
                    ?>
                   <td><input value="{{optional($m)->price}}" type="text" data-product_id="{{$id}}" data-unit="{{$unit->id}}" data-pg="{{$pg->id}}" class="form-control unit-price"></td>
                @empty
                    <?php
                    $m = \App\Models\ProductPriceGroup::where('product_id',$id)->where('unit_id',0)->where('price_group_id',$pg->id)->first();
                    ?>
                    <td><input value="{{optional($m)->price}}" type="text"  data-product_id="{{$id}}" data-unit="0" data-pg="{{$pg->id}}" class="form-control unit-price"></td>
                @endforelse
            </tr>
        @endforeach
    </tbody>
@endif
</table>
