

<?php
   $rand_id = rand(1,1000).time().rand(1,1000);
    if(isset($r)){
        $row = $r->product;
    }else{
        $r = null;
    }
?>
<tr class="product_id" id="p-{{$rand_id}}">
    <td id="product">
        <input type="hidden" name="product_id[{{$rand_id}}]" class="product_id" value="{{$row->id}}">
        {{$row->product_name}}
    </td>
    <td>
        <input type="number" class="form-control qty" value="{{optional($r)->qty}}"  name="qty[{{$rand_id}}]" style="width:100%">

    </td>
    <td>
        <select data-id="{{$rand_id}}" name="line_unit_id[{{$rand_id}}]" class="form-control select line_unit_id" title="{{_t('Product Unit')}}">
            {!!  \App\Models\Unit::getUnit(optional($r)->line_unit_id,$row->id)!!}
        </select>
    </td>
    <td>
        <button class="btn btn-danger btn-xs remove-product" type="button">Remove</button>
    </td>
</tr>