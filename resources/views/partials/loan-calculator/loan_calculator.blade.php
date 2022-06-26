<div align="center">
    <button type="button" class="btn bg-olive margin generate">{{ _t('generate') }}</button>
    <button type="button" class="btn bg-olive margin generate" onclick="printDiv()"><i class="fa fa-print"></i></button>

</div>
<div id="DivIdToPrint">
<table class="table-data" id="table-data" style="margin-top: 20px;">
    <thead>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Date</th>
        <th colspan="4">Repayment</th>
        <th colspan="3">Balance</th>

    </tr>
    <tr>
        <th>Principal</th>
        <th>Interest</th>
        <th>Exact Interest</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody class="payment-schedule">

    </tbody>
</table>
</div>
@push('crud_fields_styles')
    <style>

        .table-data
        {
            width:100%;
        }

        .table-data,.table-data  th, .table-data  td
        {
            border-collapse:collapse;
            border: 1px solid #a8a8a8;
        }

        .table-data  th
        {
            text-align: center;
            padding: 5px;
        }

        .table-data  td
        {
            padding: 5px;
        }

        .table-data  tbody > tr:nth-child(odd)
        {
            background-color: #f4f4f4;
            color: #606060;
        }
    </style>
@endpush

@push('crud_fields_scripts')

    <script>
        $(function () {
            $('#saveActions').parent().remove();
            $('.my-back').remove();

            $('form').on('submit',function (e) {
               e.preventDefault();
            });

            $('body').on('change', '[name="loan_production_id"]', function () {

                var lp_id = $('[name="loan_production_id"]').val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('api/get-loan-product/')}}'+ '/' + lp_id,
                    data: {
                        g_id: lp_id,
                    },
                    success: function (res) {
                        //console.log(res);

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
                        $('[name="interest_method"]').val(res.interest_method);
                    }
                });

            });
            $('body').on('click', '.generate', function () {
                var date = $('[name="loan_application_date"]').val();
                //alert(date);
                var first_date_payment =  $('[name="first_installment_date"]').val();
                var lp_id = $('[name="loan_production_id"]').val();
                var principal_amount = $('[name="loan_amount"]').val();
                var loan_duration = $('[name="loan_term_value"]').val();
                var loan_duration_unit = $('[name="loan_term"]').val();
                var repayment_cycle = $('[name="repayment_term"]').val();
                var loan_interest = $('[name="interest_rate"]').val();
                var loan_interest_unit = $('[name="interest_rate_period"]').val();
                var interest_method = $('[name="interest_method"]').val();
                var loan_production_id = $('[name="loan_production_id"]').val();

                //alert(lp_id);
                //alert(loan_duration_unit);
                get_payment_schedule(date,first_date_payment,lp_id,interest_method,
                    principal_amount,loan_duration,loan_duration_unit,
                    repayment_cycle,loan_interest,loan_interest_unit,loan_production_id);
            });


        });
        function get_payment_schedule(date,first_date_payment,loan_product_id,interest_method,
                                      principal_amount,loan_duration,loan_duration_unit,
                                      repayment_cycle,loan_interest,loan_interest_unit,loan_production_id) {

            $.ajax({
                type: 'GET',
                url: '{{url('api/get-loan-calculator')}}',
                data: {
                    date: date,
                    first_date_payment: first_date_payment,
                    loan_product_id: loan_product_id,
                    interest_method: interest_method,
                    principal_amount: principal_amount,
                    loan_duration: loan_duration,
                    loan_duration_unit: loan_duration_unit,
                    repayment_cycle: repayment_cycle,
                    loan_interest: loan_interest,
                    loan_interest_unit: loan_interest_unit,
                    loan_production_id: loan_production_id
                },
                success: function (res) {
                    $('.payment-schedule').empty();
                    $('.payment-schedule').append(res);
                }

            });
        }
        function printDiv()
        {

            var DivIdToPrintPop=document.getElementById('DivIdToPrint');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write('<html>' +
                '<head>' +
                '<style>\n' +
                '    .table-data\n' +
                '    {\n' +
                '        width:100%;\n' +
                '    }\n' +
                '\n' +
                '    .table-data,.table-data  th, .table-data  td\n' +
                '    {\n' +
                '        border-collapse:collapse;\n' +
                '        border: 1px solid #a8a8a8;\n' +
                '    }\n' +
                '\n' +
                '    .table-data  th\n' +
                '    {\n' +
                '        text-align: center;\n' +
                '        padding: 5px;\n' +
                '    }\n' +
                '\n' +
                '    .table-data  td\n' +
                '    {\n' +
                '        padding: 5px;\n' +
                '    }\n' +
                '\n' +
                '    .table-data  tbody > tr:nth-child(odd)\n' +
                '    {\n' +
                '       background-color: #f4f4f4;\n' +
                '        color: #606060;\n' +
                '    }\n' +
                '</style>' +
                '</head>'+
                '<body onload="window.print()">'+DivIdToPrintPop.innerHTML+'</body>' +
                '</html>');

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }
    </script>

@endpush
