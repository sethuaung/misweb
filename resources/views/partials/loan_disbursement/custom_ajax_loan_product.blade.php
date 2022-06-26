<?php
      $id  =  isset($id)?$id:0;

     $loan_de= \App\Models\Loan::where('id',$id)->first();
     $product=\App\Models\LoanProduct::find(optional($loan_de)->loan_production_id);
     $join_group = optional($product)->join_group;


?>
@push('crud_fields_scripts')
    <script>
        $(function () {
            @if($join_group=='Yes')
                $('.group_loan').show();
            @elseif($join_group=='No')
                $('.group_loan').hide();


            @else
                $('.group_loan').hide();


            @endif

            $('body').on('change', '[name="loan_production_id"]', function () {

                var lp_id = $('[name="loan_production_id"]').val();
              
                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-loan-product/')}}'+ '/' + lp_id,
                    data: {
                        g_id: lp_id,
                    },
                    success: function (res) {

                        $('[name="loan_amount"]').val(res.principal_default);
                        $('[name="interest_rate"]').val(res.interest_rate_default);

                        $('[name="loan_term"]').val(res.loan_term);
                        $('span[id*="select2-loan_term"]').html(res.loan_term);

                        $('[name="loan_term_value"]').val(res.loan_term_value);

                        $('[name="repayment_term"]').val(res.repayment_term);
                        $('span[id*="select2-repayment_term"]').html(res.repayment_term);

                        $('[name="interest_rate_period"]').val(res.interest_rate_period);
                        $('span[id*="select2-interest_rate_period"]').html(res.interest_rate_period);

                        $('[name="interest_rate_min"]').val(res.interest_rate_min);
                        $('[name="interest_rate_max"]').val(res.interest_rate_max);
                        $('[name="principal_min"]').val(res.principal_min);
                        $('[name="principal_max"]').val(res.principal_max);




                        if(res.join_group=='Yes'){
                            $('.group_loan').show();
                        }
                        else {
                            $('.group_loan').hide();
                        }

                        get_payment_schedule();
                        get_service();
                    }
                });

            });

            $('body').on('change', '[name="product_id"]', function () {

                var product_id = $('[name="product_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-price-item/')}}',
                    data: {
                        product_id: product_id,
                    },
                    success: function (res) {
                        console.log(res);
                        if (res != null) {
                            if (res.qty > 0) {
                                if (res.price > 0) {
                                    $('[name="loan_amount"]').val(res.price);
                                    get_payment_schedule();
                                    get_service();
                                }
                            } else {
                                new PNotify({
                                    title: (""),
                                    text: "Product out of stock",
                                    type: "danger",
                                    icon: false
                                });
                                $('[name="product_id"]').empty();
                            }
                        }
                    }
                });

            });
            $('[name="loan_amount"]').on('keyup',function () {
                get_payment_schedule();
            });

            $('[name="interest_rate"]').on('keyup',function () {
                get_payment_schedule();
            });
            $('[name="loan_term_value"]').on('keyup',function () {
                get_payment_schedule();
            });
            $('[name="loan_term"]').on('change',function () {
                get_payment_schedule();
            });
            $('[name="repayment_term"]').on('change',function () {
                var repayment = $(this).val();
                var date = $('[name="loan_application_date"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-first-date-payment')}}',
                    data: {
                        date: date,
                        repayment: repayment,
                    },
                    success: function (res) {
                        // console.log(res);
                        $('[name="first_installment_date"]').val(res);
                        $('#my-first_installment_date').val(res);
                    }

                });
                get_payment_schedule();
            });
            $('[name="interest_rate_period"]').on('change',function () {
                get_payment_schedule();
            });
        });

        $(function () {

            $('form').on('submit',function(e){
              //  alert('kjdslkfhdsklfjdlkshfk');
                var error = 0;

                var loan_amount = $('[name="loan_amount"]').val() - 0;
                var principal_min = $('[name="principal_min"]').val() - 0;
                var principal_max = $('[name="principal_max"]').val() - 0;
                var interest_rate_min = $('[name="interest_rate_min"]').val() - 0;
                var interest_rate_max = $('[name="interest_rate_max"]').val() - 0;
                var interest_rate = $('[name="interest_rate"]').val() - 0;

                // alert(principal_max);

                if(loan_amount > principal_max || loan_amount < principal_min){
                    error = 1;
                    new PNotify({
                        title: ("Amount out range"),
                        text: "Please following on loan production",
                        type: "warning"
                    });
                }

                if(interest_rate >interest_rate_max || interest_rate < interest_rate_min){
                    error = 1;
                    new PNotify({
                        title: ("Interest rate out of rang"),
                        text: "Please following on loan production",
                        type: "warning"
                    });
                }


                if(error>0){
                    e.preventDefault();
                    $('button').prop("disabled", false);
                    e.preventDefault();
                    return false;
                }
            });

        });



        function get_payment_schedule() {

            var date = $('[name="loan_application_date"]').val();

            var first_date_payment =  $('[name="first_installment_date"]').val();
            var loan_product_id = $('[name="loan_production_id"]').val();
            var principal_amount = $('[name="loan_amount"]').val();
            var loan_duration = $('[name="loan_term_value"]').val();
            var loan_duration_unit = $('[name="loan_term"]').val();
            var repayment_cycle = $('[name="repayment_term"]').val();
            var loan_interest = $('[name="interest_rate"]').val();
            var loan_interest_unit = $('[name="interest_rate_period"]').val();

            $.ajax({
                type: 'GET',
                url: '{{url('api/get-payment-schedule')}}',
                data: {
                    date: date,
                    first_date_payment: first_date_payment,
                    loan_product_id: loan_product_id,
                    principal_amount: principal_amount,
                    loan_duration: loan_duration,
                    loan_duration_unit: loan_duration_unit,
                    repayment_cycle: repayment_cycle,
                    loan_interest: loan_interest,
                    loan_interest_unit: loan_interest_unit,
                },
                success: function (res) {
                    $('.payment-schedule').empty();
                    $('.payment-schedule').append(res);
                }

            });
        }

        function loan_application_date_change(d) {
            get_payment_schedule();
        }

        function first_installment_date_change(d) {
            get_payment_schedule();
        }
        function get_service() {
            var loan_product_id = $('[name="loan_production_id"]').val();
            $.ajax({
                type: 'GET',
                url: '{{url('api/get-charge-service')}}',
                data: {
                    loan_product_id: loan_product_id,

                },
                success: function (res) {
                    $('#service-list2').empty();
                    $('#service-list').empty();
                    $('#service-list').html(res);
                }

            });
        }

        function get_se() {
            var loan_product_id = $('[name="loan_production_id"]').val();
            $.ajax({
                type: 'GET',
                url: '{{url('api/get-charge-service')}}',
                data: {
                    loan_product_id: loan_product_id,

                },
                success: function (res) {
                    $('#service-list').empty();
                    $('#service-list').html(res);
                }

            });
        }

    </script>
@endpush
