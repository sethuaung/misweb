<div class="service_amount">

    @if(isset($id))
        <?php
        $dis = \App\Models\DepositServiceCharge::where('loan_deposit_id', $id)->get();
        ?>
        @foreach($dis as $row)
            <div class="col-md-4">
                <input type="hidden" class="form-control " name="service_charge_id[]"
                       value="{{$row->service_charge_id}}">
                <input type="text" class="form-control s_amount" name="service_amount[]"
                       value="{{$row->service_charge_amount}}">
            </div>
        @endforeach
    @endif

</div>

@push('crud_fields_scripts')

    <style>
        .content {
            background: #ffffff;
        }
    </style>

    <script>


        $("#saveActions").remove();


        $(function () {
            $('body').on('keyup', '#compulsory_saving_amount,.s_amount', function () {
                cal_total_service_amount();
            });

            $('[name="applicant_number_id"]').on('change', function () {
                f();
            });

            f();
            cal_total_service_amount();
        });

        function f() {
            var loan_disbursement_id = $('[name="applicant_number_id"]').val();
            // alert(loan_disbursement_id);
            $.ajax({
                type: 'GET',
                url: '{{url('/api/loan-disbursement-deposit')}}',
                data: {
                    loan_disbursement_id: loan_disbursement_id,
                },
                success: function (res) {

                    //$('[name="referent_no"]').val(res.referent_no);
                    $('[name="customer_name"]').val(res.customer_name);
                    $('[name="nrc"]').val(res.nrc_number);
                    $('[name="compulsory_saving_amount"]').val(res.compulsory_amount);
                    //console.log(res);
                    var str = '';
                    $.each(res.loan_charge, function () {
                        var c = $(this);
                        var total= 0;

                        var amt_charge = c[0].amount;

                        var  total_line_charge = (c[0].charge_option == 1?amt_charge:((res.loan_amount*amt_charge)/100));

                        str += '<div class="col-md-4">\n' +
                            '    <label>'+c[0].name+'</label>\n' +
                            '    <input type="text" class="form-control s_amount" name="service_amount[]" value="'+total_line_charge+'"}}" />\n  ' +
                            '    <input type="hidden" class="form-control " name="service_charge_id[]" value="'+c[0].id+'"}}" />\n  ' +
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
            var compulsory = $('[name="compulsory_saving_amount"]').val() - 0;
            $('.s_amount').each(function () {
                var amount = $(this).val() - 0;
                total += amount;
            });
            total_amt = total + compulsory;
            $('[name="total_deposit"]').val(total_amt);
        }


    </script>


@endpush