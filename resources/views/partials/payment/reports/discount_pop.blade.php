<?php
$rand_id = isset($rand_id)?$rand_id : rand(1,1000).time().rand(1,1000);
?>
<div class="box box-primary direct-chat direct-chat-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{_t('Bill')}}</h3>

    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row" style="padding: 10px 50px">
            <div class="col-md-6">
                <p>{{_t('Supplier')}}: <b class="p-company"></b></p>
                <p>{{_t('Ref No')}}:  <b class="p-name"></b></p>
                <p>{{_t('Date')}}:  <b class="p-name"></b></p>
                <p>{{_t('Original Amt')}}:  <b class="p-ori-amt"></b></p>
            </div>
            <div class="col-md-6">
                <p>{{_t('Amount Due')}}: <b class="p-amt"></b></p>
                <p>{{_t('Discount Used')}}:  <b class="p-disc"></b></p>
                <p>{{_t('Credit Used')}}:  <b class="p-credit"></b></p>
                <p>{{_t('Amt To Pay')}}:  <b class="p-amt-to-pay"></b></p>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <ul class="nav nav-tabs" style="width: 76% !important;">
        <li class="active a-discount-pop" style="width: 150px; text-align: center;" ><a data-toggle="tab" href="#dicount-{{$rand_id}}">{{_t('Discount')}}</a></li>
        <li class="a-credit-pop" style="width: 150px; text-align: center;"><a data-toggle="tab" href="#credit-{{$rand_id}}">{{_t('Credits')}}</a></li>
    </ul>
    <div class="tab-content">
        <div id="discount-{{$rand_id}}" class="discount-pop-sub">
           <table width="400px" style="margin-top: 30px;">
               <tr>
                   <td>{{_t('Terms')}}</td>
                   <td class="right">Net 30</td>
               </tr>
               <tr>
                   <td>{{_t('Suggest Discount')}}</td>
                   <td class="right">0.00</td>
               </tr>
               <tr>
                   <td>{{_t('Amount Discount')}}</td>
                   <td><input type="text" class="form-control right pop-discount"  data-id="{{$rand_id}}"  value="0" style="width: 200px"></td>
               </tr>
           </table>
        </div>
        <div id="main-{{$rand_id}}" class="credit-pop-sub" style="display: none;">
            <table class="table table-bordered" style="width: 74%;margin-top: 30px;">
                <thead>
                <tr>
                    <th>
                        <label>
                            <input class="p_checked_all" type="checkbox">
                        </label>
                    </th>
                    <th>{{_t('Ref.')}}</th>
                    {{--<th>DATE</th>--}}
                    <th nowrap>{{_t('Transaction')}}</th>
                    <th>{{_t('CREDIT AMT')}}</th>
                    <th>{{_t('AMT TO USED')}}</th>
                    <th>{{_t('CREDITS BALANCE')}}</th>
                </tr>
                </thead>
                <tbody>
                    @if($credits != null)
                        @if(count($credits)>0)
                            @foreach($credits as $row)
                                <?php
                                $credit_rand = rand(1,1000).time().rand(1,1000);
                                $reference = '';
                                if($row->train_type_deduct="deposit"){
                                    $depo = \App\Models\SupplyDeposit::find($row->tran_id_deduct);
                                    $reference = optional($depo)->reference;
                                }else{
                                    $po = \App\Models\Purchase::find($row->tran_id_deduct);
                                    $reference = optional($po)->reference_no;
                                }

                                ?>
                                <tr id="credit-{{$credit_rand}}">
                                    <td class="center">
                                        <label>
                                            <input data-id="{{$credit_rand}}" data-main_id="{{$rand_id}}" name="check_credit[{{$rand_id}}][{{$credit_rand}}]" value="{{$credit_rand}}" type="checkbox" class="credit_checkbox">
                                        </label>
                                    </td>
                                    <td nowrap>
                                        <span>{{ $reference }}</span>
                                        <input type="hidden" name="credit_reference_no[{{$rand_id}}][{{$credit_rand}}]" value="{{$row->tran_id_deduct}}">
                                    </td>
                                    {{--<td nowrap>
                                        <span>{{ $row->tran_date != null?$row->tran_date:'' }}</span>
                                        <input type="hidden" name="credit_date[{{$rand_id}}][{{$credit_rand}}]" value="{{ optional($po)->p_date != null?optional($po)->p_date:'' }}">
                                    </td>--}}
                                    <td nowrap>
                                        <span>{{$row->train_type_deduct}}</span>
                                        <input type="hidden" name="credit_transaction[{{$rand_id}}][{{$credit_rand}}]" value="{{$row->train_type_deduct}}">
                                    </td>
                                    <td class="right">
                                        <span class="credit_amount">{{$row->balance}}</span>
                                        <input type="hidden" name="credit_amount[{{$rand_id}}][{{$credit_rand}}]" class="h_credit_amount" value="{{$row->balance}}">
                                    </td>
                                    <td nowrap class="right">
                                        <input data-id="{{$credit_rand}}" data-main_id="{{$rand_id}}" name="amount_to_used[{{$rand_id}}][{{$credit_rand}}]"
                                               data-validation="number" data-validation-allowing="range[0;{{$row->balance}}],float"
                                               class="credit_amt_used" type="text" value="0.00">
                                    </td>
                                    <td nowrap class="right">
                                        <span class="credit_balance">{{$row->balance}}</span>
                                        <input type="hidden" name="credit_balance[{{$rand_id}}][{{$credit_rand}}]" class="h_credit_balance" value="{{$row->balance}}">
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="main-{{$rand_id}}">
                                <td colspan="3" class="right">
                                    {{_t('Total')}}
                                </td>
                                <td class="right">
                                    <span class="total_credit_amount"></span>
                                </td>
                                <td class="right">
                                    <span class="total_credit_amt_used"></span>
                                    <input type="hidden" class="h_total_credit_amt_used">
                                </td>
                                <td class="right">
                                    <span class="total_credit_balance"></span>
                                    <input type="hidden" class="h_total_credit_balance">
                                </td>
                            </tr>
                        @endif
                     @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
