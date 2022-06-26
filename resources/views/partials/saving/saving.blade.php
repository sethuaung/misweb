
@push('crud_fields_scripts')

    <script>
        $(function () {
            change_saving_type();
            change_type();
            /*$('[name="plan_type"]').on('change',function () {
                change_type();
            });*/
            $('[name="saving_product_id"]').on('change',function () {
                savingProduct();
                // get_payment_schedule();
                change_saving_type();
            });
            $('[name="saving_type"]').on('change',function () {
                change_saving_type();
            });

            $('[name="saving_amount"]').on('change',function () {
                // get_payment_schedule();
            });

            $('[name="term_value"]').on('change',function () {
                // get_payment_schedule();
            });

            $('[name="interest_rate"]').on('change',function () {
                // get_payment_schedule();
            });

            $('[name="interest_compound"]').on('change',function () {
               // get_payment_schedule();
            });

            $('[name="duration_interest_calculate"]').on('change',function () {
                // get_payment_schedule();
            });

        });

        function first_deposit_date_change() {
            get_payment_schedule();

        }

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

        function get_payment_schedule() {
            var first_deposit_date =  $('[name="first_deposit_date"]').val();
            var plan_type = $('[name="saving_type"]').val();
            var expectation_amount = $('[name="expectation_amount"]').val();
            var fixed_payment_amount = $('[name="fixed_payment_amount"]').val();
            var saving_term = $('[name="saving_term"]').val();
            var term_value = $('[name="term_value"]').val();
            var payment_term = $('[name="payment_term"]').val();
            var term_interest_compound = $('[name="term_interest_compound"]').val();
            var interest_rate = $('[name="interest_rate"]').val();
            var interest_rate_period = $('[name="interest_rate_period"]').val();
            var saving_amount = $('[name="saving_amount"]').val();
            var interest_compound = $('[name="interest_compound"]').val();
            var duration_interest_calculate = $('[name="duration_interest_calculate"]').val();
            var payment_method = $('[name="payment_method"]').val();

            $.ajax({
                type: 'GET',
                url: '{{url('api/get-saving-deposit-schedule')}}',
                async: false,
                data: {
                    first_deposit_date: first_deposit_date,
                    plan_type: plan_type,
                    expectation_amount: expectation_amount,
                    fixed_payment_amount: fixed_payment_amount,
                    saving_term: saving_term,
                    term_value: term_value,
                    payment_term: payment_term,
                    term_interest_compound: term_interest_compound,
                    interest_rate: interest_rate,
                    interest_rate_period: interest_rate_period,
                    saving_amount: saving_amount,
                    interest_compound: interest_compound,
                    duration_interest_calculate: duration_interest_calculate,
                    payment_method: payment_method

                },
                success: function (res) {
                    $('#saving_schedule').empty();
                    $('#saving_schedule').html(res);
                }

            });
        }

        function savingProduct() {
            var saving_product_id = $('[name="saving_product_id"]').val();
            $.ajax({
                type: 'GET',
                url: '{{url('api/get-data-saving-product')}}',
                async: false,
                data: {
                    saving_product_id: saving_product_id,

                },
                success: function (res) {

                   // $('[name="plan_type"]').val(res.plan_type);
                   $('[name="saving_type"]').val(res.saving_type).trigger('change');
                   $('[name="expectation_amount"]').val(res.expectation_amount);
                   $('[name="fixed_payment_amount"]').val(res.fixed_payment_amount);
                   $('[name="saving_term"]').val(res.saving_term).trigger('change');
                   $('[name="term_value"]').val(res.term_value);
                   $('[name="payment_term"]').val(res.payment_term);
                   $('[name="term_interest_compound"]').val(res.term_interest_compound).trigger('change');
                   $('[name="interest_rate"]').val(res.interest_rate);
                   $('[name="interest_rate_period"]').val(res.interest_rate_period).trigger('change');
                   $('[name="duration_interest_calculate"]').val(res.duration_interest_calculate).trigger('change');
                   $('[name="interest_compound"]').val(res.interest_compound);
                   $('[name="minimum_balance_amount"]').val(res.minimum_balance_amount);
                   $('[name="minimum_required_saving_duration"]').val(res.minimum_required_saving_duration);
                   $('[name="allowed_day_to_cal_saving_start"]').val(res.allowed_day_to_cal_saving_start);
                   $('[name="allowed_day_to_cal_saving_end"]').val(res.allowed_day_to_cal_saving_end);
                   $('[name="saving_amount"]').val(res.saving_amount);
                   change_type();

                }

            });
        }
    </script>
@endpush
