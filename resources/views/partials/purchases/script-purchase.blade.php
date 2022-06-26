<?php
    $_e = isset($entry)?$entry:null;
    $purchase_details = optional($_e)->purchase_details;

    $purchase_currency = optional($_e)->currencies;
    $symbol = isset($purchase_currency->currency_symbol)?$purchase_currency->currency_symbol:'';
    $create_bill = request()->create_bill;
?>
<div class="div-del-detail"></div>
<table class="table table-bordered">
    <thead >
    <tr style="background-color:#3c8dbc;color:white;">
        <th>{{_t('Product')}} ({{_t('Code-Name')}})</th>
        <th  style="width: 100px;">{{_t('Quantity')}}</th>
        <th  style="width: 100px;">{{_t('Unit')}}</th>
        <th class="received_hidden">{{_t('Cost')}}</th>
        <th class="show-received" style="width: 100px;">{{_t('Received')}}</th>
        <th class="show-received" style="width: 100px;">{{_t('Remain')}}</th>
        <th class="show-received" style="width: 100px;">{{_t('Finished')}}</th>
        {{--<th>{{_t('In Hand')}}</th>--}}
        <th class="received_hidden">{{_t('Discount')}}</th>
        <th class="received_hidden">{{_t('Tax')}}</th>
        <th class="received_hidden">{{_t('Amount')}}</th>
        <th><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
    </tr>
    </thead>
    <tbody id="product-list">
    @if($purchase_details != null)
        @if(count($purchase_details)>0)
            @foreach($purchase_details as $r)
{{--                @if($r->purchase_status == 'pending' || $create_bill == 'return')--}}
                @if($r->purchase_status == 'pending')
                    @include('partials.purchases.product-list',['r'=>$r])
                @endif
            @endforeach
        @endif
    @endif
    </tbody>
    <tfoot>
    <tr class="received_hidden">
        <th colspan="1" class="right">{{_t('Total')}}</th>
        <th  class="right"><span class="f-qty">0</span></th>
        <th></th>
        <th></th>
        <th class="right"><span class="f-discount"></span></th>

        <th class="right"><span class="f-tax"></span></th>
        <th class="right"><span class="f-amount"></span></th>
        <th class="right"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
    </tr>
    </tfoot>
</table>

<div class="well well-sm bottom-total received_hidden" style="margin-bottom: 0px; position: fixed; bottom: 0px;right: 0px; width: 100%; z-index: 1;">
    <table class="table table-bordered table-condensed totals" style="margin-bottom:0;width: 100%">
        <tbody>
        <tr>
            <td style="width: 250px;"></td>
            <th>{{_t('Items')}} <span class="pull-right g_items">{{numb_format(optional($_e)->total_qty,4)}}</span></th>
            <th>{{_t('Total')}} <span class="pull-right"><span class="g_total_amt">{{numb_format(optional($_e)->subtotal,4)}}</span> <span class="currency">{{$symbol}}</span></span></th>
            <th>{{_t('Discount')}} <span class="pull-right"><span class="g_discount">{{numb_format(optional($_e)->discount_amount,4)}}</span> <span class="currency">{{$symbol}}</span></span></th>
            <th>{{_t('Tax')}} <span class="pull-right"><span class="g_tax">{{numb_format(optional($_e)->tax_amount,4)}}</span> <span class="currency">{{$symbol}}</span></span></th>
            <th>{{_t('Shipping')}} <span class="pull-right"><span class="g_shipping">{{numb_format(optional($_e)->shipping,4)}}</span> <span class="currency">{{$symbol}}</span></span></th>
            <th>{{_t('Grand Total')}} <span class="pull-right"><span class="g_total">{{numb_format(optional($_e)->grand_total,4)}}</span> <span class="currency">{{$symbol}}</span></span></th>
            <th style="width: 50px;text-align: right;"><button type="button" class="btn btn-info add_payment"  data-toggle="modal" data-target="#payModal">Add Payment</button></th>
        </tr>
        </tbody>
    </table>
</div>

