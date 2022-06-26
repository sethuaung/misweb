<?php
$loan_id = request()->loan_id;
?>

<div class="service_amount">

    <?php

        $loan_charge = \App\Models\LoanCharge::where('loan_id',$loan_id)->where('charge_type',2)->where('status','yes')->get();
        $loan_amount = optional(\App\Models\Loan::find($loan_id))->loan_amount;

        ?>
        @if($loan_amount >0)
        @if($loan_charge != null)
            @foreach($loan_charge as $row)

                <div class="col-md-4">

                    <input type="text" class="form-control s_amount" name="service_amount[]"
                           value="{{$row->amount}}">
                </div>
            @endforeach
        @endif
        @endif

</div>

@push('crud_fields_scripts')

    <style>
      /*  .content {
            background: #ffffff;
        }*/
    </style>

    <script>


        //$("#saveActions").remove();


        $(function () {
            $('body').on('keyup', '#compulsory_saving_amount,.s_amount', function () {
                cal_total_service_amount();
            });

            $('[name="contract_id"]').on('change', function () {

                f();
            });

            f();
            cal_total_service_amount();
        });

        function f() {
            var loan_disbursement_id = $('[name="contract_id"]').val();
            $.ajax({
                type: 'GET',
                url: '{{url('/api/loan-disbursement-paid')}}',
                data: {
                    loan_disbursement_id: loan_disbursement_id,
                },
                success: function (res) {
                   // console.log(res);
                    //$('[name="reference"]').val(res.referent_no);
                    $('[name="client_name"]').val(res.customer_name);
                    $('[name="client_nrc"]').val(res.nrc_number);
                    $('[name="compulsory_saving"]').val(res.compulsory_amount);
                    $('[name="loan_amount"]').val(res.loan_amount);
                    $('[name="client_id"]').val(res.customer_id);

                    $('[name="first_payment_date"]').datepicker('setDate', res.first_installment_date);
                    $('[name="first_payment_date"]').parent().find('[data-bs-datepicker]').val(res.first_installment_date);
                    $('[name="first_payment_date"]').val(res.first_installment_date);
                    /*$('[name="first_payment_date"]').datepicker({
                        value: res.first_installment_date
                    });*/
                    var str = '';
                    $.each(res.loan_charge, function () {
                        var c = $(this);
                        var total = 0;
                        var amount = 0;
                        //console.log(c[0]);
                        if(c[0].charge_option == 2){
                            amount =  (c[0].amount*res.loan_amount)/100;
                        }else{
                            amount = c[0].amount;
                        }

                        str += '<div class="col-md-4">\n' +
                            '    <label>' + c[0].name + '</label>\n' +
                            '    <input type="text" class="form-control s_amount" name="service_amount[]" value="' + amount + '"}}" />\n  ' +
                            '    <input type="hidden" class="form-control " name="service_charge_id[]" value="' + c[0].id + '"}}" />\n  ' +
                            '    <input type="hidden" class="form-control " name="charge_id[]" value="'+c[0].charge_id+'"}}" />\n  ' +
                            '</div>';


                    });
                    $('.service_amount').html(str);
                    cal_total_service_amount();
                }

            });
        }

        function cal_total_service_amount() {
            var total = 0;
            var total_amt = 0;
            var compulsory = $('[name="compulsory_saving"]').val() - 0;
            var loan_amount = $('[name="loan_amount"]').val() - 0;

            $('.s_amount').each(function () {
                var amount = $(this).val() - 0;
                total += amount;
            });
            total_amt = loan_amount - (total + compulsory);
            $('[name="total_money_disburse"]').val(round(total_amt,2));
        }


    </script>


@endpush
