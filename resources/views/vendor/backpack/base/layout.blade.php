<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('backpack::inc.head')
    <style>
        .sep-p {
            padding: 0;
            margin-top: -5px;
            margin-right: 0px;
            margin-bottom: 0px;
            margin-left: 0px;
            height: 1px;
        }
        .help-block {
            display: none;
        }

        .callout.callout-danger {
            line-height: 30px;
        }

        .text-capitalize {
            font-family: "Poppins" !important;
            font-size: 16px;
        }

        small {
            font-family: "Poppins" !important;
        }

        .box-title {
            font-family: "Poppins" !important;
        }

        .text-capitalize-bar {
            font-family: "Poppins" !important;
            font-size: 16px;
            font-weight: bold;
        }

        .logo-lg {
            font-family: "Poppins" !important;
            font-size: 18px !important;

        }


        .dataTables_scrollBody{
            min-height: 230px;
        }
        .table.dataTable thead .sorting{
            padding-right: 8px;
        }
        .table.dataTable thead th{
            min-width: 100px;
        }

        .table.dataTable tbody td{
            white-space: normal;
            min-width: 100px;
        }

        table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting{
            padding-right: 0px;
        }

        .table.dataTable tbody td:last-child {
            white-space: nowrap;
        }


        .text-capitalize{
            font-family: "Poppins" !important;
        }

        .whole-page-overlay{
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            position: fixed;
            background: rgba(0,0,0,0.6);
            width: 100%;
            height: 100% !important;
            z-index: 1050;
            display: none;
            text-align: center;
        }
        .whole-page-overlay .center-loader{
            top: 50%;
            position: absolute;
            color: white;
        }

        .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side {
            /*background-color: #434343;*/
            /*background-color: #ffffff;*/
            background-color: #2c3b41d4;
        }

        .skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a, .skin-blue .sidebar-menu>li.menu-open>a{
            background: #1e282cc2;
        }
       /* .skin-blue .sidebar a{
            color: #343a40;
        }

        .skin-blue .sidebar-menu>li:hover>a, .skin-blue .sidebar-menu>li.active>a, .skin-blue .sidebar-menu>li.menu-open>a{
            color: #343a40;
            background: #ffffff;
        }

        .skin-blue .sidebar-menu>li>.treeview-menu{
            color: #343a40;
            background: #ffffff;
        }*/
    </style>
