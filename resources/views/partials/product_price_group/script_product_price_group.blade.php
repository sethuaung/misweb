<div class="wrapper" style="text-align: center;">
    <button type="button" class="btn btn-primary generate">Generate</button>
</div>
<div class="show-price-group">

</div>
@push('crud_fields_scripts')
    <script>
        $(function () {

            $('#saveActions').parent().remove();

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

            $('.generate').on('click',function () {
                var category_id = $('[name="category_id[]"]') .val();

                $.ajax({
                    type:'GET',
                    url:'{{url('api/gen-unit-price-group')}}',
                    data:{
                        category_id : category_id,
                    },
                    success:function(res) {
                        $('.show-price-group').html(res);
                    }

                });
            });
        });
    </script>
@endpush

