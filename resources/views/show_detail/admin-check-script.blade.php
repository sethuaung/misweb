<link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
    jQuery(document).ready(function($) {
        /*$('.date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true
        });*/

        $(document).on('keyup','.unit-price',function (e) {
            var price = $(this).val();
            var product_id = $(this).data('product_id');
            var unit = $(this).data('unit');
            var pg = $(this).data('pg');

            $.ajax({
                type:'GET',
                url:'{{url('api/product_unit_price_group')}}',
                data:{
                    product_id : product_id,
                    unit_id: unit,
                    price_group_id: pg,
                    price : price
                },
                success:function(res) {

                }

            });
        });
    });
</script>
