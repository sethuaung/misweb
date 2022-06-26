
@push('crud_fields_scripts')
    <script>
         $(function () {
             $('body').on('change', '[name="center_id"]', function () {
                 var center_id = $(this).val();

                 $.ajax({
                     type: 'GET',
                     url: '{{url('api/get-group-loan-code')}}',
                     data: {
                         center_id: center_id,
                     },
                     success: function (res) {
                         var code = res.code;
                         $('[name="group_code"]').val(code);
                     }

                 });


             });

         })
    </script>
@endpush