@include('partials.payment_template_purchase',['_e' => $_e])

@push('crud_fields_styles')
    <style>
        .box{
            padding-bottom:70px;
        }
        .table-bottom{
            margin-bottom: 100px !important;
        }
        .show-received{
            display: none;
        }
    </style>
@endpush

@push('crud_fields_scripts')

    <script>
        var is_return = $('[name="is_return"]').val();

        is_return = is_return>0?is_return:0;

        function p_date_change(so_date){
            // console.log(so_date);
            var payment_term_id = $('[name="payment_term_id"]').val();
            update_due_date(payment_term_id, so_date);
        }

        function due_date_change(d_date){
            var payment_term_id = $('[name="payment_term_id"]').val();

            if (payment_term_id != null){
                $('[name="due_date"]').datepicker('setDate', d_date);
                $('[name="due_date"]').parent().find('[data-bs-datepicker]').val(d_date);
                $('[name="due_date"]').val(d_date);

            }

            @if($_e == null)
                update_due_date(payment_term_id, d_date);
            @endif
        }


        function delay_push(value,delaytime) {
            setTimeout(function(){

            }, delaytime >30 ? delaytime:5000);
        }

        function precise_round(num, dec){

            if ((typeof num !== 'number') || (typeof dec !== 'number'))
                return false;

            var num_sign = num >= 0 ? 1 : -1;

            return (Math.round((num*Math.pow(10,dec))+(num_sign*0.0001))/Math.pow(10,dec)).toFixed(dec);
        }


        function num2(num) {
            @if(companyReportPart() == 'company.chamnol_travel' || companyReportPart() == 'company.fullwelltrading')
                return precise_round(num,2)-0;
            @endif

                return num-0;
        }

        function num3(num,n) {
            @if(companyReportPart() == 'company.chamnol_travel' || companyReportPart() == 'company.fullwelltrading')
                return precise_round(num,2)-0;
            @endif

                return round2(num,n)-0;
        }


        function update_due_date(payment_term_id, date){
            // console.log(date);
            $.ajax({
                type: 'GET',
                url: '{{url('api/update-due-date')}}',
                data: {
                    payment_term_id: payment_term_id,
                    date: date
                },
                success: function (res) {
                    // console.log(res);
                    if(res.d){
                        $('[name="due_date"]').datepicker('setDate', res.d);
                        $('[name="due_date"]').parent().find('[data-bs-datepicker]').val(res.d);
                        $('[name="due_date"]').val(res.d);
                    }
                }

            });
        }

        $(function () {

            $('[name="payment_term_id"]').on('change',function () {
                var so_date = $('[name="p_date"]').val();
                var payment_term_id = $(this).val();
                update_due_date(payment_term_id, so_date);
            });


            /*$(window).bind('scroll', function() {
                if($(window).scrollTop() >= $('.box').offset().top + $('.box').outerHeight() - window.innerHeight) {
                    $(".bottom-total").addClass('table-bottom');
                }else{
                    $(".bottom-total").removeClass('table-bottom')
                }
            });
            */

            $('body').on('click','.remove-product-line',function () {
                var r_id = $(this).data('id');

                var del_detail_id = $(this).data('del_detail_id');
                var tr = $('#p-'+r_id);
                tr.remove();

                calculate_grand_total();

                if(del_detail_id > 0) {
                    $('.div-del-detail').append('<input type="hidden"  value="'+del_detail_id+'"  name="del_detail_id[]">');
                }
            });

            $('body').on('keyup','.line_qty',function () {
                var qty = $(this).val();
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                tr.find('.p-line_qty').val(qty);
                calculate_line_total(tr);
                calFcostPercent()
            });
            $('body').on('change','.line_unit_id',function () {
                var unit_id = $(this).val();
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                var product_id = tr.find('.product_id').val();
                var currency_id = $('[name="currency_id"]').val();
                var from_currency_id = tr.find('.from_currency_id').val();
                var date = $('[name="p_date"]').val();
                var price_group_id = $('[name="price_group_id"]').val();
                //alert(currency_id);
                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-cost-by-change-unit')}}',
                    data: {
                        product_id: product_id,
                        currency_id: currency_id,
                        from_currency_id: from_currency_id,
                        unit_id: unit_id
                    },
                    success: function (res) {

                        tr.find('.unit_cost').val(res);
                        calculate_line_total(tr);
                        calFcostPercent()
                        //$('[number="number"]').number( true, 2 );
                        $('.num').number( true, 2 );
                    }

                });

            });
            $('body').on('change','.foc',function () {
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                var foc = tr.find('.foc option:selected').val();
                tr.find('.span-line_foc').text(foc);
            });
            $('body').on('keyup','.p-line_qty',function () {
                var qty = $(this).val();
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                tr.find('.line_qty').val(qty);
                calculate_line_total(tr);
                calFcostPercent()
            });

            $('body').on('change','.line_tax_id',function () {
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                calculate_line_total(tr);
                calFcostPercent()
            });

            $('body').on('change','.line_spec_id',function () {
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                var spec = tr.find('.line_spec_id option:selected').text();
                tr.find('.product_spec_name').html('('+spec+')');
                calculate_line_total(tr);
            });

            $('body').on('keyup','.unit_discount',function () {
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                calculate_line_total(tr);
                calFcostPercent();
            });

            $('body').on('keyup','.unit_cost',function () {
                //var net_cost = $(this).val();
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                calculate_line_total(tr);
                calFcostPercent()
            });

            $('body').on('keyup','.p-cal-line_subtotal',function () {
                var subtotal = $(this).val();
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                var qty = tr.find('.line_qty').val();
                var cost = 0;

                if(qty != 0){
                    cost = num3( subtotal/qty,6);
                }

                tr.find('.unit_cost').val(cost);
                calculate_line_total(tr);
                calFcostPercent()
            });

            $('body').on('click','.calculate_line_total',function () {
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                var qty = tr.find('.line_qty').val();
                var subtotal = tr.find('.p-cal-line_subtotal').val();
                var cost = 0;
                if(qty != 0){
                    cost = num3(subtotal/qty,6);
                }
                tr.find('.unit_cost').val(cost);
                calculate_line_total(tr);
            });

            //========== cal G-Total ==================
            //=========================================
            $('[name="discount"]').on('keyup',function () {
                calculate_grand_total();
            });
            $('[name="bill_reference_id"]').on('change',function () {
                var bill_ref_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-shipping-by-reference')}}',
                    data: {
                        bill_ref_id: bill_ref_id,
                    },
                    success: function (res) {

                        console.log(res);
                        $('[name="shipping"]').val(res.shipping);
                        var sh = $('[name="shipping"]').val()-0;
                        if(sh >0){
                            $('[name="shipping"]').attr('readonly', true);
                        }else{
                            $('[name="shipping"]').attr('readonly', false);
                        }
                        calculate_grand_total();
                        calFcostPercent();
                        //$('[number="number"]').number( true, 2 );
                        $('.num').number( true, 2 );
                    }

                });
            });

            $('[name="shipping"]').on('keyup',function () {
                calculate_grand_total();
                calFcostPercent()

            });

            $('[name="tax_id"]').on('change',function () {
                calculate_grand_total();
            });

            $('[name="currency_id"]').on('change',function () {
                var symbol = $('[name="currency_id"] option:selected').data('symbol');
                var exchange = $('[name="currency_id"] option:selected').data('exchange');
                $('.currency').text(symbol);
                $('.h_exchange').val(exchange);
                //calculate_grand_total();
            });

            $('.bill-order').hide();
            $('.bill-received').hide();
            $('.bill-received-order').hide();
            $('#purchase_status').hide();

            $('[name = "purchase_type"]').on('change',function () {
                var purchase_type = $(this).val();
                if(purchase_type == "bill-only-from-order"){
                    $('.bill-order').show();
                    $('.bill-received').hide();
                    $('.bill-received-order').hide();
                    $('#purchase_status').show();
                }else if(purchase_type == "bill-only-from-received"){
                    $('.bill-order').hide();
                    $('.bill-received').show();
                    $('.bill-received-order').hide();
                    $('#purchase_status').hide();
                }else if(purchase_type == "bill-and-received-from-order"){
                    $('.bill-received').hide();
                    $('.bill-order').hide();
                    $('.bill-received-order').show();
                    $('#purchase_status').show();
                }else {
                    $('.bill-order').hide();
                    $('.bill-received').hide();
                    $('.bill-received-order').hide();
                    $('#purchase_status').hide();
                }
            });

            if('{{ optional($_e)->purchase_type}}' == "bill-only-from-order"){
                $('.bill-order').show();
                $('.bill-received').hide();
                $('.bill-received-order').hide();
            }else if('{{ optional($_e)->purchase_type}}' == "bill-only-from-received"){
                $('.bill-order').hide();
                $('.bill-received').show();
                $('.bill-received-order').hide();
            }else if('{{ optional($_e)->purchase_type}}' == "bill-and-received-from-order"){
                $('.bill-received').hide();
                $('.bill-order').hide();
                $('.bill-received-order').show();
            }else {
                $('.bill-order').hide();
                $('.bill-received').hide();
                $('.bill-received-order').hide();
            }
            $('[name="bill_order_id"]').on('change',function () {
               var id =  $(this).val();
                window.location.href = '{{url("/api/bill-from-order")}}'+'/'+id;
            });
            $('[name="bill_received_order_id"]').on('change',function () {
                var id =  $(this).val();
                window.location.href = '{{url("/api/bill-received-from-order")}}'+'/'+id;
            });

            $('[name="return_bill_received_id"]').on('change',function () {
                var id =  $(this).val();

                window.location.href = '{{url("/api/return-bill-received-id")}}'+'/'+id;
            });
            $('[name="currency_id"]').parent().hide();

            $('[name="supplier_id"]').on('change',function () {

                var supplier_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ url('api/get-supplier-currency_id') }}',
                    data: {
                        supplier_id: supplier_id,
                    },
                    success: function (res) {
                        //console.log(res);
                        var currency_id = res.currency_id;
                        $('[name="currency_id"]').val(currency_id);
                        $('[name="tax_id"]').val(res.tax_id);
                        $('[name="supply_phone"]').val(res.phone);
                        $('[name="supply_address"]').val(res.address);
                        $('[name="payment_term_id"]').val(res.payment_term_id);
                        $('[name="currency_id"]').trigger('change');
                        $('[name="payment_term_id"]').trigger('change');

                        $('#product-list').empty();
                        calculate_grand_total();
                    }

                });



                //
                // var symbol = $('[name="currency_id"] option:selected').data('symbol');
                // var exchange = $('[name="currency_id"] option:selected').data('exchange');
                // $('.currency').text(symbol);
                // $('.h_exchange').val(exchange);
                //calculate_grand_total();

            });

            $('body').on('click','.add-warehouse-detail',function () {
                var r_id = $(this).data('id');
                var tr =  $('#p-'+r_id);
                var warehouse_id = $(this).data('warehouse_detail');
                var main_w_id = $('[name="warehouse_id"]').val();
                alert(main_w_id);
                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-warehouse-detail')}}',
                    data: {
                        warehouse_id: warehouse_id,
                        line_main_id: r_id
                    },
                    success: function (res) {
                        tr.find('.warehouse-list').append(res);
                        $('.line_expire_date').datepicker({
                            format: 'yyyy-mm-dd'
                        });
                        tr.find('.line_location_id').select2({
                            theme: "bootstrap"
                        });
                    }

                });
            });

            $('body').on('change','.line_location_id',function () {
                var location_id = $(this).val();
                var tr = $(this).parent().parent();
                var tr_qty = $(this).parent().parent().parent().parent().parent();
                var p_id = tr_qty.find('.add-warehouse-detail').data('id');
                var _tr =  $('#p-'+p_id);

                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-location-from-purchase')}}',
                    dataType: 'JSON',
                    data: {
                        location_id: location_id
                    },
                    success: function (res) {
                        tr.find('.line_qty').val(res.qty);

                        tr.find('.line_qty').trigger('change');
                        tr.find('.line_lot').val(res.lot);

                        tr.find('.line_expire_date').val(res.factory_expire_date);
                        calculate_line_total(_tr);
                        calculate_line_qty(_tr);
                    }

                });
                calculate_line_total(_tr);

            });
            @if(isset($id))
            @if($id>0)
            calculate_grand_total();
            @endif
            @endif

        });

        function calculate_grand_total() {

            //== total line QTY ======
            var total_qty = 0;
            $('.line_qty').each(function () {
                var qty = $(this).val()-0;
                if (qty > 0) {
                    total_qty += qty;
                }
            });
            $('.f-qty').text(total_qty);
            $('.g_items').text(total_qty);
            $('.h_items').val(total_qty);

            //== total line discount ======
            var total_discount = 0;
            $('.line_discount_amount').each(function () {
                var discount = $(this).val()-0;
                if (discount > 0) {
                    total_discount += discount;
                }
            });
            $('.f-discount').text('('+num2(total_discount)+')');

            //== total line tax ======
            var total_tax = 0;
            $('.line_tax_amount').each(function () {
                var tax = $(this).val()-0;
                if (tax > 0) {
                    total_tax += tax;
                }
            });

            total_tax = num3(total_tax,6);

            $('.f-tax').text(total_tax);

            //== total line amount ======
            var subtotal = 0;
            $('.line_amount').each(function () {
                var total = $(this).val()-0;
                if (total > 0) {
                    subtotal += num3(total, 6);
                }
            });

            subtotal = num2(subtotal);
            $('.f-amount').text(subtotal);
            $('.h_total').val(subtotal);
            $('.g_total_amt').text(subtotal);

            //== cal G total ========
            var p_discount = $('[name="discount"]').val();
            var p_discount_p =0;
            var p_discount_a =0;

            if (p_discount.indexOf('%') != -1) {
                p_discount_p = p_discount.replace(/%/g, ' ');
            }
            else {
                p_discount_a = p_discount;
            }

            var g_discount = 0;
            if (p_discount_a > 0 && p_discount_p == 0) {
                g_discount =  num3(p_discount_a, 6);
            } else if (p_discount_a == 0 && p_discount_p > 0) {
                g_discount = num3(subtotal * p_discount_p / 100, 6);
            }

            var g_total_after_dis = subtotal - g_discount;
            var g_shipping = $('[name="shipping"]').val()-0;
            var t_g_shipping = $('[name="shipping"]').val()-0;
            var bill_ref = $('[name="bill_reference_id"]').val()-0;
            if (bill_ref >0){
                g_shipping = 0;
            }

            var g_tax = $('[name="tax_id"] option:selected').data('tax');

            var g_total_tax = 0;
            if(g_tax >0) {
                g_total_tax = num3( g_total_after_dis * (g_tax / 100), 6);
            }


            var g_total = num3( g_total_after_dis + g_total_tax + g_shipping,6);

            $('.g_discount').text(g_discount);
            $('.h_discount').val(g_discount);

            $('.g_shipping').text(t_g_shipping);
            $('.h_shipping').val(g_shipping);

            $('.g_tax').text(g_total_tax);
            $('.h_tax').val(g_total_tax);

            $('.g_total').text(g_total);
            $('.h_gtotal').val(g_total);
            $('.h_balance').val(g_total);
            $('.quick-amt').data('amt', g_total);
            //$('[number="number"]').number( true, 2 );
            $('.num').number( true, 2 );
        }

        function calculate_line_total(tr){
            var tax = tr.find('.line_tax_id option:selected').data('tax');
            var qty = tr.find('.line_qty').val();
            var bal = tr.find('.line_qty_remain').data('bal')-0;
            var cost = tr.find('.unit_cost').val();
            var unit = tr.find('.line_unit_id');
            var spec = tr.find('.line_spec_id');
            var _discount = tr.find('.unit_discount').val();
            var discount_p = 0;
            var discount_a = 0;

            var net_cost = 0;
            var discount_amount = 0;
            var qty_remain = 0;

            if(qty != 0 || qty != '') {
                if (_discount.indexOf('%') != -1) {
                    discount_p = _discount.replace(/%/g, ' ');
                }
                else {
                    discount_a = _discount;
                }

                if (discount_a > 0 && discount_p == 0) {
                    net_cost = cost - discount_a;
                    discount_amount = discount_a*qty;
                } else if (discount_a == 0 && discount_p > 0) {
                    net_cost = num3( cost * (1 - discount_p / 100),4);
                    discount_amount = num3( cost*qty*discount_p/100,4);
                } else {
                    net_cost = cost;
                }

                // console.log(net_cost)

                var unit_tax  = num3(net_cost * tax / 100,6);

                var line_total =0;
                if(tax > 0) {
                    line_total = num3( net_cost * qty * (1 + tax / 100),6);
                }else {
                    line_total = num3(net_cost * qty,4);
                }
                qty_remain = bal - qty;

                //=== unit cost ======
                tr.find('.span-p-net_unit_cost').text(net_cost);
                tr.find('.net_unit_cost').val(net_cost);
                tr.find('.span-net_unit_cost').text(net_cost);

                //=== tax ============
                tr.find('.unit_tax').val(unit_tax);
                tr.find('.span-p-unit_tax').text(unit_tax);
                tr.find('.line_tax_amount').val(num3(unit_tax*qty,6));
                tr.find('.span-line_tax_amount').text((num3(unit_tax*qty,6)) + ' (' + tax + '%)');


                tr.find('.span-line_discount_amount').text('('+discount_amount+')');
                tr.find('.line_discount_amount').val(discount_amount);

                tr.find('.span-line_amount').text(num3(line_total,6));
                tr.find('.line_amount').val(line_total);
                tr.find('.span-line_qty_remain').text(qty_remain);
                tr.find('.line_qty_remain').val(qty_remain);
                //$('[number="number"]').number( true, 2 );
                $('.num').number( true, 2 );

            }

            calculate_grand_total();
        }

        function select_product(product_id,unit_id) {
            var supplier_id = $('[name="supplier_id"]').val();
            var currency_id = $('[name="currency_id"]').val();
            if(supplier_id >0) {
                $.ajax({
                    type: 'GET',
                    url: '{{url('api/purchase-select-product')}}',
                    async: false,
                    data: {
                        product_id: product_id,
                        is_return: is_return,
                        currency_id: currency_id,
                    },
                    success: function (res) {
                        @if(companyReportPart() == 'company.pich_nara' || companyReportPart() == 'company.chamnol_travel' || companyReportPart() == 'company.theng_hok_ing' || companyReportPart() =='company.vang_houd')
                            $('#product-list').append(res);
                        @else
                            $('#product-list').prepend(res);
                        @endif

                        $('.line_qty:first').focus();
                        //$('[number="number"]').number( true, 2 );
                        var id = $(res).prop('id');
                        //console.log(id);
                        var tr = $('#' + id);

                        if(unit_id >0) {
                            tr.find('.line_unit_id').val(unit_id);
                        }
                        tr.find('.line_unit_id').trigger('change');
                        $('.num').number( true, 2 );
                    }

                });
            }else{
                alert('Please Select Supplier');
            }
        }

        $('.add_payment').on('click', function () {
            var item = $('.h_items').val() - 0;
            if (item == 0 || item == null) {
                alert('no item selected');
                return false;
            }
        });


        $(function () {
            @if($_e != null)
            $('#saveActions').hide();
                //$('[name="supplier_id"]').trigger('change');
                var purchase_type = $('[name="purchase_type"]').val();
            var supplier_id = $('[name="supplier_id"]').val();

            $.ajax({
                type: 'GET',
                url: '{{ url('api/get-supplier-currency_id') }}',
                data: {
                    supplier_id: supplier_id,
                },
                success: function (res) {
                    //console.log(res);
                    var currency_id = res.currency_id;
                    $('[name="currency_id"]').val(currency_id);
                    $('[name="tax_id"]').val(res.tax_id);
                    $('[name="supply_phone"]').val(res.phone);
                    $('[name="supply_address"]').val(res.address);
                    $('[name="payment_term_id"]').val(res.payment_term_id);
                    $('[name="currency_id"]').trigger('change');

                    var so_date = $('[name="p_date"]').val();
                    update_due_date(res.payment_term_id, so_date);

                    //$('#product-list').empty();
                }

            });

                //alert(purchase_type);
                if(purchase_type == 'bill-only-from-order' || purchase_type == 'bill-and-received-from-order') {

                    $('form').prop('action', '{{url('admin/bill')}}');
                    $('.received_hidden').show();
                    $('.show-received').hide();
                    $('[name="_method"]').remove();
                    $('[name="bill_order_id"]').val({{$_e->id}});
                    $('[name="purchase_type"]').hide();
                    $('.content-header').remove();
                }
                if(purchase_type == 'bill-only-from-received' ) {

                    $('form').prop('action', '{{url('admin/bill')}}');
                    $('.received_hidden').show();
                    $('.show-received').hide();
                    $('[name="_method"]').remove();
                    $('[name="purchase_type"]').hide();
                    $('.content-header').remove();
                }
                if(purchase_type == 'purchase-received'){

                    $('.received_hidden').hide();
                    $('.payment_hidden').hide();
                    $('#saveActions').show();
                    @if(request()->is_edit_received == null)
                    $('form').prop('action', '{{url('admin/goods-received')}}');
                    $('[name="_method"]').remove();

                    $('[name="received_order_id"]').val({{$_e->id}});

                    @endif
                    //$('[name="bill_order_id"]').val({{$_e->id}});
                    $('[name="purchase_type"]').hide();
                    $('.content-header').remove();
                    $('.show-received').show();
                }
                if(purchase_type == 'return-from-bill-received'){
                    //alert(purchase_type);
                    $('form').prop('action', '{{url('admin/purchase-return')}}');
                    $('.received_hidden').show();
                    $('.show-received').hide();
                    $('[name="_method"]').remove();
                    $('[name="purchase_type"]').hide();
                    $('.content-header').remove();
                }
                //$('[name="purchase_type"]').val('bill-only-from-order');
            @endif


        });

        var timer=0;
        clearTimeout(timer);
        timer = setTimeout(function () {

{{--            $('[name="payment_term_id"]').val('{{$_e->payment_term_id}}');--}}
//             $('[name="payment_term_id"]').trigger('change');

            var due_date = $('[name="due_date"]').val();
            $('[name="due_date"]').datepicker('setDate', due_date);
            $('[name="due_date"]').parent().find('[data-bs-datepicker]').val(due_date);

        },500 || 0);



       {{-- @if(request()->create_bill2 != 'edit' && request()->create_bill != 'return')
            $(function () {
                $('.h_items').val(0);
            });
       @endif--}}
        function calFcostPercent(){
            var shipping = $('[name="shipping"]').val()-0;
            var g_total = $('.h_total').val()-0;
            var tax_ = $('.f-tax').text();
            var tax = tax_.replace(',','');
            var total = g_total - tax;
            var shipping_percent = 0;
            if(g_total >0){
                shipping_percent = shipping / total;
            }
            calFCost(shipping_percent)
       }
       function calFCost(shipping_percent) {
            $('.line_product1').each(function () {
                var tr = $(this);
                var line_tax = tr.find('.line_tax_amount').val()??0;
                var line_amount = tr.find('.line_amount').val()??0;
                var line_qty = tr.find('.line_qty').val()??0;
                var line_total = line_amount - line_tax;

                var line_cost = line_total * shipping_percent;
                var line_f_cost = 0;
                if(line_qty >0){
                    line_f_cost = line_cost / line_qty;
                }
                tr.find('.f_cost').val(line_f_cost);
            });
       }
    </script>

@endpush
