@push('crud_fields_scripts')
    <script>
        $(function () {
            $('body').on('click','.dak_blog',function (e) {
                   e.preventDefault();
                   var amount = $('[name="amount"]').val()-0.00;
                   var number = 0.01;
                   var dak = 0.00;
                   if(amount >0){
                       dak  =amount - number;
                   }
                $('[name="amount"]').val(dak);

            });
            $('body').on('click','.plus_blog',function (e) {
                  e.preventDefault();
                 var amount = $('[name="amount"]').val()-0.00;
                 var number = 0.01;
                 var sum = amount + number;
                 $('[name="amount"]').val(sum);

            });
        });
    </script>
@endpush