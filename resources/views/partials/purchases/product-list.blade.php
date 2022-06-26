

<?php

   $currency_id= isset($entry)?$entry->currency_id:$currency_id;
 //dd($entry->currency_id);
    $rand_id = rand(1,1000).time().rand(1,1000);
    //if edit ===============================
    //=======================================
    if(isset($r)){
        $row = $r->product;
    }else{
        $r = null;
    }
    $is_return = isset($is_return)?$is_return:0;
    //=======================================
    //=======================================

    $m2 = getSetting();
    $class = getSettingKey('show-class', $m2);
    $job = getSettingKey('show-job', $m2);
       //dd($currency_id);
?>
@if(isset($row))
    @if($row != null)
        <?php
        $p_spec = $row->spec;
        $row_spec = null;
        if($p_spec != null){
            if(is_array($p_spec)){
                $row_spec = $p_spec[0];
            }
        }

        ?>
        <tr class="product_id line_product1" id="p-{{$rand_id}}">
            <td id="product">
                <input type="hidden" name="line_purchase_detail_id[{{$rand_id}}]" class="line_purchase_detail_id" value="{{optional($r)->id}}">
                <input type="hidden" name="from_currency_id[{{$rand_id}}]" class="from_currency_id" value="{{$row->currency_purchase_id}}">
                <input type="hidden" name="f_cost[{{$rand_id}}]" class="f_cost" value="{{optional($r)->f_cost>0?optional($r)->f_cost:0}}">

                <input type="hidden" name="product_id[{{$rand_id}}]" class="product_id" value="{{$row->id}}">
               {{$row->upc?$row->upc.' -':''}} {{$row->product_name}} <span class="product_spec_name">  {{$row_spec != null ? "({$row_spec->name})":''}} </span>
                <span><i class="pull-right fa fa-edit tip edit" data-id="{{$rand_id}}" data-toggle="modal" data-target="#myModal-{{$rand_id}}-{{$rand_id}}" title="Edit" style="cursor:pointer;"></i></span>
                <!-- The Modal -->
                <div class="modal fade" id="myModal-{{$rand_id}}-{{$rand_id}}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">{{($row->upc??$row->sku).'-'.$row->product_name}}</h4>
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

                                        <label class="col-sm-3 control-label">{{_t('Unit Tax')}}</label>
                                        <div class="col-sm-3">
                                            <select data-id="{{$rand_id}}" name="line_tax_id[{{$rand_id}}]" class="form-control line_tax_id">
                                                <option value="0" data-tax="0"></option>
                                                {!! \App\Models\Tax::getTax(optional($r)->line_tax_id,$row->id) !!}
                                            </select>
                                        </div>
                                    </div>


                                   {{-- <div class="form-group">
                                        <label class="col-sm-3 control-label">{{_t('Quantity')}}</label>
                                        <div class="col-sm-3">
                                            <input data-id="{{$rand_id}}" value="{{optional($r)->line_qty}}"  number="number" type="text" class="form-control  p-line_qty">
                                        </div>

                                        <label class="col-sm-3 control-label">{{_t('Product Unit')}}</label>
                                        <div class="col-sm-3">
                                            <select data-id="{{$rand_id}}" name="line_unit_id[{{$rand_id}}]" class="form-control select line_unit_id" title="{{_t('Product Unit')}}">
                                                {!!  \App\Models\Unit::getUnit(optional($r)->line_unit_id,$row->id)!!}
                                            </select>
                                        </div>
                                    </div>--}}

                                    <div class="form-group">
                                        {{--<label class="col-sm-3 control-label">{{_t('Product Option')}}</label>
                                        <div class="col-sm-3">
                                            <select data-id="{{$rand_id}}" name="line_spec_id[{{$rand_id}}]" class="form-control select line_spec_id" title="{{_t('Product Option')}}" >
                                                {!!  \App\Models\ProductSpecCategory::getSpec(optional($r)->line_spec_id,$row->id)!!}
                                            </select>
                                        </div>--}}
                                        <label class="col-sm-3 control-label">{{_t('Quantity')}}</label>
                                        <div class="col-sm-3">
                                            <input data-id="{{$rand_id}}" value="{{optional($r)->line_qty}}"  number="number" type="text" class="form-control  p-line_qty">
                                        </div>

                                        <label class="col-sm-3 control-label">{{_t('Unit Discount')}}</label>
                                        <div class="col-sm-3">
                                            <input number="number" name="unit_discount[{{$rand_id}}]" data-id="{{$rand_id}}" value="{{optional($r)->unit_discount}}" type="text" class="form-control unit_discount">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        @if ($class == 'Yes')
                                            <label class="col-sm-3 control-label">{{_t('Classes')}}</label>
                                            <div class="col-sm-3">
                                                <select data-id="{{$rand_id}}" name="line_class_id[{{$rand_id}}]" class="form-control select line_class_id" title="{{_t('class')}}" >
                                                    <option value="0"></option>
                                                    {!! \App\Models\AccClass::getAccClass(optional($r)->class_id) !!}
                                                </select>
                                            </div>
                                        @endif

                                        @if ($job == 'Yes')
                                            <label class="col-sm-3 control-label">{{_t('Job')}}</label>
                                            <div class="col-sm-3">
                                                <select data-id="{{$rand_id}}" name="line_job_id[{{$rand_id}}]" class="form-control select line_job_id" title="{{_t('job')}}" >
                                                    <option value="0"></option>
                                                    {!! \App\Models\Job::getJob(optional($r)->job_id) !!}
                                                </select>
                                            </div>
                                        @endif

                                    </div>

                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th style="width:25%;">{{_t('Net Unit Cost')}}</th>
                                                <th style="width:25%;">{{--<span class="span-p-net_unit_cost">{{optional($r)->net_unit_cost}}</span>--}}
                                                    <input  name="net_unit_cost[{{$rand_id}}]"  data-id="{{$rand_id}}" value="{{optional($r)->net_unit_cost}}" type="hidden" class="form-control net_unit_cost">
                                                </th>
                                                <th style="width:25%;">{{_t('Unit Tax')}}</th>
                                                <th style="width:25%;"><span class="span-p-unit_tax">{{optional($r)->unit_tax}}</span>
                                                    <input  name="unit_tax[{{$rand_id}}]" value="{{optional($r)->unit_tax}}"  data-id="{{$rand_id}}" type="hidden" class="form-control unit_tax">
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">{{_t('Calculate Unit Cost')}}</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="pcost" data-id="{{$rand_id}}" class="col-sm-6 control-label">{{_t('Subtotal')}} (QTYxUnit-Cost)</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <input type="text" data-id="{{$rand_id}}" class="form-control p-cal-line_subtotal">
                                                        <div class="input-group-addon" style="padding: 2px 8px;">
                                                            <a href="javascript:void(0)"  data-id="{{$rand_id}}" class="tip calculate_subtotal" title="" data-original-title="{{_t('Calculate Unit Cost')}}">
                                                                <i class="fa fa-calculator"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <input type="text" number="number" data-id="{{$rand_id}}" class="form-control line_qty"
                       value="{{request()->create_bill =='received'?optional($r)->line_qty_remain:  optional($r)->line_qty-optional($r)->return_qty}}"  name="line_qty[{{$rand_id}}]">
            </td>
            <td>
                <select data-id="{{$rand_id}}" name="line_unit_id[{{$rand_id}}]" class="form-control select line_unit_id" title="{{_t('Product Unit')}}">
                {!!  \App\Models\Unit::getUnitPurchase(optional($r)->line_unit_id,$row->id)!!}
                </select>
            </td>

            <td class="right received_hidden" style="width: 100px">
                {{--<span style="display: none;" class="span-net_unit_cost" data-id="{{$rand_id}}">{{optional($r)->unit_cost}}</span>
                --}}
                <input  number="number" name="unit_cost[{{$rand_id}}]"  data-id="{{$rand_id}}" value="{{(optional($r)->unit_cost != null)?optional($r)->unit_cost:getPurchaseCostnew($row->id,$row->default_purchase_unit,$row->currency_purchase_id,$currency_id)}}" type="text" class="form-control unit_cost">
            </td>
            <td class="right show-received">
                <span class="span-line_qty_received num" style="color: red;" data-id="{{$rand_id}}">{{optional($r)->line_qty_received}}</span>
                <input type="hidden" class="line_qty_received" value="{{optional($r)->line_qty_received}}" name="line_qty_received[{{$rand_id}}]" />
            </td>
            <td class="right show-received">
                <span class="span-line_qty_remain num" data-id="{{$rand_id}}">{{request()->create_bill =='received'?0: optional($r)->line_qty_remain}}</span>
                <input data-bal="{{optional($r)->line_qty_remain}}" name="line_qty_remain[{{$rand_id}}]" value="{{request()->create_bill =='received'?0: optional($r)->line_qty_remain}}"  data-id="{{$rand_id}}"
                        type="hidden" class="form-control line_qty_remain">
            </td>
            <td class="show-received" style="text-align: center;">
                <input type="checkbox" name="line_finished[{{$rand_id}}]" value="0" data-id="{{$rand_id}}">
            </td>
            {{--<td class="right" style="display: none">
                <span class="span-stock-in-hand" data-id="{{$rand_id}}">0</span>
            </td>--}}
            <td class="right received_hidden">
                <span class="span-line_discount_amount num" style="color: red;" data-id="{{$rand_id}}">{{optional($r)->line_discount_amount}}</span>
                <input type="hidden" class="line_discount_amount" value="{{optional($r)->line_discount_amount}}" name="line_discount_amount[{{$rand_id}}]" />
            </td>
            <td class="right received_hidden">
                <span class="span-line_tax_amount" data-id="{{$rand_id}}">{{optional($r)->line_tax_amount}}</span>
                <input  name="line_tax_amount[{{$rand_id}}]" value="{{optional($r)->line_tax_amount}}"  data-id="{{$rand_id}}"
                        type="hidden" class="form-control line_tax_amount">

            </td>
            <td class="right received_hidden">
                <?php
                    $class_num = 'num';
                    if (companyReportPart() =='company.theng_hok_ing'){
                        $class_num='';
                    }
                ?>
                <span class="span-line_amount {{$class_num}}" data-id="{{$rand_id}}">{{optional($r)->line_amount-optional($r)->return_grand_total}}</span>

                <input type="hidden" class="line_amount" value="{{optional($r)->line_amount-optional($r)->return_grand_total}}" name="line_amount[{{$rand_id}}]" />
            </td>
            <td>
                <i data-del_detail_id="{{optional($r)->id}}"  data-id="{{$rand_id}}" class="fa fa-times tip remove-product-line" title="Remove" style="cursor:pointer;"></i>
            </td>
    </tr>
    @endif
@endif
