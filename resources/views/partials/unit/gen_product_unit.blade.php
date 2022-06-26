@php
$product_id = isset($product_id)?$product_id:null;
@endphp
@if(isset($units))

   {{-- @foreach($warehouses as $warehouse)--}}
        {{--<tr>
            <td colspan="5"><span style="color: red;">{{$warehouse->name}}</span></td>
        </tr>--}}
        @foreach($units as $unit)
            <?php
                $rand_id = rand(1,1000).time().rand(1,1000);


            $u_val = \App\Models\ProductUnitVariant::where('product_id',$product_id)
                ->where('unit_id',$unit->id)
                //->orderBy('id','DESC')
                ->first();


            ?>
            <tr id="id-{{$rand_id}}">
                <td style="padding-left: 20px;"><span style="color: blue;">{{$unit->title}}</span></td>
                <td>
                    <input number="number" value="{{optional($u_val)->qty}}"   class="form-control " name="unit_qty[{{ $unit->id }}]">
                    <input type="hidden" value="{{$unit->id}}"   class="form-control " name="unit_variant_id[{{ $unit->id }}]">
                </td>
                <td>
                    <input value="{{optional($u_val)->code}}"   class="form-control package-code" name="code[{{ $unit->id }}]">
                </td>
                <td style="display: none;">
                    <input number="number" value="{{optional($u_val)->length}}"   class="form-control cbm-l" name="length[{{ $unit->id }}]">
                </td>
                <td style="display: none;">
                    <input number="number" value="{{optional($u_val)->width}}"   class="form-control cbm-wi" name="width[{{ $unit->id }}]">
                </td>
                <td style="display: none;">
                    <input number="number" value="{{optional($u_val)->height}}"   class="form-control cbm-h" name="height[{{ $unit->id }}]">
                </td>

                <td style="display: none;">
                    <input type="text" value="{{optional($u_val)->bcm}}"   class="form-control bcm" name="bcm[{{ $unit->id }}]" readonly="readonly">
                </td>
                <td style="display: none;">
                    <input number="number" value="{{optional($u_val)->weight}}"   class="form-control cbm-we" name="weight[{{ $unit->id }}]">
                </td>
                <td>
                    <i class="fa fa-times tip remove-product-line" title="Remove" style="cursor:pointer;"></i>
                </td>
            </tr>
        @endforeach
    {{--@endforeach--}}

@endif
