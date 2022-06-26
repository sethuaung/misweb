
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="JTmJaaKEweiwTuzFqMZakI25qz00hQ0IfvxdKHEB"/>
    <title>
        Add Transfer
    </title>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/ionicons/css/ionicons.min.css">
    {{--<link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">--}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/') }}/backpack/pnotify/pnotify.custom.min.css">
    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/adminlte/') }}/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/adminlte/') }}/plugins/select2/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
</head>
<body>

<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content">

            <div class="row display-flex-wrap">
                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                <input type="hidden" name="http_referrer"
                       value=http://mfsmm.globeso.biz/admin/loandisbursement/create>


                <div class="box col-md-12 padding-10 p-t-20">
                    <div class="form-group col-md-4  required">
                        <label>Branch</label>

                        <select name="branch_id" style="width: 100%" id="select2_ajax_branch_id" class="form-control">
                            <option value="">
                                Select a branch
                            </option>

                        </select>
                    </div>
                    <div class="form-group col-md-4  required">
                        <label>Center Name </label>
                        <select name="center_leader_id" style="width: 100%" id="select2_ajax_center_leader_id" class="form-control"></select>
                    </div>

                    <div class="form-group col-md-4 loan_product  required">
                        <label>Loan Product</label>
                        <select name="loan_production_id" style="width: 100%" id="select2_ajax_loan_production_id" class="form-control">
                        </select>
                    </div>

                    <div class="form-group col-md-4  required">
                        <label>Loan application date</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control  loan_application_date" name="loan_application_date" value="{{date('Y-m-d')}}">
                        </div>

                    </div>
                    <div class="form-group col-md-4  required">
                        <label>First installment date</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control  first_installment_date" name="first_installment_date" value="{{date('Y-m-d')}}">
                        </div>

                    </div>


                    <div class="form-group col-md-4 ">
                        <label>Interest Method</label>
                        <select name="interest_method" placeholder="Interest Method" class="form-control">
                            <option value="flat-rate">Flat Rate</option>
                            <option value="declining-rate">Decline Rate</option>
                            <option value="declining-flate-rate">Decline Flate Rate</option>
                            <option value="declining-balance-equal-installments">Decline Flate payment</option>
                            <option value="interest-only">Decline Interest only</option>
                            <option value="effective-rate">Effective Rate</option>
                            <option value="effective-flate-rate">Effective Flate Rate</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4  required">
                        <label for="loan_amount">Loan Amount</label>
                        <input number="number" type="text" name="loan_amount" id="loan_amount" value="" class="form-control">
                    </div>

                    <div class="form-group col-md-4  required">
                        <label for="interest_rate">Interest rate</label>
                        <input number="number" type="text" name="interest_rate" id="interest_rate" value="" class="form-control">
                    </div>

                    <div class="form-group col-md-4 ">
                        <label>Interest rate period</label>
                        <select name="interest_rate_period" class="form-control">
                            <option value="Monthly">Monthly</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Two-Weeks">Two-Weeks</option>
                            <option value="Four-Weeks">Four-Weeks</option>
                            <option value="Yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 ">
                        <label>Loan Term</label>
                        <select name="loan_term" class="form-control">
                            <option value="Day">Day</option>
                            <option value="Week">Week</option>
                            <option value="Two-Weeks">Two-Weeks</option>
                            <option value="Four-Weeks">Four-Weeks</option>
                            <option value="Month">Month</option>
                            <option value="Year">Year</option>
                        </select>

                    </div>

                    <div class="form-group col-md-4  required">
                        <label for="loan_term_value">Loan Term Value</label>
                        <input type="number" name="loan_term_value" id="loan_term_value" value="" class="form-control">
                    </div>
                    <div class="form-group col-md-4 ">
                        <label>Repayment term</label>
                        <select name="repayment_term" class="form-control">
                            <option value="Monthly">Monthly</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Two-Weeks">Two-Weeks</option>
                            <option value="Four-Weeks">Four-Weeks</option>
                            <option value="Yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4  required">

                        <label>Currency</label>
                        <select name="currency_id" class="form-control">
                            <option value="1">Kyats</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4  required">
                        <label>Transaction Type</label>
                        <select name="transaction_type_id" class="form-control">

                        </select>
                    </div>

                    <div class="form-group col-xs-12">
                        <div align="center" style="margin-bottom: 15px;">
                            <button type="button" class="btn btn-success" id="generate">{{ _t('generate') }}</button>
                            <button type="button " class="btn btn-success" onclick="printDiv()" style="margin-left: 15px;"><i class="fa fa-print"></i></button>
                        </div>
                        <div id="DivIdToPrint">

                            <table  class="table-data" width="100%" id="table-data">
                                <thead>
                                <tr >
                                    <th rowspan="2" style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">No</th>
                                    <th rowspan="2"style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">Date</th>
                                    {{--                                            <th colspan="2"style="text-align: center;">Repayment</th>--}}
                                    <th colspan="4"style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">Repayment</th>
                                    <th rowspan="2"style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">Balance</th>
                                </tr>
                                <tr >
                                    <th style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">Principal</th>
                                    <th style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">Interest</th>
                                    <th style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">Exact Interest</th>
                                    <th style="text-align: center;border-collapse:collapse;border: 1px solid #a8a8a8;padding: 5px;">Total</th>
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


                    </div>
                </div>


            </div><!-- /.box-body -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>