</head>
<body class="hold-transition @if(!(request()->is_frame >0)) {{ config('backpack.base.skin') }} @endif sidebar-mini" style="font-family: 'Poppins">
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
    @if(!(request()->is_frame >0))
        @include('backpack::inc.main_header')
    @endif
    <!-- =============================================== -->
    @if(!(request()->is_frame >0))
    @section('sidebar')

            @include('backpack::inc.sidebar')

    @show
    @endif
      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
        <div class="{{ (request()->is_frame >0?'':'content-wrapper') }}">
        <!-- Content Header (Page header) -->
         @yield('header')

        <!-- Main content -->
        <section class="content">
            @php
            $Url = explode('/', Request::url());
            @endphp
            @if(in_array('comming-repayment', $Url) && companyReportPart() == 'company.mkt')
            @if($errors->any())
            @foreach ($errors->all() as $message)
                <div class="alert alert-warning ">
                    <strong>Sorry !</strong> {{$message}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
            @endif
            @if(Session::has('message'))
            <div class="alert alert-success">
                <strong>{{Session::get('message')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @php
            $acc_codes = App\Models\AccountChart::select('name','code','id')->get();
            $currentURL = \Request::fullUrl();
            //dd($acc_codes);
            @endphp
            <form action="{{route('select_repayment')}}" method = "GET">
                @csrf
                <div class="form-group col-md-3">
                    <input type="text" id="time" class="form-control" name="repayment_date" placeholder="Select Repayment Date" required>
                    <input type="hidden" name="current_url" value="{{$currentURL}}">
                </div>
                <div class="form-group col-md-3">
                    <select class="form-control  select2_field my_select" name="acc_code">
                        @foreach ($acc_codes as $acc_code )
                            <option value="{{$acc_code->id}}">{{$acc_code->name}} ({{$acc_code->code}})</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" id="approve_id" name="disbursement_id" required>
                 <button class="btn btn-success" type="submit" value="submit"><span class="fa fa-check-circle-o"></span> Approve Selected</button>
                 </form>
            @endif

            @if(in_array('approve-loan-payment', $Url) && companyReportPart() == 'company.mkt')
            @if($errors->any())
            @foreach ($errors->all() as $message)
                <div class="alert alert-warning">
                    <strong>Sorry !</strong> {{$message}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
            @endif
            @if(Session::has('message'))
            <div class="alert alert-success">
                <strong>{{Session::get('message')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @php

            @endphp


            @endif

            @if(in_array('authorize-client-pending', $Url) && companyReportPart() == 'company.mkt')
                @if($errors->any())
                @foreach ($errors->all() as $message)
                    <div class="alert alert-warning">
                        <strong>Sorry !</strong> {{$message}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach
                @endif
                @if(Session::has('message'))
                <div class="alert alert-success">
                    <strong>{{Session::get('message')}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                @php

                @endphp

            @endif

          @yield('content')
            <div class="whole-page-overlay text-center" id="whole_page_loader">
                <img class="center-loader" height="10%" src="{{asset('uploads/images/logo/loader.gif')}}"/>
            </div>
            @if(in_array('authorize-client-pending', $Url) && companyReportPart() == 'company.mkt')
              <div class="row">
                  <form action="{{route('client_auth_mobile_approve')}}" method = "GET">
                      @csrf
                      <input type="hidden" id="approve_id" name="approve_id">
                      <div class="col-md-12">
                          <button class="btn btn-success" type="submit" value="submit"><span class="fa fa-check-circle-o"></span> Approve Selected</button>
                      </div>

                  </form>
              </div>

            @endif

            @if(in_array('approve-loan-payment', $Url) && companyReportPart() == 'company.mkt')

                <div class="row">
                    <form action="{{route('mobile_approve')}}" method = "GET">
                        @csrf
                        <input type="hidden" id="approve_id" name="approve_id">
                        <div class="col-md-12">
                            <button class="btn btn-success" type="submit" value="submit"><span class="fa fa-check-circle-o"></span> Approve Selected</button>
                        </div>

                    </form>
                </div>
            @endif


        </section>
        <!-- /.content -->
    </div>
      <!-- /.content-wrapper -->
      @if(!(request()->is_frame >0))
      <footer class="main-footer text-sm clearfix">
        @include('backpack::inc.footer')
      </footer>
      @endif
    </div>
    <!-- ./wrapper -->


    @yield('before_scripts')
    @stack('before_scripts')

    @include('backpack::inc.scripts')
    @include('backpack::inc.alerts')

    @yield('after_scripts')

    @stack('after_scripts')

    <script>

        /* Store sidebar state */
        $('.sidebar-toggle').click(function(event) {
          event.preventDefault();
          if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
            sessionStorage.setItem('sidebar-toggle-collapsed', '');
          } else {
            sessionStorage.setItem('sidebar-toggle-collapsed', '1');
          }
        });

        // Set active state on menu element
        var current_url = "{{ Request::fullUrl() }}";
        var full_url = current_url+location.search;
        var $navLinks = $("ul.sidebar-menu li a");
        // First look for an exact match including the search string
        var $curentPageLink = $navLinks.filter(
            function() { return $(this).attr('href') === full_url; }
        );
        var delay = (function(){
            var timer = 0;
            return function(callback, ms){
                clearTimeout (timer);
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
        if(!$curentPageLink.length > 0){
            $curentPageLink = $navLinks.filter(
                function() { return $(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href')); }
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


            $('[number="number"]').bind('paste', function() {
                var el = this;
                setTimeout(function() {
                    el.value = el.value.replace('[a-z] + [^0-9\s.]+|.(?!\d)','');
                    // el.value = el.value.replace(/[^0-9.]/g, '');
                    // el.value = el.value.replace(/\D/g, '');
                }, 0);
            });


            $('body').on('keypress','[number="number"]',function () {
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

        function addCommas(nStr)
        {
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

           /* $('form').on('submit',function(e){
                var error = 0;
                $('.required').find(':input').each(function () {
                    var input = $(this).val();
                    if(input == ''){
                        var name = $(this).prop('name');

                        $(this).focus();
                        new PNotify({
                            title: ("Require"),
                            text: name + " is not empty!!",
                            type: "warning"
                        });
                        error = 1;
                        return false;
                    }
                });

                if(error>0){
                    e.preventDefault();
                    $('button').prop("disabled", false);
                    new PNotify({
                        title: ("Require"),
                        text: "* is not empty!!",
                        type: "warning"
                    });
                    return false;
                }
            });*/



            $('.change-branch-top').on('change',function () {
                var branch_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{url('/api/change-branch')}}',
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

    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>--}}
    <script src="{{ asset('vendor/adminlte') }}/plugins/jquery/jquery.jscroll.min.js" ></script>

    <script type="text/javascript">
    // $( document ).ready(function() {
    //     console.log( "document loaded" );
    //     $(".infinite-scroll").scroll(function() {
    //         // if($(window).scrollTop() + $(window).height() >= $("#product_list").height()) {
    //
    //         alert("hi");
    //         // }
    //     });
    // });
    var path = location.pathname.substring(1);
    if(!(path == "admin/saving-report-moeyan?type=normal") || !(path == "admin/saving-report-moeyan?type=customer") || (path == "admin/interest-cal-report")){
        $('ul.pagination').hide();
    }
    $(function() {
        $('.infinite-scroll').jscroll({
            debug: true,
            autoTrigger: true,
            loadingHtml: '<!--<img class="center-block" src="/images/loading.gif" alt="Loading..." />-->',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'ul.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
                // $('div.jscroll-added').removeClass('menu');
                $("div.jscroll-added ul.menu>li>a").css({"padding": 0, "border-bottom": "none"});
                $("div.jscroll-added ul.infinite-scroll").css({"overflow": "hidden"});
                // window.scrollTo(0,0);
            }
        });

    });
</script>

    <!-- JavaScripts -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
    <script src="{{ asset('js/number/jquery.number.min.js') }}"></script>
    <script>
        $(function() {

            /* $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
                 if(e.keyCode == 13) {
                     e.preventDefault();
                     return false;
                 }
             });*/
            $(document).on("keypress", ":input:not(textarea):not(:submit)", function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $('.help-block').each(function() {
                $(this).hide();
            });

            $('[number="number"]').keypress(function(event) {
                return isNumber(event, this);
            });


            $('[number="number"]').bind('paste', function() {
                var el = this;
                setTimeout(function() {
                    el.value = el.value.replace('[a-z] + [^0-9\s.]+|.(?!\d)', '');
                    // el.value = el.value.replace(/[^0-9.]/g, '');
                    // el.value = el.value.replace(/\D/g, '');
                }, 0);
            });


            $('body').on('keypress', '[number="number"]', function() {
                return isNumber(event, this);
            });

            /* $('body').on('focus','[number="number"]',function () {
                 $(this).number( true, 2 );
             });*/

            //$('[number="number"]').number( true, 2 );
            $('.num').number(true, 2);

            // {{--@if(isHRM())--}}

            // {{--getNewNotifications();--}}
            // {{--getNewNotificationsBirthday();--}}
            // {{--getNewNotificationsJobVacancy();--}}
            // {{--getNewNotificationsTraining();--}}
            // {{--getNewNotificationsEvent();--}}

            // {{--@endif--}}

            });

            function isNumber(evt, element) {

                var charCode = (evt.which) ? evt.which : event.keyCode;
                //console.log(charCode);
                if (
                    (charCode != 45 || $(element).val().indexOf('-') != -1) && // “-” CHECK MINUS, AND ONLY ONE.
                    (charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
                    (charCode != 37 || $(element).val().indexOf('%') != -1) && // “.” CHECK DOT, AND ONLY ONE.
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

            $(function() {

                $('form').on('submit', function(e) {

                    $('[number="number"]').each(function() {
                        var v = $(this).val();
                        console.log(v);
                        $(this).prop('value', v);
                    });

                    //return  false;

                    var error = 0;
                    $('.required').find(':input').each(function() {
                        var input = $(this).val();
                        if (input == '') {
                            $(this).focus();
                            error = 1;
                            return false;
                        }
                    });
                    if (error > 0) {
                        e.preventDefault();
                        $('button').prop("disabled", false);
                        new PNotify({
                            title: ("Require"),
                            text: "* is not empty!!",
                            type: "warning"
                        });
                        return false;
                    }
                });

            });


            $(function() {
                /* if($(window).width()<750){
                     $('body').removeClass('sidebar-collapse');
                 }*/

                $(document).on('click', '.sidebar-toggle', function() {
                    if ($('body').hasClass("sidebar-collapse") && $('body').hasClass("sidebar-open")) {
                        $('body').removeClass("sidebar-collapse");
                    }
                });


                var isInIFrame = (window.location != window.parent.location);
                if (isInIFrame == true) {
                    // iframe
                    //console.log('ifram')
                    $('.main-sidebar').hide();
                    $('.main-header').hide();
                    $('.main-footer').hide();

                    $('.content-wrapper').css('width', '100%');
                    $('#saveActions').parent().html('<button type="submit" class="btn btn-success">\n' +
                        '            <span class="fa fa-save" role="presentation"> Save</span> ' +
                        '        </button>');
                }
            });


            $(document).on('click', '.change-branch-top', function(e) {
                e.preventDefault();
                var branch_id = $(this).val();
                if (branch_id) {
                    $.ajax({
                        type: 'GET',
                        url: "{{url('/api/get_branch_report')}}" + '/' + branch_id,
                        data: {
                            branch_id: branch_id,
                        },
                        success: function(res) {
                            console.log(res);
                            $('#sale_amount').text(res.sale_amount_total);
                            $('#sale_return').text(res.sale_return_amount);
                            $('#purchase_amount').text(res.purchase_amount);
                            $('#stock_b').text(res.stock_balance);
                            $('#cash_balance').text(res.cash_balance);
                            $('#expense').text(res.expense_amount);
                            $('#gross_profit_amount').text(res.gross_profit_amount);
                            $('#branch_id_session').val(res.branch_id_session);
                            $('#receivable').text(res.receivable);
                            $('#income').text(res.income);

                            // document.getElementsByTagName('form')[0].submit();
                        }

                    });

                } else {

                }
            });
    </script>
</body>
</html>
