<?php
    $_e = isset($entry)?$entry:null;
?>
@push('crud_fields_scripts')
    <script>
        $(function (e) {

            $('body').on('change','[name="id_format"]',function () {
               var id_format = $(this).val();

               if(id_format == "Auto"){
                   $('[name="group_code"]').attr('readonly', true);
                   $('[name="center_id"]').trigger('change');
               }else{
                   $('[name="group_code"]').removeAttr('readonly', true);
                   $('[name="group_code"]').val('');
               }
            });

            @if($_e == null)
            $('[name="id_format"]').trigger('change');
            @endif
        });
    </script>
@endpush
