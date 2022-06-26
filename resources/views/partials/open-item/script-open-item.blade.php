<?php
$_e = isset($entry)?$entry:null;
$open_details = optional($_e)->open_item_detail;

?>
<div class="div-del-detail"></div>
<table class="table table-bordered">
    <thead >
    <tr style="background-color:#3c8dbc;color:white;">
        <th>{{_t('Product')}} ({{_t('Code-Name')}})</th>
        <th  style="width: 100px;">{{_t('QTY')}}</th>
        <th  style="width: 150px;">{{_t('UOM')}}</th>
        <th  style="width: 100px;">{{_t('Cost')}}</th>
        <th  style="width: 100px;">{{_t('Amount')}}</th>
        <th><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
    </tr>
    </thead>
    <tbody id="product-list">
    @if($open_details != null)
        @if(count($open_details)>0)
            @foreach($open_details as $r)
                @include('partials.open-item.product-list',['r'=>$r])
            @endforeach
        @endif
    @endif
    </tbody>
</table>

@push('crud_fields_styles')
    <style>
        .box{
            padding-bottom:70px;
        }
        .table-bottom{
            margin-bottom: 100px !important;
        }
    </style>
@endpush

@push('crud_fields_scripts')

    <script>
        $(function () {


            $('body').on('click','.remove-product-line',function () {
                var r_id = $(this).data('id');
                var del_detail_id = $(this).data('del_detail_id');
                var tr = $('#p-'+r_id);
                tr.remove();

                //calculate_grand_total();
                if(del_detail_id > 0) {
                    $('.div-del-detail').append('<input type="hidden"  value="'+del_detail_id+'"  name="del_detail_id[]">');
                }
            });

            $('body').on('keyup','.line_qty_receive',function () {
                var qty = $(this).val();
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                tr.find('.p-line_qty_receive').val(qty);
                calculate_line_total(tr);
                calculate_line_amount(tr);
            });

            $('body').on('keyup','.p-line_qty_receive',function () {
                var qty = $(this).val();
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                tr.find('.line_qty_receive').val(qty);
                calculate_line_total(tr);
            });
            $('body').on('keyup','.line_cost',function () {
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                calculate_line_total(tr);
                calculate_line_amount(tr);
            });
            $('body').on('change','.line_spec_id',function () {
                var r_id = $(this).data('id');
                var tr = $('#p-'+r_id);
                var spec = tr.find('.line_spec_id option:selected').text();
                tr.find('.product_spec_name').html('('+spec+')');
                calculate_line_total(tr);
            });

            @if(isset($id))
            @if($id>0)
            //calculate_grand_total();
            @endif
            @endif


            $('body').on('change','.line_warehouse_id', function () {
                var warehouse_id = $(this).val();

                var r_id = $(this).data('id');
                var tr =  $('#p-'+r_id);
                tr.find('.warehouse-list').empty();
                tr.find('.add-warehouse-detail').data('warehouse_detail',warehouse_id);

            });

            $('body').on('click','.add-warehouse-detail',function () {
                var r_id = $(this).data('id');
                var tr =  $('#p-'+r_id);
                var warehouse_id = $(this).data('warehouse_detail');
                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-open-warehouse-detail')}}',
                    data: {
                        warehouse_id: warehouse_id,
                        line_main_id: r_id
                    },
                    success: function (res) {
                        tr.find('.warehouse-list').append(res);

                        $('.line_expire_date').datepicker({
                            format: 'yyyy-mm-dd'
                        });
                        $('.line_location_id').select2({
                            theme: "bootstrap"
                        });
                    }

                });
            });
            $('.line_location_id').select2({
                theme: "bootstrap"
            });
            $('body').on('keyup','.line_qty',function () {
                var _tr = $(this).parent().parent().parent().parent();
                var r_id = _tr.find('.add-warehouse-detail').data('id');

                var tr =  $('#p-'+r_id);
                calculate_line_qty(tr);
                calculate_line_total(tr);
                calculate_line_amount(tr);
              /*  tr.find('.p-line_qty_receive').val(total_qty);
                tr.find('.line_qty_receive').val(total_qty);*/
            });
            $('body').on('click','.remove-location-line',function () {
                var _tr = $(this).parent().parent().parent().parent();
                var r_id = $(this).data('id');
                var p_id = _tr.find('.add-warehouse-detail').data('id');
                var tr =  $('#p-'+p_id);
                var l_tr = _tr.find('#w-'+r_id);

                l_tr.remove();
                calculate_line_qty(tr);
                calculate_line_total(tr);

            });
            $('.line_expire_date').datepicker();
        });

        function calculate_line_total(tr){
            var line_qty_purchase = tr.find('.line_qty_purchase').val() -0;
            var line_qty_receive = tr.find('.line_qty_receive').val()-0;
            var line_qty_received = tr.find('.line_qty_received').val()-0;
            var unit = tr.find('.line_unit_id');
            var spec = tr.find('.line_spec_id');

            if(line_qty_receive >=0) {

                var line_qty_remain = line_qty_purchase - (line_qty_receive + line_qty_received);

                line_qty_remain = line_qty_remain > 0?line_qty_remain:0;
                tr.find('.span-line_qty_remain').text(line_qty_remain);
                tr.find('.line_qty_remain').val(line_qty_remain);

            }
        }

        function select_product(product_id) {
            $.ajax({
                type: 'GET',
                url: '{{url('api/open-item-select-product')}}',
                data: {
                    product_id: product_id,
                },
                success: function (res) {
                    $('#product-list').append(res);
                }

            });
        }
        function calculate_line_qty(tr) {
            var total_qty = 0;
            tr.find('.line_qty').each(function () {

                var qty = $(this).val()-0;
                if (qty > 0) {
                    total_qty += qty;
                }

            });
            tr.find('.p-line_qty_receive').val(total_qty);
            tr.find('.line_qty_receive').val(total_qty);
        }
        function calculate_line_amount(tr) {
            var qty = tr.find('.line_qty_receive').val()-0;
            var cost = tr.find('.line_cost').val()-0;
            var total = 0;
            total = qty*cost;

            tr.find('.span_line_amount').text(total);
            tr.find('.line_mount').val(total);
        }
    </script>
@endpush
