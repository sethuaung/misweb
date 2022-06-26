<?php
$rand_id = rand(1,1000).time().rand(1,1000);
//if edit ===============================
$rd = isset($rd)?$rd:null;

$acc_chart = isset($acc_chart)?$acc_chart:optional($rd)->acc_chart;
?>

    @if(optional($rd)->cr>0 || $rd ==null)
    <tr id="id-{{$rand_id}}">
        <td>
            {{optional($acc_chart)->code}} - {{optional($acc_chart)->name}}
            <input data-id="{{$rand_id}}" class="hidden" value="{{ optional($acc_chart)->id}}" name="acc_id[{{$rand_id}}]">
        </td>
        <td style="width: 150px; display: none;">
            <input data-id="{{$rand_id}}" class="j_dr form-control right" value="{{optional($rd)->dr}}" number="number" name="j_dr[{{$rand_id}}]" type="text">
        </td>
        <td style="width: 150px;">
            <input data-id="{{$rand_id}}" class="j_cr form-control right" value="{{optional($rd)->cr}}" number="number" name="j_cr[{{$rand_id}}]" type="text">
        </td>
        <td style="width: 50px;" class="text-center">
            <i  data-id="{{$rand_id}}" class="fa fa-trash-o remove-detail"></i>
        </td>
    </tr>
    @endif
