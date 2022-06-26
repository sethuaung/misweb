@push('crud_fields_scripts')
    <script>
        $(function () {

            var  available_balance = $('[name="available_balance"]').val();


            var  principle = $('[name="principle"]').val();
            $('body').on('change', '.cash_from', function () {
                var cash_from = $('[name="cash_from"]').val();

                if(cash_from=='Principal amount'){
                    $('[name="cash_balance"]').val(principle);
                }else if(cash_from=='Available balance'){
                    $('[name="cash_balance"]').val(available_balance);
                }
                //console.log(cash_from);
            });
            $('body').on('keyup', '.cash_withdrawal', function () {
                var cash_withdrawal = $('.cash_withdrawal').val();
                var cash_balance = $('[name="cash_balance"]').val();
                var remaining_balance = 0;
                remaining_balance = cash_balance-cash_withdrawal;
                $('[name="cash_remaining"]').val(remaining_balance);
                check(cash_balance,cash_withdrawal);

            });

        });


        function check(available_balance,cash_withdrawal) {
            if (cash_balance-cash_withdrawal>=0){
                $('[type="submit"]').prop("disabled", false);
            }

            else {
                $('[type="submit"]').prop("disabled", true);
            }
        }

    </script>
@endpush