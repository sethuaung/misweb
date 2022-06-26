@php
$id = isset($id)?$id:0;
$_e = isset($entry)?$entry:null;

@endphp



<table class="table table-bordered">
    <thead>
    <tr>
        <th>{{_t('Name')}}</th>
        <th>{{_t('Quantity unit')}}</th>
        <th>{{_t('Code')}}</th>
        <th style="display: none;">{{_t('Length (cm)')}}</th>
        <th style="display: none;">{{_t('Width (cm)')}}</th>
        <th style="display: none;">{{_t('Height (cm)')}}</th>
        <th style="display: none;">{{_t('BCM')}}</th>
        <th style="display: none;">{{_t('Weight (Kg)')}}</th>
        <th></th>
    </tr>
    </thead>
    <tbody  class="gen">

        @if($_e != null)
           @include('partials.unit.gen_product_unit',['product_id'=>optional($_e)->id])
        @endif
    </tbody>
</table>


@push('crud_fields_scripts')
    <script>

        $(function () {
            $('body').on('keyup','.cbm-l,.cbm-wi,.cbm-h',function () {
                var tr = $(this).parent().parent();

                var l = tr.find('.cbm-l').val()-0;
                var w = tr.find('.cbm-wi').val()-0;
                var h = tr.find('.cbm-h').val()-0;
                var bcm =  (l*w*h)/1000000;
                tr.find('.bcm').val(bcm);
            });


            $('[name="update_account"]').trigger('change');

            $('[name="category_id"]').on('change',function (e) {

                var category_id = $(this).val();

                $.ajax({
                    type:'GET',
                    dataType:'JSON',
                    //async:false,
                    url:'{{url('api/get-acc-from-category')}}',
                    data:{
                        category_id:category_id
                    },
                    success:function(res){
                        $.each(res, function(k, v) {

                            $.ajax({
                                type:'GET',
                                //async:false,
                                url:'{{url('api/get-acc-op')}}',
                                data:{
                                    acc_id:v
                                },
                                success:function(op){
                                    $('[name="'+k+'"]').html(op);
                                }

                            });


                        });
                    }

                });


            });

            $('.generate_unit').on('click',function (e) {
                e.preventDefault();
                genProductUnit({{$id}});
            });

            $('body').on('keyup','.package-code',function () {
                var code = $(this).val();
                var d = $(this);
                $.ajax({
                    type:'GET',
                    dataType:'JSON',
                    //async:false,
                    url:'{{url('api/check-package-code')}}',
                    data:{
                        code:code
                    },
                    success:function(res){
                        if(res.error>0 || checkDupCode(code)){
                            alert(code + ' already exist!!');
                            d.val('');
                            d.focus();
                        }
                    }
                });
            });

            @if(isset($id))
            @if($id>0)
            genProductUnit({{$id}});

            @if(!($_e->stock_acc_id>0))
            $('[name="category_id"]').trigger('change');
            @endif
            @endif
            @endif

        });
        function checkDupCode(code) {
            var c = 0;
            $('.package-code').each(function () {
                var im = $(this).val();
                if(im == code){
                    c++;
                }
            });

            return c > 1;

        }

        $(function () {

            $('[name="update_account"]').on('change',function (e) {
                var update_acc = $(this).val();
                if(update_acc == 'yes'){
                    $('[href="#tab_account"]').show();
                }else {
                    $('[href="#tab_account"]').hide();
                    //$( "#form_tabs" ).tabs({ active: 0 });

                    //$('.nav-tabs a[href="#imagedescription]').tab('show');
                    $('#form_tabs a:first').tab('show');
                }

            });

            $('[name="update_account"]').trigger('change');

            $('[name="category_id"]').on('change',function (e) {

                var category_id = $(this).val();

                $.ajax({
                    type:'GET',
                    dataType:'JSON',
                    //async:false,
                    url:'{{url('api/get-acc-from-category')}}',
                    data:{
                        category_id:category_id
                    },
                    success:function(res){
                        $.each(res, function(k, v) {

                            $.ajax({
                                type:'GET',
                                //async:false,
                                url:'{{url('api/get-acc-op')}}',
                                data:{
                                    acc_id:v
                                },
                                success:function(op){
                                    $('[name="'+k+'"]').html(op);
                                }

                            });


                        });
                    }

                });


            });

            $('.generate_unit').on('click',function (e) {
                e.preventDefault();
                genProductUnit({{$id}});

            });


            @if(isset($id))
                @if($id>0)
                    genProductUnit({{$id}});




                @endif
            @endif

        });


        function genProductUnit(id) {
            var unit = $('[name="unit_variant[]"]').val();

            $.ajax({
                type:'POST',
                url:'{{url('api/gen-unit-variant')}}',
                data:{
                    unit:unit,
                    id:id
                },
                success:function(res){
                    $('.gen').html(res);
                }

            });
        }
    </script>
@endpush
