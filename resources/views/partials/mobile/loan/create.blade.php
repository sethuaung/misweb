<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">


    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    {{--<title>--}}
    {{--Add loan :: loan Admin--}}
    {{--</title>--}}
    <?php
    $base = asset('/');
    ?>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{$base}}vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{$base}}vendor/adminlte/bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{$base}}vendor/adminlte/plugins/ionicons/css/ionicons.min.css">

    <link rel="stylesheet" href="{{$base}}vendor/adminlte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{$base}}vendor/adminlte/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="{{$base}}vendor/adminlte/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{$base}}vendor/backpack/pnotify/pnotify.custom.min.css">


    <link rel="stylesheet" href="{{$base}}vendor/adminlte/dist/fonts/sans-pro.css">
    <link rel="stylesheet" href="{{$base}}vendor/adminlte/dist/fonts/moul.css">


    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{$base}}vendor/backpack/base/backpack.base.css?v=3">
    <link rel="stylesheet" href="{{$base}}vendor/backpack/base/backpack.bold.css">


    <link rel="stylesheet" href="{{$base}}vendor/backpack/crud/css/crud.css">
    <link rel="stylesheet" href="{{$base}}vendor/backpack/crud/css/form.css">
    <link rel="stylesheet" href="{{$base}}vendor/backpack/crud/css/create.css">

    <link rel="stylesheet" href="{{$base}}css/custom.css">
    <!-- CRUD FORM CONTENT - crud_fields_styles stack -->
    <style>
        .nav-tabs-custom {
            box-shadow: none;
        }

        .nav-tabs-custom > .nav-tabs.nav-stacked > li {
            margin-right: 0;
        }

        .tab-pane .form-group h1:first-child,
        .tab-pane .form-group h2:first-child,
        .tab-pane .form-group h3:first-child {
            margin-top: 0;
        }


    </style>

    <link rel="stylesheet"
          href="{{$base}}vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
    <!-- include select2 css-->
    <link href="{{$base}}vendor/adminlte/bower_components/select2/dist/css/select2.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{$base}}vendor/adminlte/plugins/select2/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>
    <style>

        .table-data {
            width: 100%;
        }

        .table-data, .table-data th, .table-data td {
            border-collapse: collapse;
            border: 1px solid #a8a8a8;
        }

        .table-data th {
            text-align: center;
            padding: 5px;
        }

        .table-data td {
            padding: 5px;
        }

        .table-data tbody > tr:nth-child(odd) {
            background-color: #f4f4f4;
            color: #606060;
        }

       
    </style>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition   sidebar-mini" style="font-family: 'Content', Titillium Web;">
<script type="text/javascript">
    /* Recover sidebar state */
    (function () {
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            var body = document.getElementsByTagName('body')[0];
            body.className = body.className + ' sidebar-collapse';
        }
    })();
