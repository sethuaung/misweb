<?php
    $rand_id = rand(1,1000).time().rand(1,1000);
    $line_main_id = isset($line_main_id)?$line_main_id:0;

    $rr = isset($rr)?$rr:null;
    $r = isset($r)?$r:null;

    $warehouse_id = isset($warehouse_id)?$warehouse_id:optional($r)->line_warehouse_id;

    //if edit ===============================
    //=======================================
?>
    <tr class="ware_id" id="w-{{$rand_id}}">
          <td width="200px">
              <select data-id="{{$rand_id}}" name="detail_location_id[{{$line_main_id}}][{{$rand_id}}]" class="form-control select2 line_location_id" title="{{_t('Locations')}}" >
                  <option value="0"></option>
                  {!! \App\Models\Location::getLocation(optional($rr)->location_id,$warehouse_id) !!}
              </select>
          </td>
        <td width="60px">
            <input type="text" data-id="{{$rand_id}}" name="detail_qty[{{$line_main_id}}][{{$rand_id}}]" value="{{optional($rr)->qty}}" class="form-control line_qty">
        </td>
        <td>
            {{--<input type="text" data-id="{{$rand_id}}" name="detail_lot[{{$line_main_id}}][{{$rand_id}}]" value="{{optional($rr)->lot}}" class="form-control line_lot">--}}
            <select data-id="{{$rand_id}}" name="line_lot[{{$line_main_id}}][{{$rand_id}}]" class="form-control select2 line_lot" title="{{_t('Locations')}}" >
                <option value="0">-</option>
                @if(optional($r)->line_location_id >0)
                    <?php
                    $lots = \App\Models\PreGoodsProductDetail::where('line_location_id',optional($r)->line_location_id)->get();
                    ?>
                    @foreach($lots as $lot)
                        <option @if($lot->lot == optional($r)->line_lot){{"selected"}}@endif value="{{$lot->lot}}">{{$lot->lot}}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td><i  data-id="{{$rand_id}}" class="fa fa-times tip remove-location-line" title="Remove" style="cursor:pointer;"></i></td>
    </tr>
