

@push('crud_fields_scripts')

    <style>
      /*  .content {
            background: #ffffff;
        }*/
    </style>

    <script>

        $(function () {

            $('body').on('keyup', '.deposit', function () {
                cal_total_amount();
            });


            cal_total_amount();
        });


        function cal_total_amount() {
           // var deposit = 0;
            var total_amt = 0;

            var loan_amount = $('[name="loan_amount"]').val() - 0;
            var deposit = $('[name="deposit"]').val() - 0;

            total_amt = loan_amount - deposit;
            $('[name="total_money_disburse"]').val(round(total_amt,2));
        }


    </script>


@endpush
