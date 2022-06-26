
@push('crud_fields_scripts')

    <script>
        $(function () {
            change_saving_type();
            change_type();
            $('[name="plan_type"]').on('change',function () {
                change_type();
            });

            $('[name="saving_type"]').on('change',function () {
                change_saving_type();
            });
        });
        function change_type() {
            var plan_type = $('[name="plan_type"]').val();
            if (plan_type == "Expectation") {
                $('.fixed_amount').hide();
                $('.expectation_amount').show()
            } else {
                $('.fixed_amount').show();
                $('.expectation_amount').hide();
            }
        }

        function change_saving_type() {
            var plan_type = $('[name="saving_type"]').val();

            if (plan_type == 'Plan-Saving'){

                $('#allowed_day_to_cal_saving_start').parent().hide();
                $('#allowed_day_to_cal_saving_end').parent().hide();
                $('#select2_array_payment_method').parent().show();
                $('#term_value').parent().show();
                $('#minimum_required_saving_duration').parent().hide();
                $('#saving_amount').parent().show();

            }else{
                $('#allowed_day_to_cal_saving_start').parent().show();
                $('#allowed_day_to_cal_saving_end').parent().show();
                $('#select2_array_payment_method').parent().hide();
                $('#term_value').parent().hide();
                $('#minimum_required_saving_duration').parent().show();
                $('#saving_amount').parent().hide();

            }
        }
    </script>
@endpush
