@push('crud_fields_scripts')
<script>
   $(function () {

       $('body').on('change', '[name="contract_id"]', function () {

           var loan_id = $('[name="contract_id"]').val();

           $.ajax({
               type: 'GET',
               url: '{{url('/api/get-loan-disbursement/')}}' + '/' + loan_id,
               data: {
                  loan_id:loan_id
               },
               success: function (res) {

                       $('[name="client_id"]').val(res.client_id);
                       $('[name="loan_process_fee"]').val(res.loan_process_fee);
                       $('[name="compulsory_saving"]').val(res.compulsory_saving);
                       $('[name="loan_amount"]').val(res.loan_amount);
               }

           });


       });
   });

</script>
@endpush
