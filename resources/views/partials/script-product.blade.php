<?php
    $_e = isset($entry)?$entry:null;
    $bundle_details = optional($_e)->bundle_details;

?>
<table class="table table-bordered">
    <thead >
    <tr style="background-color:#3c8dbc;color:white">
        <th>Product Name</th>
        <th>Quantity</th>
        <th>UOM</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody id="product-list">
        @if($bundle_details != null)
            @foreach($bundle_details as $r)
                @include('partials.product-list')
            @endforeach
        @endif
    </tbody>
</table>
@push('crud_fields_scripts')

    <script>



       $('body').on('click','.remove-product',function () {
           var tr=$(this).parent().parent();
           tr.remove();
       });

       function select_product(product_id) {
           $.ajax({
               type: 'GET',
               url: '{{url('api/bundle-select-product')}}',
               async: false,
               data: {
                   product_id: product_id,
               },
               success: function (res) {
                   $('#product-list').append(res)
               }

           });
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


       });

    </script>
@endpush