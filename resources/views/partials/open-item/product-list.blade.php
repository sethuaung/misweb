<?php
$rand_id = rand(1,1000).time().rand(1,1000);
//if edit ===============================
//=======================================
if(isset($r)){
    $row = $r->product;
}else{
    $r = null;
}

//=======================================
//=======================================
?>
@if(isset($row))
    @if($row != null)
        <?php
        $p_spec = $row->spec;
        $row_spec = null;
        if($p_spec != null){
            if(count($p_spec)>0){
                $row_spec = $p_spec[0];
            }
        }
        ?>
        <tr class="product_id" id="p-{{$rand_id}}">
            <td id="product">
                <input type="hidden" name="line_open_detail_id[{{$rand_id}}]" class="line_open_detail_id" value="{{optional($r)->id}}">
                <input type="hidden" name="product_id[{{$rand_id}}]" class="product_id" value="{{$row->id}}">
                {{$row->product_name}} <span class="product_spec_name">  {{$row_spec != null ? "({$row_spec->name})":''}} </span>
                <span><i class="pull-right fa fa-edit tip edit" data-id="{{$rand_id}}" data-toggle="modal" data-target="#myModal-{{$rand_id}}-{{$rand_id}}" title="Edit" style="cursor:pointer;"></i></span>
                <!-- The Modal -->
                <div class="modal fade" id="myModal-{{$rand_id}}-{{$rand_id}}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">{{$row->product_name}}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-horizontal" role="form">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">{{_t('Warehouse')}}</label>
                                        <div class="col-sm-3">
                                            <select data-id="{{$rand_id}}" name="line_warehouse_id[{{$rand_id}}]" class="form-control select line_warehouse_id" title="{{_t('warehouse')}}" >
                                                <option value="0"></option>
                                                {!!  \App\Models\Warehouse::getWarehouse(optional($r)->line_warehouse_id,$row->id)!!}
                                            </select>
                                        </div>

                                        <label class="col-sm-3 control-label">{{_t('UOM')}}</label>
                                        <div class="col-sm-3">
                                            <select data-id="{{$rand_id}}" name="line_unit_id[{{$rand_id}}]" class="form-control select line_unit_id" title="{{_t('Product Unit')}}">
                                                <option value="0">-</option>
                                                {!!  \App\Models\Unit::getUnit(optional($r)->line_unit_id,$row->id)!!}
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">{{_t('Product Option')}}</label>
                                        <div class="col-sm-3">
                                            <select data-id="{{$rand_id}}" name="line_spec_id[{{$rand_id}}]" class="form-control select line_spec_id" title="{{_t('Product Option')}}" >
                                                {!!  \App\Models\ProductSpecCategory::getSpec(optional($r)->line_spec_id,$row->id)!!}
                                            </select>
                                        </div>
                                        <label class="col-sm-3 control-label">{{_t('Receive')}}</label>
                                        <div class="col-sm-3">
                                            <input data-id="{{$rand_id}}" value="{{optional($r)->line_qty}}"  number="number" type="text" class="form-control  p-line_qty_receive">
                                        </div>
                                    </div>
                                    </di>
                                </div>
                                <table class="table">
                                    <thead class="btn-default">
                                    <th>Location</th>
                                    <th >Qty</th>
                                    <th>Lot Number</th>
                                    <th>Expire Date</th>
                                    <th><a class="btn btn-sm btn-primary add-warehouse-detail" data-id="{{$rand_id}}" data-warehouse_detail="{{ optional($r)->line_warehouse_id}}"> + </a> </th>
                                    </thead>
                                    <tbody class="warehouse-list">
                                    <?php
                                    $o_details = optional($r)->open_item_location_detail;
                                    ?>
                                    @if($o_details != null)
                                        @if(count($o_details)>0)
                                            @foreach($o_details as $rr)
                                                @include('partials.open-item.warehouse-detail',['r'=> $r,'rr'=>$rr,'line_main_id'=>$rand_id])
                                            @endforeach
                                        @endif
                                    @endif
                                    </tbody>
                                </table>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">{{_t('Close')}}</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </td>
            <td class="right"  style="width: 100px;">
                <input type="text" number="number" data-id="{{$rand_id}}" class="form-control line_qty_receive" value="{{optional($r)->line_qty}}"  name="line_qty_receive[{{$rand_id}}]">
            </td>
            <td>
                <select data-id="{{$rand_id}}" name="line_unit_id[{{$rand_id}}]" class="form-control select line_unit_id" title="{{_t('Product Unit')}}">
                    <option value="0">-</option>
                    {!!  \App\Models\Unit::getUnit(optional($r)->line_unit_id,$row->id)!!}
                </select>
            </td>
            <td class="right">
                <input name="line_cost[{{$rand_id}}]" data-id="{{$rand_id}}" value="{{optional($r)->line_cost}}" class="form-control line_cost">

            </td>
            <td class="right">
                <span class="span_line_amount">{{optional($r)->line_amount}}</span>
                <input type="hidden" class="line_mount" name="line_amount[{{$rand_id}}]" value="{{optional($r)->line_amount}}"/>
            </td>
            <td>
                <i data-del_detail_id="{{optional($r)->id}}"  data-id="{{$rand_id}}" class="fa fa-times tip remove-product-line" title="Remove" style="cursor:pointer;"></i>
            </td>
        </tr>
    @endif
@endif

