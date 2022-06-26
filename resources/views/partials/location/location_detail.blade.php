<?php
$rand_id = rand(1,1000).time().rand(1,1000);
//if edit ===============================
//=======================================
$r = isset($r)?$r:null;

$location_id = isset($location_id)?$location_id: optional($r)->id;
//=======================================
//=======================================
?>
<tr id="id-{{ $rand_id }}" data-location_id="{{$location_id}}">
    <td readonly="readonly" style="width: 150px;"><input data-rid="{{ $rand_id }}" value="{{optional($r)->name}}" class="form-control l-location"></td>
    <td><input data-rid="{{ $rand_id }}" value="{{optional($r)->aisle}}"  class="form-control l-aisle"></td>
    <td><input data-rid="{{ $rand_id }}" value="{{optional($r)->bay}}"  class="form-control l-bay"></td>
    <td><input data-rid="{{ $rand_id }}" value="{{optional($r)->bin}}"  class="form-control l-bin"></td>
    <td><input data-rid="{{ $rand_id }}" value="{{optional($r)->level}}"  class="form-control l-level"></td>
    <td>
        <select data-rid="{{ $rand_id }}" class="form-control l-direction" style="width: 80px">
            <option value=""></option>
            <option {{optional($r)->direction == 'N'?'selected':''}}   value="N">N</option>
            <option {{optional($r)->direction == 'L'?'selected':''}}   value="L">Left</option>
            <option {{optional($r)->direction == 'R'?'selected':''}}  value="R">Right</option>
        </select>
    </td>
    <td>
        <select data-rid="{{ $rand_id }}" class="form-control l-color" style="width: 80px">
            <option value=""></option>
            <option  {{optional($r)->color == 'Red'?'selected':''}}   value="Red">Red</option>
        </select>
    </td>
    <td>
        <select data-rid="{{ $rand_id }}" class="form-control l-for-sale" style="width: 80px">
            <option {{optional($r)->for_sale == 'No'?'selected':''}}   value="No">No</option>
            <option {{optional($r)->for_sale == 'Yes'?'selected':''}}   value="Yes">Yes</option>
        </select>
    </td>
    <td style="width: 80px;">
        <a data-rid="{{ $rand_id }}" class="btn btn-xs btn-primary add-detail"> + </a>
        <a data-rid="{{ $rand_id }}"  data-location_id="{{$location_id}}"  class="btn btn-xs btn-danger del-detail"> - </a>
    </td>
</tr>