</script>
<!-- Site wrapper -->
<div class="wrapper">
    <?php

    $user_id = isset($user_id) ? $user_id : 0;
    $loan_id = request()->loan_id != null ? request()->loan_id : 0;

    $u_branch = \App\Models\UserBranch::where('user_id', $user_id)->first();

    $_REQUEST['branch_id'] = optional($u_branch)->branch_id;


    ?>
        <div class="row" style="margin-top:10px;margin-left: 1px;">
            <!-- Default box -->
            <form method="post" action="{{url('api_m/store-loan-view')}}">
                {{--<input type="hidden" name="_token" value="K21BtJtjUL5WoFJkgNNrMqcanCQtRRg5HzbQDOYL">--}}
                {{csrf_field()}}
                <input type="hidden" name="branch_table_id" value="{{$_REQUEST['branch_id']}}">
                <input type="hidden" name="user_hidden_id" value="{{$user_id}}">
                <div class="container">
                    <div class="row display-flex-wrap">
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        {{--<input type="hidden" name="http_referrer" value={{$base}}admin/active-member-client>--}}

                        <div class="tab-container col-xs-12">
                            <div class="nav-tabs-custom" id="form_tabs">
                                <ul class="nav nav-tabs" role="tablist" style="font-size: 14px">
                                    <li role="presentation" class="active"><a href="#tab_client"
                                                                              aria-controls="tab_client"
                                                                              role="tab" tab_name="client"
                                                                              data-toggle="tab"
                                                                              class="tab_toggler">Client</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_account"
                                                                        aria-controls="tab_account" role="tab"
                                                                        tab_name="account" data-toggle="tab"
                                                                        class="tab_toggler">Account</a></li>
                                    <li role="presentation" class=""><a href="#tab_photo"
                                                                        aria-controls="tab_photo" role="tab"
                                                                        tab_name="photo" data-toggle="tab"
                                                                        class="tab_toggler">Photo</a></li>
                                    <li role="presentation" class=""><a href="#tab_paymentschedule"
                                                                        aria-controls="tab_paymentschedule"
                                                                        role="tab" tab_name="paymentschedule"
                                                                        data-toggle="tab" class="tab_toggler">Schedule</a>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <div class="tab-content box col-md-12">
                            <div role="tabpanel" class="tab-pane active" id="tab_client">

                                <input type="hidden" name="created_by" value="{{$user_id}}"
                                       class="form-control">

                                <div class="form-group col-md-3 client_id  required">
                                    <label>Client ID</label>
                                    <select name="client_id" style="width: 100%" id="select2_ajax_client_id"
                                            class="form-control "></select>
                                </div>
                                <div class="form-group col-md-3 ">
                                    <label>Client nrc</label>
                                    <input readonly type="text" name="client_nrc_number" value=""
                                           class="form-control ">
                                </div>

                                <div class="form-group col-md-3 ">
                                    <label>Client Name</label>
                                    <input readonly type="text" name="client_name" value=""
                                           class="form-control ">
                                </div>

                                <div class="form-group col-md-3 ">
                                    <label>Client phone</label>
                                    <input readonly type="text" name="client_phone" value=""
                                           class="form-control ">
                                </div>

                                <div class="form-group col-md-3 ">
                                    <label>Saving amount</label>
                                    <input readonly type="text" name="available_balance" value=""
                                           class="form-control ">
                                </div>

                                <div class="form-group col-md-3 ">
                                    <label>You are a group leader</label>
                                    <select name="you_are_a_group_leader" class="form-control ">
                                        <option value="Yes" selected>Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>You are a center leader</label>
                                    <select name="you_are_a_center_leader" class="form-control ">
                                        <option value="Yes" selected>Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3 guarantor ">
                                    <label>Guarantor</label>
                                    <select name="guarantor_id" style="width: 100%"
                                            id="select2_ajax_guarantor_id"
                                            class="form-control "></select>
                                    {{--<div class="input-group">--}}
                                    {{--<div class="input-group-addon">--}}
                                    {{--<a href="" data-remote="false" data-toggle="modal" data-target="#show-create-guarantor"><span class="glyphicon glyphicon-plus"></span></a>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                                <div class="modal fade" id="show-create-guarantor" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-x">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span
                                                            aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Guarantor</h4>
                                            </div>
                                            <div class="modal-body">
                                                <iframe width="100%" height="315" src="" frameborder="0"
                                                        allowfullscreen></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- include field specific select2 js-->


                                <!-- load the view from type and view_namespace attribute if set -->

                                <!-- text input -->
                                <div class="form-group col-md-3 g_nrc_number ">
                                    <label>Guarantor NRC No</label>
                                    <input readonly type="text" name="g_nrc_number" value=""
                                           class="form-control ">
                                </div>

                                <div class="form-group col-md-3 ">
                                    <label>Guarantor Name</label>
                                    <input readonly type="text" name="g_name" value=""
                                           class="form-control ">
                                </div>
                                <div class="form-group col-md-3 ">
                                    <label>Guarantor ID</label>
                                    <input readonly type="text" name="g_id" value=""
                                           class="form-control ">
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab_account">
                                <div class="form-group col-md-4  required">
                                    <label>Loan Number</label>
                                    <input type="text" name="disbursement_number"
                                           value="{{\App\Models\Loan::getSeqRef('loan')}}" class="form-control">
                                </div>

                                <?php
                                $arr = [];
                                $user_branch = \App\Models\UserBranch::where('user_id', $user_id)->get();
                                //dd($user_branch);
                                if ($user_branch != null) {
                                    foreach ($user_branch as $r) {
                                        $arr[$r->branch_id] = $r->branch_id;
                                    }
                                }

                                ?>
                                <div class="form-group col-md-4  required">
                                    <label>Branch</label>
                                    <select name="branch_id" style="width: 100%" id="select2_ajax_branch_id"
                                            class="form-control">
                                        <option value="" selected>
                                            Select a branch
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 ">
                                    <label>Center Name</label>
                                    <select name="center_leader_id" style="width: 100%"
                                            id="select2_ajax_center_leader_id"
                                            class="form-control "></select>
                                </div>
                                <div class="form-group col-md-4  required">
                                    <label>Loan Officer Name</label>
                                    <select name="loan_officer_id" style="width: 100%"
                                            id="select2_ajax_loan_officer_id"
                                            class="form-control "></select>
                                </div>

                                <div class="form-group col-md-4 loan_product  required">
                                    <label>Loan Product</label>
                                    <select name="loan_production_id" style="width: 100%"
                                            id="select2_ajax_loan_production_id"
                                            class="form-control "></select>
                                </div>

                                <div class="form-group col-md-4  required">
                                    <label>Loan application date</label>
                                    <div class="input-group date">
                                        <input type="text"
                                               class="form-control  loan_application_date "
                                               name="loan_application_date" value="{{date('Y-m-d')}}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>

                                </div>


                                <div class="form-group col-md-4  required">
                                    <label>First installment date</label>
                                    <div class="input-group date">
                                        <input type="text"
                                               class="form-control  first_installment_date "
                                               name="first_installment_date" value="{{date('Y-m-d')}}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-4  required">
                                    <label for="loan_amount">Loan Amount</label>
                                    <input number="number" type="text" name="loan_amount" id="loan_amount"
                                           value="" class="form-control ">
                                </div>
                                <!-- load the view from type and view_namespace attribute if set -->

                                <!-- number input -->
                                <div class="form-group col-md-4  required">
                                    <label for="interest_rate">Interest rate</label>
                                    <input number="number" type="text" name="interest_rate" id="interest_rate"
                                           value="" class="form-control ">

                                </div>

                                <div class="form-group col-md-4  required">
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
                                <div class="form-group col-md-4  required">
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
                                    <input type="number" name="loan_term_value" id="loan_term_value" value=""
                                           class="form-control ">
                                </div>
                                <div class="form-group col-md-4  required">
                                    <label>Repayment term</label>
                                    <select name="repayment_term" class="form-control ">
                                        <option value="Monthly">Monthly</option>
                                        <option value="Daily">Daily</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Two-Weeks">Two-Weeks</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                </div>


                                <?php
                                $currency = \App\Models\Currency::all();
                                ?>
                                <div class="form-group col-md-4  required">
                                    <label>Currency</label>
                                    <select name="currency_id" class="form-control ">
                                        @if($currency != null)
                                            @foreach($currency as $c)
                                                <option value="{{$c->id}}">{{$c->currency_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>


                                <?php
                                $train = \App\Models\TransactionType::all();
                                ?>

                                <div class="form-group col-md-4  required">
                                    <label>Transaction Type</label>
                                    <select name="transaction_type_id" class="form-control ">
                                        @if($train != null)
                                            @foreach($train as $t)
                                                <option value="{{$t->id}}">{{$t->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3 group_loan ">
                                    <label>Group ID</label>
                                    <div class="input-group">
                                        <select name="group_loan_id" style="width: 100%"
                                                id="select2_ajax_group_loan_id" class="form-control ">
                                        </select>
                                        {{--<div class="input-group-addon">--}}
                                        {{--<a href="" data-remote="false" data-toggle="modal" id="modal-show" data-target="#show-create-group"><span class="glyphicon glyphicon-plus"></span></a>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>

                                <div id="modal-pop">

                                </div>
                                <div class="modal fade" id="show-create-group" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-x">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span
                                                            aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Group</h4>
                                            </div>
                                            <div class="modal-body">
                                                <iframe width="100%" height="315" src="" frameborder="0"
                                                        allowfullscreen></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab_photo">
                                <div class="form-group col-md-6 ">
                                    <div class="col-md-6">
                                        <label>Guarantor Photo</label>
                                        <div>
                                            <img width="300" height="300" class="g_image"
                                                 src="{{$base}}No_Image_Available.jpg"/>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group col-md-6 ">
                                    <div class="col-md-6">
                                        <label>Client Photo</label>
                                        <div>
                                            <img width="300" height="300" class="c_image"
                                                 src="{{$base}}No_Image_Available.jpg"/>
                                        </div>
                                    </div>

                                </div>

                                <div class="hidden ">
                                    <input type="hidden" class="interest_rate_min" value=""
                                           class="form-control">
                                </div>
                                <div class="hidden">
                                    <input type="hidden" class="interest_rate_max" value=""
                                           class="form-control">
                                </div>
                                <div class="hidden ">
                                    <input type="hidden" class="principal_min" value="" class="form-control">
                                </div>
                                <div class="hidden ">
                                    <input type="hidden" class="principal_max" value="" class="form-control">
                                </div>

                            </div>
                            <div role="tabpanel" class="tab-pane" id="tab_paymentschedule">

                                <div class="form-group col-md-12 ">
                                    <table class="table-data" id="table-data"
                                           style="margin-top: 20px;font-size: 10px">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Date</th>
                                            <th colspan="3">Repayment</th>
                                            <th rowspan="2">Balance</th>
                                        </tr>
                                        <tr>
                                            <th>Principal</th>
                                            <th>Interest</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody class="payment-schedule">
                                        <tr>
                                            <td colspan="2" style="text-align: right;"><b>Total: </b></td>
                                            <td style="text-align: right;"><b>0</b></td>
                                            <td style="text-align: right;"><b>0</b></td>
                                            <td style="text-align: right;"><b>0</b></td>
                                            <td style="text-align: right;"></td>
                                        </tr>
                                        </tbody>
                                    </table>


                                </div>

                            </div>

                        </div>
                        <input type="hidden" name="current_tab" value="client"/>


                    </div><!-- /.box-body -->

                    <div style="overflow-x:auto;">
                        <div id="service-list">

                        </div>
                        <div id="service-list2">

                        </div>
                    </div>

                    <div class="">
                        <button type="submit" class="btn btn-success">
                            <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
                            <span data-value="save_and_back">Save </span>
                        </button>
                    </div><!-- /.box-footer-->

                </div><!-- /.box -->
            </form>


        </div>

    <!-- /.content-wrapper -->
    <footer class="main-footer text-sm clearfix">
    </footer>
</div>
<!-- ./wrapper -->
<!-- ./wrapper -->
<!-- jQuery 3.3.1 -->
<script src="{{$base}}vendor/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{$base}}vendor/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="{{$base}}vendor/adminlte/plugins/pace/pace.min.js"></script>
<script src="{{$base}}vendor/adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="{{$base}}vendor/adminlte/dist/js/adminlte.js"></script>
<!-- page script -->
<script type="text/javascript">
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function () {
        Pace.restart();
    });

    // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
    location.hash && activeTab && activeTab.tab('show');
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        location.hash = e.target.hash.replace("#tab_", "#");
    });
</script>

<script src="{{$base}}vendor/backpack/pnotify/pnotify.custom.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

        PNotify.prototype.options.styling = "bootstrap3";
        PNotify.prototype.options.styling = "fontawesome";

    });
</script>
<script src="{{$base}}vendor/backpack/crud/js/crud.js"></script>
<script src="{{$base}}vendor/backpack/crud/js/form.js"></script>
<script src="{{$base}}vendor/backpack/crud/js/create.js"></script>
<script src="{{$base}}vendor/adminlte/bower_components/select2/dist/js/select2.min.js"></script>
<script src="{{$base}}vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        // trigger select2 for each untriggered select2 box
        $("#select2_ajax_client_id").each(function (i, obj) {
            var form = $(obj).closest('form');

            if (!$(obj).hasClass("select2-hidden-accessible")) {
                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select a client code",
                    minimumInputLength: "0",


                    allowClear: true,

                    ajax: {
                        url: "{{url('api')}}/get-client",
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                //form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {

                                    return {
                                        text: item["client_number"],
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
                    });

            }
        });
        $("#select2_ajax_guarantor_id").each(function (i, obj) {
            if (!$(obj).hasClass("select2-hidden-accessible")) {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select a guarantor",
                    minimumInputLength: "0",


                    allowClear: true,
                    ajax: {
                        url: "{{url('api')}}/get-guarantor",
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "full_name_mm";
                                    return {
                                        text: item['full_name_en'],
                                        id: item["id"]
                                    }
                                }),
                                more: data.current_page < data.last_page
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
                        url: "{{url('api')}}/get-branch",
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
                        url: "{{url('api')}}/get-center-leader-name",
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
        $("#select2_ajax_loan_officer_id").each(function (i, obj) {
            var form = $(obj).closest('form');

            if (!$(obj).hasClass("select2-hidden-accessible")) {
                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select a officer",
                    minimumInputLength: "0",


                    allowClear: true,
                    ajax: {
                        url: "{{url('api')}}/get-user",
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                center_id: $('[name="center_leader_id"]').val(),
                                form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "name";
                                    return {
                                        text: item['user_code'] + '-' + item[textField],
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
                        url: "{{url('api')}}/get-loan-product",
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
        $("#select2_ajax_group_loan_id").each(function (i, obj) {
            var form = $(obj).closest('form');


            if (!$(obj).hasClass("select2-hidden-accessible")) {
                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select Group Loan",
                    minimumInputLength: "0",


                    allowClear: true,
                    ajax: {

                        url: "{{url('api')}}/get-group-loan",
                        type: 'GET',
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                center_leader_id: $('[name="center_leader_id"]').val(),
                                page: params.page, // pagination
                                form: form.serializeArray()  // all other form inputs
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            var result = {
                                results: $.map(data.data, function (item) {
                                    textField = "group_code";
                                    textField1 = "group_name";
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
        $('[name="you_are_a_group_leader"]').select2({
            theme: 'bootstrap',
            multiple: false,
            //  placeholder: "Select Group Loan",
        });

        $('[name="you_are_a_center_leader"]').select2({
            theme: 'bootstrap',
            multiple: false,
            //  placeholder: "Select Group Loan",
        });

        $('[name="transaction_type_id"]').select2({
            theme: 'bootstrap',
            multiple: false,
            //  placeholder: "Select Group Loan",
        });

        $('[name="currency_id"]').select2({
            theme: 'bootstrap',
            multiple: false,
            //  placeholder: "Select Group Loan",
        });

        $('[name="repayment_term"]').select2({
            theme: 'bootstrap',
            multiple: false,
            //  placeholder: "Select Group Loan",
        });

        $('[name="loan_term"]').select2({
            theme: 'bootstrap',
            multiple: false,
            //  placeholder: "Select Group Loan",
        });

        $('[name="interest_rate_period"]').select2({
            theme: 'bootstrap',
            multiple: false,
            //  placeholder: "Select Group Loan",
        });


    });
</script>

<script>
    $(function () {
        $('.group_loan').hide();

        $(".phpdebugbar").hide();
        // $(".main-footer").hide();
        $("footer").remove();

        @if($loan_id > 0)
        new PNotify({
            // title: ("This Loan"),
            text: "This Loan save successfully",
            type: "success"
        });
        @endif

        $('body').on('change', '[name="loan_production_id"]', function () {
            //console.log("success");
            var lp_id = $('[name="loan_production_id"]').val();

            $.ajax({
                type: 'GET',
                url: '{{url('api')}}/get-loan-product' + '/' + lp_id,
                data: {
                    g_id: lp_id,
                },
                success: function (res) {
                    //console.log(res.compulsory_id);

                    $('[name="loan_amount"]').val(res.principal_default);
                    $('[name="interest_rate"]').val(res.interest_rate_default);


                    $('[name="loan_term"]').val(res.loan_term);
                    $('[name="loan_term_value"]').val(res.loan_term_value);
                    $('[name="repayment_term"]').val(res.repayment_term);
                    $('[name="interest_rate_period"]').val(res.interest_rate_period);


                    var loan_term = '<option value=' + res.loan_term + '>' + res.loan_term + '</option>';
                    var loan_term_value = '<option value=' + res.loan_term_value + '>' + res.loan_term_value + '</option>';
                    var repayment_term = '<option value=' + res.repayment_term + '>' + res.repayment_term + '</option>';
                    var interest_rate_period = '<option value=' + res.interest_rate_period + '>' + res.interest_rate_period + '</option>';


                    if (res.loan_term != null) {
                        $('[name="loan_term"]').html(loan_term);
                    }


                    if (res.loan_term_value != null) {
                        $('[name="loan_term_value"]').html(loan_term_value);
                    }

                    if (res.repayment_term != null) {
                        $('[name="repayment_term"]').html(repayment_term);
                    }

                    if (res.interest_rate_period != null) {
                        $('[name="interest_rate_period"]').html(interest_rate_period);
                    }

                    $('[name="interest_rate_min"]').val(res.interest_rate_min);
                    $('[name="interest_rate_max"]').val(res.interest_rate_max);
                    $('[name="principal_min"]').val(res.principal_min);
                    $('[name="principal_max"]').val(res.principal_max);


                    if (res.join_group == 'Yes') {
                        $('.group_loan').show();
                    } else {
                        $('.group_loan').hide();
                    }

                    get_payment_schedule();
                    get_service();
                }
            });

        });
        $('[name="loan_amount"]').on('keyup', function () {
            get_payment_schedule();
        });

        $('[name="interest_rate"]').on('keyup', function () {
            get_payment_schedule();
        });
        $('[name="loan_term_value"]').on('keyup', function () {
            get_payment_schedule();
        });
        $('[name="loan_term"]').on('change', function () {
            get_payment_schedule();
        });
        $('[name="first_installment_date"]').on('change', function () {
            get_payment_schedule();
        });
        $('[name="repayment_term"]').on('change', function () {
            var repayment = $(this).val();
            var date = $('[name="loan_application_date"]').val();

            $.ajax({
                type: 'GET',
                url: '{{url('api')}}/get-first-date-payment',
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
        $('[name="interest_rate_period"]').on('change', function () {
            get_payment_schedule();
        });
    });

    $(function () {

        $('form').on('submit', function (e) {
            //  alert('kjdslkfhdsklfjdlkshfk');
            var error = 0;

            var loan_amount = $('[name="loan_amount"]').val() - 0;
            var principal_min = $('[name="principal_min"]').val() - 0;
            var principal_max = $('[name="principal_max"]').val() - 0;
            var interest_rate_min = $('[name="interest_rate_min"]').val() - 0;
            var interest_rate_max = $('[name="interest_rate_max"]').val() - 0;
            var interest_rate = $('[name="interest_rate"]').val() - 0;

            // alert(principal_max);

            if (loan_amount > principal_max || loan_amount < principal_min) {
                error = 1;
                new PNotify({
                    title: ("Amount out range"),
                    text: "Please following on loan production",
                    type: "warning"
                });
            }

            if (interest_rate > interest_rate_max || interest_rate < interest_rate_min) {
                error = 1;
                new PNotify({
                    title: ("Interest rate out of rang"),
                    text: "Please following on loan production",
                    type: "warning"
                });
            }


            if (error > 0) {
                e.preventDefault();
                $('button').prop("disabled", false);
                e.preventDefault();
                return false;
            }
        });

    });


    function get_payment_schedule() {

        var date = $('[name="loan_application_date"]').val();

        var first_date_payment = $('[name="first_installment_date"]').val();
        var loan_product_id = $('[name="loan_production_id"]').val();
        var principal_amount = $('[name="loan_amount"]').val();
        var loan_duration = $('[name="loan_term_value"]').val();
        var loan_duration_unit = $('[name="loan_term"]').val();
        var repayment_cycle = $('[name="repayment_term"]').val();
        var loan_interest = $('[name="interest_rate"]').val();
        var loan_interest_unit = $('[name="interest_rate_period"]').val();

        $.ajax({
            type: 'GET',
            url: '{{url('api')}}/get-payment-schedule',
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
                branch_id : '{{$_REQUEST['branch_id']}}'
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
            url: '{{url('api')}}/get-charge-service',
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
            url: '{{url('api')}}/get-charge-service',
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
<script>
    $(function () {
        $('body').on('change', '.guarantor', function () {

            var g_id = $('[name="guarantor_id"]').val();

            $.ajax({
                type: 'GET',
                url: '{{url('api')}}/get-guarantor' + '/' + g_id,
                data: {
                    g_id: g_id,
                },
                success: function (res) {
                    $('[name="g_nrc_number"]').val(res.nrc_number);
                    $('[name="g_name"]').val(res.full_name_mm);
                    $('[name="g_id"]').val(res.id);
                    if (res.photo) {
                        $('.g_image').prop('src', '{{$base}}' + '/' + res.photo);
                    }

                }

            });


        });

        $('body').on('change', '.guarantor2', function () {

            var g_id = $('[name="guarantor2_id"]').val();

            $.ajax({
                type: 'GET',
                url: '{{url('api')}}/get-guarantor' + '/' + g_id,
                data: {
                    g_id: g_id,
                },
                success: function (res) {
                    $('[name="g_nrc_number2"]').val(res.nrc_number);
                    $('[name="g_name2"]').val(res.full_name_mm);
                    $('[name="g_id2"]').val(res.id);

                }

            });


        });
    });

    $(function () {
        $('[name="guarantor_id"]').trigger('change');
        //$('[name="client_id"]').trigger('change');
        $('[name="guarantor2_id"]').trigger('change');
    });
</script>
<script>
    $(function () {

        $('body').on('change', '.client_id', function () {

            var c_id = $('[name="client_id"]').val();

            $.ajax({
                type: 'GET',
                url: '{{url('api')}}/get-client-g' + '/' + c_id,
                async: false,
                data: {
                    g_id: c_id,
                },
                success: function (res) {
                    // console.log(res);
                    var html = '<option value=' + res.loan_officer_id + '>' + res.loan_officer_name +
                        '</option>';

                    var you_are_a_center_leader = '<option value=' + res.you_are_a_center_leader + '>' + res.you_are_a_center_leader +
                        '</option>';

                    var you_are_a_group_leader = '<option value=' + res.you_are_a_group_leader + '>' + res.you_are_a_group_leader +
                        '</option>';

                    var center = '<option value=' + res.center_id + '>' + res.center_name +
                        '</option>';

                    var branch = '<option value=' + res.branch_id + '>' + res.branch_name +
                        '</option>';

                    if (res.you_are_a_center_leader != null) {
                        $('[name="you_are_a_center_leader"]').html(you_are_a_center_leader);
                    }

                    if (res.you_are_a_group_leader != null) {
                        $('[name="you_are_a_group_leader"]').html(you_are_a_group_leader);
                    }


                    $('[name="client_nrc_number"]').val(res.nrc_number);
                    $('[name="client_phone"]').val(res.primary_phone_number);
                    $('[name="client_name"]').val(res.name_other);

                    if (res.loan_officer_id > 0) {
                        $('[name="loan_officer_id"]').html(html);
                    }
                    if (res.center_id > 0) {
                        $('[name="center_leader_id"]').html(center);
                    }
                    if (res.branch_id > 0) {
                        $('[name="branch_id"]').html(branch);
                    }
                    $('[name="available_balance"]').val(res.saving_amount);
                    if (res.photo_of_client) {
                        $('.c_image').prop('src', '{{$base}}' + '/' + res.photo_of_client);//group_loan_id

                        // if(res.group_id >0){
                        //     $('[name="group_loan_id"]').html('<option value="'+res.group_id+'">'+res.group_code+'-'+res.group_name+'</option>');
                        //     $('[name="group_loan_id"]').trigger('change');
                        // }

                    }


                }

            });


        });
    });

    $(function () {
        $('[name="client_id"]').trigger("change");
        $('[name="branch_id"]').trigger("change");


        var branch = '<option value=>' + '' + '</option>';

        $('[name="branch_id"]').html(branch);

    });


</script>

<script>
    jQuery('document').ready(function ($) {

        // Save button has multiple actions: save and exit, save and edit, save and new
        var saveActions = $('#saveActions'),
            crudForm = saveActions.parents('form'),
            saveActionField = $('[name="save_action"]');

        saveActions.on('click', '.dropdown-menu a', function () {
            var saveAction = $(this).data('value');
            saveActionField.val(saveAction);
            crudForm.submit();
        });

        // Ctrl+S and Cmd+S trigger Save button click
        $(document).keydown(function (e) {
            if ((e.which == '115' || e.which == '83') && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                // alert("Ctrl-s pressed");
                $("button[type=submit]").trigger('click');
                return false;
            }
            return true;
        });

        // prevent duplicate entries on double-clicking the submit form
        crudForm.submit(function (event) {
            $("button[type=submit]").prop('disabled', true);
        });

        // Place the focus on the first element in the form

        var focusField = $('form').find('input, textarea, select').not('[type="hidden"]').eq(0),

            fieldOffset = focusField.offset() ? focusField.offset().top : 0,

            scrollTolerance = $(window).height() / 2;

        focusField.trigger('focus');

        if (fieldOffset > scrollTolerance) {
            $('html, body').animate({scrollTop: (fieldOffset - 30)});
        }

        // Add inline errors to the DOM

        $("a[data-toggle='tab']").click(function () {
            currentTabName = $(this).attr('tab_name');
            $("input[name='current_tab']").val(currentTabName);
        });

    });
</script>


<script>

    /* Store sidebar state */
    $('.sidebar-toggle').click(function (event) {
        event.preventDefault();
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
        } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
        }
    });

    // Set active state on menu element
    var current_url = "{{url("admin/loandisbursement/create")}}";
    var full_url = current_url + location.search;
    var $navLinks = $("ul.sidebar-menu li a");
    // First look for an exact match including the search string
    var $curentPageLink = $navLinks.filter(
        function () {
            return $(this).attr('href') === full_url;
        }
    );
    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
            return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
            return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }

    // If not found, look for the link that starts with the url
    if (!$curentPageLink.length > 0) {
        $curentPageLink = $navLinks.filter(
            function () {
                return $(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href'));
            }
        );
    }

    $curentPageLink.parents('li').addClass('active');
</script>

<script>

    $(function () {
        $('.help-block').each(function () {
            $(this).hide();
        });

        $('[number="number"]').keypress(function (event) {
            return isNumber(event, this);
        });


        $('[number="number"]').bind('paste', function () {
            var el = this;
            setTimeout(function () {
                el.value = el.value.replace('[a-z] + [^0-9\s.]+|.(?!\d)', '');
                // el.value = el.value.replace(/[^0-9.]/g, '');
                // el.value = el.value.replace(/\D/g, '');
            }, 0);
        });


        $('body').on('keypress', '[number="number"]', function () {
            return isNumber(event, this);
        });

        // getNewNotifications();

    });

    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode;
        //console.log(charCode);
        if (
            (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
            (charCode != 37 || $(element).val().indexOf('%') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    $(function () {

        $('.change-branch-top').on('change', function () {
            var branch_id = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{url('api')}}/change-branch',
                data: {
                    branch_id: branch_id,
                },
                success: function (res) {
                    window.location.reload();
                }

            });
        });

        //$('input:textbox').val("");

    });

</script>


<script src="{{$base}}vendor/adminlte/plugins/jquery/jquery.jscroll.min.js"></script>

<script type="text/javascript">
    $('ul.pagination').hide();
    $(function () {
        $('.infinite-scroll').jscroll({
            debug: true,
            autoTrigger: true,
            loadingHtml: '<!--<img class="center-block" src="/images/loading.gif" alt="Loading..." />-->',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'ul.infinite-scroll',
            callback: function () {
                $('ul.pagination').remove();
                // $('div.jscroll-added').removeClass('menu');
                $("div.jscroll-added ul.menu>li>a").css({"padding": 0, "border-bottom": "none"});
                $("div.jscroll-added ul.infinite-scroll").css({"overflow": "hidden"});
                // window.scrollTo(0,0);
            }
        });
    });
</script>


<script>
    $(function () {
        $('.first_installment_date,.loan_application_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>


</body>
</html>