<!-- ./wrapper -->

<script src="{{ asset('vendor/adminlte/') }}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.js"></script>
<script src="{{ asset('vendor/adminlte/') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{ asset('vendor/adminlte/') }}/dist/js/adminlte.js"></script>
<script src="{{ asset('vendor/') }}/backpack/pnotify/pnotify.custom.min.js"></script>
<script src="{{ asset('vendor/') }}/backpack/crud/js/crud.js"></script>
<script src="{{ asset('vendor/') }}/backpack/crud/js/form.js"></script>
<script src="{{ asset('vendor/') }}/backpack/crud/js/create.js"></script>
<script src="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('vendor/adminlte/') }}/bower_components/select2/dist/js/select2.min.js"></script>
{{--<script src="{{asset('js')}}/number/jquery.number.min.js"></script>--}}


<script type="text/javascript">
    jQuery(document).ready(function ($) {

        PNotify.prototype.options.styling = "bootstrap3";
        PNotify.prototype.options.styling = "fontawesome";

    });
</script>

<script>
    $(function () {
        //var i = 1;
        $('.first_installment_date,.loan_application_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>

<script>
    jQuery(document).ready(function ($) {
        // trigger select2 for each untriggered select2 box
        $("#select2_ajax_center_leader_id").each(function (i, obj) {
            var form = $(obj).closest('form');

            if (!$(obj).hasClass("select2-hidden-accessible")) {
                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select a center leader name",
                    minimumInputLength: "0",


                    allowClear: true,
                    ajax: {
                        url: "{{url('api/get-center-leader-name')}}",
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                branch_id: $('[name="branch_id"]').val(),
                                form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "title";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })

                    .on('select2:unselecting', function (e) {
                        $(this).val('').trigger('change');
                        // console.log('cleared! '+$(this).val());
                        e.preventDefault();
                    })
                ;

            }
        });

    });
</script>
<script>
    jQuery(document).ready(function ($) {
        // trigger select2 for each untriggered select2 box
        $("#select2_ajax_loan_production_id").each(function (i, obj) {
            var form = $(obj).closest('form');

            if (!$(obj).hasClass("select2-hidden-accessible")) {
                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select a loan product",
                    minimumInputLength: "0",


                    allowClear: true,
                    ajax: {
                        url: "{{url('api/get-loan-product')}}",
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "name";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })

                    .on('select2:unselecting', function (e) {
                        $(this).val('').trigger('change');
                        // console.log('cleared! '+$(this).val());
                        e.preventDefault();
                    })
                ;

            }
        });

    });
</script>
<script>
    jQuery(document).ready(function ($) {
        // trigger select2 for each untriggered select2 box
        $("#select2_ajax_branch_id").each(function (i, obj) {
            var form = $(obj).closest('form');

            if (!$(obj).hasClass("select2-hidden-accessible")) {
                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select a branch",
                    minimumInputLength: "0",


                    allowClear: true,
                    ajax: {
                        url: "{{url('api/get-branch')}}",
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            console.log(data);

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "title";
                                    return {
                                        text: item[textField],
                                        id: item["id"]
                                    }
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                    },
                })

                    .on('select2:unselecting', function (e) {
                        $(this).val('').trigger('change');
                        // console.log('cleared! '+$(this).val());
                        e.preventDefault();
                    })
                ;

            }
        });

    });
</script>
<script>

    $(function () {
        $('#saveActions').parent().remove();
        $('.my-back').remove();

        $('form').on('submit',function (e) {
            e.preventDefault();
        });

        $('body').on('change', '[name="loan_production_id"]', function () {
            //console.log("nyi nyi");
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
                    $('[name="loan_term_value"]').val(res.loan_term_value);
                    $('[name="repayment_term"]').val(res.repayment_term);
                    $('[name="interest_rate_period"]').val(res.interest_rate_period);

                    $('[name="interest_rate_min"]').val(res.interest_rate_min);
                    $('[name="interest_rate_max"]').val(res.interest_rate_max);
                    $('[name="principal_min"]').val(res.principal_min);
                    $('[name="principal_max"]').val(res.principal_max);
                    $('[name="interest_method"]').val(res.interest_method);
                }
            });

        });

        $('body').on('click', '#generate', function () {
            var date = $('[name="loan_application_date"]').val();
            // alert(date);
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
            //alert(loan_duration);
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
</body>


</html>

