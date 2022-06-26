<?php
$rows = isset($rows) ? $rows : null;
?>
        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    {{--<title>
        {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
    </title>--}}
    {{--<link rel="stylesheet" href="{{ asset('vendor/backpack/backpack.base.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/overlays/backpack.bold.css') }}">--}}

    @yield('before_styles')
    @stack('before_styles')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte') }}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">--}}
    {{--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">--}}

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/ionicons/css/ionicons.min.css">


    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">

    {{--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">--}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/fonts/sans-pro.css">

    <!-- BackPack Base CSS -->

@yield('after_styles')
@stack('crud_fields_styles')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .right, .right input {
            text-align: right;
        }

        .p-left {
            text-align: left;
            border-right: 1px solid #ccc;
        }

        .home {
            width: 112px;
        }

        .method {
            width: 112px;
        }
    </style>
    <script>
        var supply_id = 0;
    </script>
</head>
<body class="hold-transition {{--{{ config('backpack.base.skin') }} --}}sidebar-mini">
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

    <header class="main-header">
    @section('main-header')
        <!-- Logo -->
            <a style="display:block;text-align: center;float: left; font-size: 20px; padding: 10px 0">
                <button type="button" data-toggle="pill" href="#home" class="btn btn-sm btn-default home">Vendor
                </button>
                <button type="button" data-toggle="pill" href="#menu1" class="btn btn-sm btn-default method">
                    Transaction
                </button>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                {{--<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('backpack::base.toggle_navigation') }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>--}}

                <div class="navbar-custom-menu pull-left">
                    <ul class="nav navbar-nav">
                        <!-- =================================================== -->
                        <!-- ========== Top menu items (ordered left) ========== -->
                        <!-- =================================================== -->
                        <?php

                        $rows_d = \App\Models\ApTrain::where(function ($q) {
                            $q->where('exchange_rate', 1);
                        })->sum('amount');
                        $rows_r = \App\Models\ApTrain::where(function ($q) {
                            $q->where('exchange_rate', '!=', 1);
                        })->sum('amount');
                        $total_row = $rows_d + round($rows_r / 4080);
                        $gt = 0;
                        $t = \App\Models\ApTrain::whereNotNull('exchange_rate')->get();
                        if ( $t != null){
                            foreach ( $t as $k=>$v ){
                                $amount = $v->amount; //per amount
                                $ex = $v->exchange_rate; // per exchange rate
                                $gt += $amount / $ex ;
                            }
                        }
                        $rGt = round($gt);
                      /*  dd($gt);*/
                        ?>
                        <li><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-home"></i> <span>Home</span></a>
                        </li>
                        <li><a href="javascript:void (0);"><i class="fa fa-money"></i> <span
                                        style="color: green !important;font-weight: bold">Total Balance : {{$rGt}}&nbsp;$</span></a>
                        </li>

                        <!-- ========== End of top menu left items ========== -->
                    </ul>
                </div>
            </nav>
        @show
    </header>

    <!-- =============================================== -->
@section('sidebar')
    @include('payment.inc.siderbar')
@show

<!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    @yield('header')

    <!-- Main content -->
        <section class="content">

            @section('content')

                @include('payment.inc.payment_detail')
            @show


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        @if (config('backpack.base.show_powered_by'))
            <div class="pull-right hidden-xs">
                {{ trans('backpack::base.powered_by') }} <a target="_blank"
                                                            href="http://backpackforlaravel.com?ref=panel_footer_link">Backpack
                    for Laravel</a>
            </div>
        @endif
        {{ trans('backpack::base.handcrafted_by') }} <a target="_blank"
                                                        href="{{ config('backpack.base.developer_link') }}">{{ config('backpack.base.developer_name') }}</a>.
    </footer>
</div>
<!-- ./wrapper -->


@yield('before_scripts')
@stack('before_scripts')

<!-- jQuery 2.2.3 -->
<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/select2/dist/js/select2.min.js"></script>
{{-- <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ asset('vendor/adminlte') }}/plugins/jQuery/jQuery-2.2.3.min.js"><\/script>')</script> --}}
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
{{-- <script src="{{ asset('vendor/adminlte') }}/bower_components/fastclick/lib/fastclick.js"></script> --}}
<script src="{{ asset('vendor/adminlte') }}/dist/js/adminlte.min.js"></script>


<!-- page script -->
<script type="text/javascript">
    /* Store sidebar state */
    $('.sidebar-toggle').click(function (event) {
        event.preventDefault();
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
        } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
        }
    });
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

    // Set active state on menu element
    var current_url = "{{ Request::fullUrl() }}";
    var full_url = current_url + location.search;
    var $navLinks = $("ul.sidebar-menu li a");
    // First look for an exact match including the search string
    var $curentPageLink = $navLinks.filter(
        function () {
            return $(this).attr('href') === full_url;
        }
    );
    // If not found, look for the link that starts with the url
    if (!$curentPageLink.length > 0) {
        $curentPageLink = $navLinks.filter(
            function () {
                return $(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href'));
            }
        );
    }

    $curentPageLink.parents('li').addClass('active');
            {{-- Enable deep link to tab --}}
    var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
    location.hash && activeTab && activeTab.tab('show');
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        location.hash = e.target.hash.replace("#tab_", "#");
    });
</script>

@include('backpack::inc.alerts')

@yield('after_scripts')
@stack('crud_fields_scripts')

<!-- JavaScripts -->
{{-- <script src="{{ mix('js/app.js') }}"></script> --}}
<script>

    //$( ".content" ).css( "visibility", "hidden" );
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
        $('.method').click(function () {
            $('#menu1').show();
            $('#home').hide();
        });
        $('.home').click(function () {
            $('#menu1').hide();
            $('#home').show();
        });
        $('.checked_all').on('change', function () {
            if ($(this).is(':checked')) {
                $('.minimal').is(':checked', true);
            }
        })

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

    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $(function () {

        $(document).on('click', '.show-detail-supply', function (e) {
            e.preventDefault();
            supply_id = $(this).data('supply_id');
            amount = $(this).find('.bal').data('amount');

            getSupplyInfo(supply_id, amount);


        });

        $(document).on('change', '.tran-type', function (e) {

            var tran_type = $('.tran-type').val();
            var start_date = $('[name="start_date"]').val();
            var end_date = $('[name="end_date"]').val();
            getSupplyTransaction(supply_id, tran_type, start_date, end_date);


        });

        $(document).on('click', '.l-tran-type', function (e) {
            e.preventDefault();
            var tran_type = $(this).data('type');
            $('.tran-type').val(tran_type);

            var start_date = $('[name="start_date"]').val();
            var end_date = $('[name="end_date"]').val();
            getSupplyTransaction(supply_id, tran_type, start_date, end_date);


        });


        $(".content").hide();
        $(".content").show("slow");

    });

    function getSupplyInfo(supply_id, amount) {
        $.ajax({
            type: 'GET',
            url: '{{ url('admin/get-supply-info') }}',
            data: {
                supply_id: supply_id,
            },
            success: function (res) {
                if (res.error == 0) {
                    $('.btn-supplier-checked').empty();
                    $('.p-company').text(res.row.company);
                    $('.p-name').text(res.row.name);
                    $('.p-phone').text(res.row.phone);
                    $('.p-address').text(res.row.address);
                    $('.btn-checked').data('supplier_id', res.row.id);
                    if (amount > 0) {
                        var str = '<a href="{{url('/admin/get-payment/')}}' + "/" + res.row.id + '" class="btn btn-default pull-right btn-checked">Bill Payment</a>';
                    }
                    $('.btn-supplier-checked').append(str);
                }
            }

        });


        var tran_type = $('.tran-type').val();
        var start_date = $('[name="start_date"]').val();
        var end_date = $('[name="end_date"]').val();
        getSupplyTransaction(supply_id, tran_type, start_date, end_date);

    }


    function getSupplyTransaction(supply_id, tran_type, start_date, end_date) {

        $.ajax({
            type: 'GET',
            url: '{{ url('admin/get-supply-transaction') }}',
            data: {
                supply_id: supply_id,
                tran_type: tran_type,
                start_date: start_date,
                end_date: end_date,

            },
            success: function (res) {
                $('.show-transaction').html(res);
            }

        });

    }


    $(function () {
        {{--$('body').on('change', '[name="s_supplier_id[]"]', function () {--}}

        {{--    console.log($(this).val());--}}
        {{--    $('.list-sup').empty();--}}
        {{--    var supply_id = $(this).val();--}}
        {{--    $.ajax({--}}
        {{--        url: '{{url('api/supplier-id/')}}' + '/' + supply_id,--}}
        {{--        method: 'get',--}}
        {{--        data: {--}}
        {{--            supply_id: supply_id--}}
        {{--        },--}}
        {{--        success: function (data) {--}}
        {{--            console.log(data);--}}
        {{--            $('.list-sup').empty();--}}
        {{--            $('.show-detail-supply').parent().parent().empty();--}}
        {{--            $('.list-sup').html(data);--}}
        {{--        }--}}
        {{--    });--}}

        {{--});--}}

        $(".select2_field-supplier").each(function (i, obj) {
                console.log('obj is' + obj);
                var form = $(obj).closest('form');
                var url = $(this).data('source');

                if (!$(obj).hasClass("select2-hidden-accessible")) {

                    $(obj).select2({
                        theme: 'bootstrap',
                        multiple: false,
                        placeholder: "select supplier",
                        allowClear: true,
                        ajax: {
                            url: url,
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
                                        textField = "name";
                                        if (item["bal"] != ""){
                                            return {
                                                text: item[textField] + '-' + item["bal"].toFixed(2),
                                                id: item["supplier_id"]
                                            }
                                        }
                                    }
                                ),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };

                            return result;
                        },
                        cache: true
                        },
                    });

                }
        }).on('select2:select', function (e) {
            var supply_id = $(this).val();
            getSupplierList(supply_id);
        }).on('select2:unselecting', function (e) {
            var supply_id = 0;

            getSupplierList(supply_id);
        });


    });

    function getSupplierList(supply_id) {
        $.ajax({
            url: '{{url('api/supplier-id/')}}' + '/' + supply_id,
            method: 'get',
            data: {
                supply_id: supply_id
            },
            success: function (data) {
                console.log(data);
                $("#supplier-result").html('');
                $("#supplier-result").html(data);
            }
        });
    }
</script>
</body>
</html>
