<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">


    <meta name="csrf-token" content="K21BtJtjUL5WoFJkgNNrMqcanCQtRRg5HzbQDOYL"/>

    <title>
        Add loan :: loan Admin
    </title>


    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
          href="http://localhost:8000/vendor/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="http://localhost:8000/vendor/adminlte/bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="http://localhost:8000/vendor/adminlte/plugins/ionicons/css/ionicons.min.css">

    <link rel="stylesheet" href="http://localhost:8000/vendor/adminlte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="http://localhost:8000/vendor/adminlte/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="http://localhost:8000/vendor/adminlte/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="http://localhost:8000/vendor/backpack/pnotify/pnotify.custom.min.css">


    <link rel="stylesheet" href="http://localhost:8000/vendor/adminlte/dist/fonts/sans-pro.css">
    <link rel="stylesheet" href="http://localhost:8000/vendor/adminlte/dist/fonts/moul.css">


    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="http://localhost:8000/vendor/backpack/base/backpack.base.css?v=3">
    <link rel="stylesheet" href="http://localhost:8000/vendor/backpack/base/backpack.bold.css">


    <link rel="stylesheet" href="http://localhost:8000/vendor/backpack/crud/css/crud.css">
    <link rel="stylesheet" href="http://localhost:8000/vendor/backpack/crud/css/form.css">
    <link rel="stylesheet" href="http://localhost:8000/vendor/backpack/crud/css/create.css">

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
    <!-- include select2 css-->
    <link href="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/css/select2.min.css"
          rel="stylesheet" type="text/css"/>

    <link href="http://localhost:8000/vendor/adminlte/plugins/select2/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>


    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>

    <!-- include select2 css-->
    <link href="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/css/select2.min.css"
          rel="stylesheet" type="text/css"/>

    <link href="http://localhost:8000/vendor/adminlte/plugins/select2/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>


    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>
    <!-- include select2 css-->
    <link href="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/css/select2.min.css"
          rel="stylesheet" type="text/css"/>

    <link href="http://localhost:8000/vendor/adminlte/plugins/select2/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>


    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>
    <!-- include select2 css-->
    <link href="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/css/select2.min.css"
          rel="stylesheet" type="text/css"/>

    <link href="http://localhost:8000/vendor/adminlte/plugins/select2/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>


    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>
    <!-- include select2 css-->
    <link href="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/css/select2.min.css"
          rel="stylesheet" type="text/css"/>

    <link href="http://localhost:8000/vendor/adminlte/plugins/select2/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>


    <style type="text/css">
        .select2-selection__clear::after {
            content: ' Clear';
        }
    </style>
    <link rel="stylesheet"
          href="http://localhost:8000/vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
    <!-- include select2 css-->
    <link href="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/css/select2.min.css"
          rel="stylesheet" type="text/css"/>

    <link href="http://localhost:8000/vendor/adminlte/plugins/select2/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>


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

    <style>
        .skin-blue .main-header .navbar {
            background: #03a9f4;
            color: white;
        }

        .skin-blue .main-header .logo {
            background: #03a9f4;
            border-color: #03a9f4 !important;
        }

        .btn-primary {
            background: #00BCD4;
        }

        .btn-primary:hover {
            border-color: #008697 !important;
            background: #008fa1;
        }

        .btn-primary:focus, .btn-primary.focus, .btn-primary:hover {
            background-color: #00BCD4;
        }

        .btn:hover, .btn:focus, .btn.focus {
            -webkit-box-shadow: 0 0 0 100px rgba(0, 0, 0, 0.05) inset;
            box-shadow: 0 0 0 100px rgba(0, 0, 0, 0.05) inset;
        }

        .btn-sm:not(.btn-rounded), .btn-group-sm > .btn:not(.btn-rounded), .btn-xs:not(.btn-rounded), .btn-group-xs > .btn:not(.btn-rounded) {
            border-radius: 3px;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
            font-family: Content !important;
            font-size: 16px;
        }

        small {
            font-family: Content !important;
        }

        .box-title {
            font-family: Content !important;
        }

        .text-capitalize-bar {
            font-family: Content !important;
            font-size: 16px;
            font-weight: bold;
        }

        .logo-lg {
            font-family: Moul;
            font-size: 18px !important;

        }

        .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side {
            background-color: #434343;
        }

        .dataTables_scrollBody {
            min-height: 230px;
        }

        .table.dataTable thead .sorting {
            padding-right: 8px;
        }

        .table.dataTable thead th {
            min-width: 100px;
        }

        .table.dataTable tbody td {
            white-space: normal;
            min-width: 100px;
        }

        table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting {
            padding-right: 0px;
        }

        .table.dataTable tbody td:last-child {
            white-space: nowrap;
        }

    </style>
</head>
<body class="hold-transition  skin-blue  sidebar-mini" style="font-family: 'Content', Titillium Web;">
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
        <!-- Logo -->
        <a href="javascript:void(0)" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>C</b>N</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">
                <select style="color: #000;" class="change-branch-top">
                                                                <option selected value="1">BR001-Head Office</option>
                                    <option value="2">BR002-Mandalay Branch</option>
                                    <option value="3">BR003-Magway Branch</option>
                                    <option value="4">BR004-Meikhitlar</option>
                                    <option value="5">BR005-NPT Branch 00</option>
                                    <option value="6">BR006-NPT Branch 01</option>
                                    </select>
    </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu pull-left">
                <ul class="nav navbar-nav">
                    <!-- =================================================== -->
                    <!-- ========== Top menu items (ordered left) ========== -->
                    <!-- =================================================== -->

                    <!-- Topbar. Contains the left part -->
                    <!-- This file is used to store topbar (left) items -->


                    <!-- ========== End of top menu left items ========== -->
                </ul>
            </div>


            <div class="navbar-custom-menu pull-right">

                <ul class="nav navbar-nav">
                    <li><img src="http://localhost:8000/uploads/images/logo/moeyan.png" height="51"></li>
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">0</span>
                        </a>
                        <ul class="dropdown-menu" style="width:400px;">
                            <li class="header">You have 0 notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->

                                <ul class="menu infinite-scroll" style="overflow-y: scroll; max-height:350px;">


                                </ul>
                            </li>
                            <li class="footer"><a href="http://localhost:8000/list_notification_all">View all</a></li>
                        </ul>
                    </li>
                    <!-- Notifications: style can be found in dropdown.less -->

                </ul>


                <ul class="nav navbar-nav">
                    <!-- ========================================================= -->
                    <!-- ========= Top menu right items (ordered right) ========== -->
                    <!-- ========================================================= -->

                    <!-- Topbar. Contains the right part -->
                    <!-- This file is used to store topbar (right) items -->


                    <li><a href="http://localhost:8000/admin/logout"><i class="fa fa-btn fa-sign-out"></i> Logout</a>
                    </li>
                    <!-- ========== End of top menu right items ========== -->
                </ul>


            </div>
        </nav>
    </header>
    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <a class="pull-left image" href="http://localhost:8000/admin/edit-account-info">


                    <img src="http://localhost:8000/avatar.png" class="img-circle" alt="User Image">

                </a>
                <div class="pull-left info">
                    <p><a href="http://localhost:8000/admin/edit-account-info">ksmart.lion</a></p>
                    <small>
                        <small><a href="http://localhost:8000/admin/edit-account-info"><span><i
                                            class="fa fa-user-circle-o"></i> My Account</span></a> &nbsp; &nbsp; <a
                                    href="http://localhost:8000/admin/logout"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
                        </small>
                    </small>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">

                <!-- ================================================ -->
                <!-- ==== Recommended place for admin menu items ==== -->
                <!-- ================================================ -->

                <!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
                <li><a href="http://localhost:8000/admin/dashboard"><i
                                class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
                <li><a href="http://localhost:8000/admin/dashboard_mkt"><i class="fa fa-dashboard"></i><span>Dashboard Data</span></a>
                </li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-users"></i> <span>MANAGE CLIENT</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">


                        <li><a href='http://localhost:8000/admin/authorize-client-pending'><i
                                        class="fa fa-plus-square-o"></i> <span>Authorize Client</span></a></li>
                        <li><a href='http://localhost:8000/admin/approve-client-pending'><i
                                        class="fa fa-plus-square-o"></i> <span>Approve Client</span></a></li>
                        <li><a href='http://localhost:8000/admin/client/create'><i class="fa fa-plus-square-o"></i>
                                <span>Add Client</span></a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-user"></i> <span>Client List</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>

                            <ul class="treeview-menu">

                                <li><a href="http://localhost:8000/admin/client"><i class="fa fa-user"></i> All Client
                                        List</a></li>
                                <li><a href="http://localhost:8000/admin/active-member-client"><i
                                                class="fa fa-user"></i> Active Member</a></li>
                                <li><a href="http://localhost:8000/admin/drop_out-member-client"><i
                                                class="fa fa-user"></i> Drop-out Member</a></li>
                                <li><a href="http://localhost:8000/admin/rejoin-member-client"><i
                                                class="fa fa-user"></i> Rejoin Member</a></li>
                                <li><a href="http://localhost:8000/admin/substitute-member-client"><i
                                                class="fa fa-user"></i> Substitute Member</a></li>
                                <li><a href="http://localhost:8000/admin/testing-user-client"><i class="fa fa-user"></i>
                                        Testing User</a></li>
                                <li><a href="http://localhost:8000/admin/dead-member-client"><i class="fa fa-user"></i>
                                        Dead Member</a></li>
                            </ul>
                        </li>
                        <li><a href='http://localhost:8000/admin/reject-client'><i class="fa fa-user"></i> <span>Reject Client</span></a>
                        <li><a href='http://localhost:8000/admin/clientcenterleader'><i class="fa fa-plus-square-o"></i>
                                <span>Center Leader</span></a>
                        <li><a href='http://localhost:8000/admin/guarantor/create'><i
                                        class="fa fa-plus-square-o"></i><span>Add Guarantor</span></a></li>
                        <li><a href='http://localhost:8000/admin/guarantor'><i class="fa fa-plus-square-o"></i><span>Guarantor List</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/add-address'><i class="fa fa-plus-square-o"></i> <span>Add Address</span></a>
                        </li>
                        <li class="sep-p">
                            <hr>
                        </li>

                        <li><a href="http://localhost:8000/admin/nrc-prefix"><i class='fa fa-tag'></i>
                                <span>NRC Prefix</span></a></li>
                        <li><a href="http://localhost:8000/admin/survey"><i class='fa fa-tag'></i>
                                <span>Survey</span></a></li>
                        <li><a href="http://localhost:8000/admin/ownership"><i class='fa fa-tag'></i>
                                <span>Ownership</span></a></li>
                        <li><a href="http://localhost:8000/admin/ownershipfarmland"><i class='fa fa-tag'></i><span>Ownership Farmland</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/customergroup"><i class='fa fa-tag'></i> <span>CUSTOMER TYPE</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/import-client/create"><i class='fa fa-download'></i>
                                <span>Import Client</span></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-cog"></i> <span>MANAGE LOAN</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">

                        <li><a href='http://localhost:8000/admin/loan-calculator/create'><i
                                        class="fa fa-plus-square-o"></i> <span>Loan Calculator</span></a></li>
                        <li><a href="http://localhost:8000/admin/loandisbursement/create"><i
                                        class='fa fa-tag'></i><span>Create Loan</span></a></li>
                        <li><a href="http://localhost:8000/admin/grouploan"><i class="fa fa-plus-square-o"></i> <span>Add Group Loan</span></a>
                        </li>
                        <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Group Loan</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href='http://localhost:8000/admin/group-loan-approve'><i
                                                class="fa fa-plus-square-o"></i> <span>Group Pending Approve</span></a>
                                </li>
                                <li><a href='http://localhost:8000/admin/group-loan-deposit'><i
                                                class="fa fa-plus-square-o"></i> <span>Group Deposit</span></a></li>
                                <li><a href='http://localhost:8000/admin/group-dirseburse'><i
                                                class="fa fa-plus-square-o"></i> <span>Group Dirseburse</span></a></li>
                                <li><a href='http://localhost:8000/admin/group-repayment-new'><i
                                                class="fa fa-plus-square-o"></i> <span>Group Repayment</span></a></li>
                                <li><a href='http://localhost:8000/admin/group-due-repayment'><i
                                                class="fa fa-plus-square-o"></i> <span>Group Due Repayment</span></a>
                                </li>
                            </ul>
                        </li>

                        <li><a href="http://localhost:8000/admin/loandisbursement"><i class='fa fa-tag'></i> <span>View All Loan</span><span
                                        class="pull-right-container"><span
                                            class="label label-info pull-right">16840</span></span></a></li>
                        <li><a href="http://localhost:8000/admin/loan-disbursement-branch"><i class='fa fa-tag'></i>
                                <span>Change Branch of Loan</span></a></li>
                        <li><a href="http://localhost:8000/admin/disbursependingapproval"><i
                                        class='fa fa-tag'></i><span>Pending Approval</span><span
                                        class="pull-right-container"><span
                                            class="label label-warning pull-right">93</span></span></a></li>
                        <li><a href="http://localhost:8000/admin/disburseawaiting"><i class='fa fa-tag'></i> <span>Approved</span><span
                                        class="pull-right-container"><span
                                            class="label label-success pull-right">17</span></span></a></li>
                        <li><a href="http://localhost:8000/admin/loanoutstanding"><i class='fa fa-tag'></i> <span>Activated</span><span
                                        class="pull-right-container"><span
                                            class="label label-success pull-right">6978</span></span></a></li>
                        <li><a href="http://localhost:8000/admin/disburseclosed"><i class='fa fa-tag'></i> <span>Loan Completed</span><span
                                        class="pull-right-container"><span
                                            class="label label-success pull-right">9750</span></span></a></li>
                        <li><a href="http://localhost:8000/admin/disbursedeclined"><i class='fa fa-tag'></i> <span>Loan Declined</span><span
                                        class="pull-right-container"><span
                                            class="label label-danger pull-right">1</span></span></a></li>
                        <li><a href="http://localhost:8000/admin/disbursecanceled"><i class='fa fa-tag'></i> <span>Loan Canceled</span><span
                                        class="pull-right-container"><span
                                            class="label label-danger pull-right">1</span></span></a></li>
                        <li><a href="http://localhost:8000/admin/disbursewrittenoff"><i class='fa fa-tag'></i><span>Loan Write Off</span><span
                                        class="pull-right-container"><span
                                            class="label label-danger pull-right">0</span></span></a></li>


                        <li class="sep-p">
                            <hr>
                        </li>

                        <li><a href="http://localhost:8000/admin/loanobjective"><i class='fa fa-tag'></i> <span>Loan Objective</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/transactiontype"><i class='fa fa-tag'></i><span>Transaction Type</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/import-loan/create"><i class='fa fa-download'></i>
                                <span>Import Loan</span></a></li>
                    </ul>
                </li>


                <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>MANAGE PAYMENT</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">

                        <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Payment Deposit</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href='http://localhost:8000/admin/depositpending'><i
                                                class="fa fa-plus-square-o"></i> <span>List Pending Deposit</span></a>
                                </li>
                                <li><a href='http://localhost:8000/admin/loandisburesementdepositu/create'><i
                                                class="fa fa-plus-square-o"></i> <span>Add Deposits</span></a></li>
                                <li><a href='http://localhost:8000/admin/loandisburesementdepositu'><i
                                                class="fa fa-plus-square-o"></i> <span>List Deposits</span></a></li>
                            </ul>
                        </li>

                        <li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Paid Disbursement</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href='http://localhost:8000/admin/disbursementpending'><i
                                                class="fa fa-plus-square-o"></i> <span>List Pending Disbursement</span></a>
                                </li>
                                <li><a href='http://localhost:8000/admin/my-paid-disbursement/create'><i
                                                class="fa fa-plus-square-o"></i> <span>Add Disbursement</span></a></li>
                                <li><a href='http://localhost:8000/admin/my-paid-disbursement'><i
                                                class="fa fa-plus-square-o"></i> <span>List Disbursement</span></a></li>
                            </ul>
                        </li>


                        <li><a href="http://localhost:8000/admin/authorize-loan-payment"><i class='fa fa-tag'></i>
                                <span> Authorize Pending Repayment</span></a></li>
                        <li><a href="http://localhost:8000/admin/approve-loan-payment"><i class='fa fa-tag'></i> <span> Approve Pending Repayment</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/due-repayment-list"><i class='fa fa-tag'></i> <span>List Due Repayments</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/late-repayment-list"><i class='fa fa-tag'></i> <span>List Late Repayments</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/addloanrepayment'><i class="fa fa-plus-square-o"></i>
                                <span>Add Loan Repayment</span></a></li>
                        <li><a href='http://localhost:8000/admin/report/loan-repayments'><i
                                        class="fa fa-plus-square-o"></i> <span>List Repayments</span></a></li>
                        <li><a href="http://localhost:8000/admin/import-loan-repayment/create"><i
                                        class='fa fa-download'></i> <span>Import Loan Repayment</span></a></li>
                        <li><a href="http://localhost:8000/admin/paid-support-fund"><i class='fa fa-tag'></i> <span>Paid Support Fund</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/comming-repayment"><i class='fa fa-plus-square-o'></i>
                                <span>Coming Repayments</span></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-users"></i> <span>COMPULSORY SAVING</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">

                        <li><a href='http://localhost:8000/admin/compulsorysavinglist'><i class='fa fa-tag'></i> <span>Compulsory Save List</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/compulsorysavingactive'><i class='fa fa-tag'></i>
                                <span>Compulsory Save Active</span></a></li>
                        <li><a href='http://localhost:8000/admin/compulsorysavingcompleted'><i class='fa fa-tag'></i>
                                <span>Compulsory Save Completed</span></a></li>
                        <li><a href='http://localhost:8000/admin/compulsory-saving-transaction'><i
                                        class='fa fa-tag'></i> <span>Saving Transactions</span></a></li>
                        <li><a href='http://localhost:8000/admin/cashwithdrawal'><i class='fa fa-tag'></i> <span>Cash Withdrawal</span></a>
                        </li>


                    </ul>
                </li>


                <li class="treeview">
                    <a href="#"><i class="fa fa-users"></i> <span>MANAGE SAVING</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">

                        <li><a href='http://localhost:8000/admin/saving-calculator/create'><i
                                        class="fa fa-plus-square-o"></i> <span>Saving Calculator</span></a></li>
                        <li><a href='http://localhost:8000/admin/open-saving-account/create'><i
                                        class="fa fa-plus-square-o"></i> <span>Open Saving Account</span></a></li>


                    </ul>
                </li>


                <li class="treeview">
                    <a href="#"><i class="fa fa-bookmark"></i> <span>MANAGE TRANSFER</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href='http://localhost:8000/admin/loan-pending-transfer'><i class='fa fa-tag'></i> <span>Loans Transfer</span></a>
                        </li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-money"></i> <span>MANAGE PRODUCT</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href='http://localhost:8000/admin/loan-product'><i class="fa fa-plus-square-o"></i><span>Loan Product</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/saving-product'><i class="fa fa-plus-square-o"></i>
                                <span>Saving Product</span></a></li>
                        <li><a href='http://localhost:8000/admin/compulsory-product'><i class='fa fa-tag'></i> <span>Compulsory Product</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/charge'><i class='fa fa-tag'></i>
                                <span>Charge</span></a></li>

                    </ul>
                </li>

                <!-- Account -->

                <!-- Product -->


                <li class="treeview">
                    <a href="#"><i class="fa fa-book"></i> <span>ACCOUNTING</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="http://localhost:8000/admin/general-journal"><i
                                        class="fa fa-plus-square-o"></i><span>General Journal</span></a></li>
                        <li><a href="http://localhost:8000/admin/transfer"><i class='fa fa-tag'></i>
                                <span>Transfer</span></a></li>
                        <li><a href="http://localhost:8000/admin/expense"><i class="fa fa-plus-square-o"></i> <span>
                         Expense </span></a></li>
                        <li><a href="http://localhost:8000/admin/journal-profit"><i class="fa fa-plus-square-o"></i>
                                <span>
                         Other income </span></a></li>

                        <li><a href='http://localhost:8000/admin/account-chart'><i
                                        class="fa fa-plus-square-o"></i><span>Chart of Account</span></a></li>

                        <li class="sep-p">
                            <hr>
                        </li>


                        <li><a href='http://localhost:8000/admin/currency'><i class="fa fa-plus-square-o"></i><span>Currency</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/import-journal'><i
                                        class="fa fa-plus-square-o"></i><span>Import Journal / Expense</span></a></li>

                        <li><a href='http://localhost:8000/admin/frd-account-setting2/create'><i
                                        class='fa fa-plus-square-o'></i><span>FRD Account Setting</span></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-bookmark"></i> <span>CAPITAL</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href='http://localhost:8000/admin/capital'><i class='fa fa-tag'></i>
                                <span>Add Capital</span></a></li>
                        <li><a href='http://localhost:8000/admin/capital-withdraw'><i class='fa fa-tag'></i> <span>Withdraw Capital</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/shareholder'><i class='fa fa-tag'></i> <span>Shareholder</span></a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-list"></i> <span>REPORT</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">

                        <li><a href='http://localhost:8000/admin/summary-report'><i class='fa fa-tag'></i><span>Summary Report</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/report-accounting'><i class='fa fa-tag'></i><span>Accounting Report</span></a>
                        </li>
                        <li><a href='http://localhost:8000/admin/report-account-external'><i
                                        class='fa fa-tag'></i><span>Accounting FRD Report </span></a></li>


                        <li><a href='http://localhost:8000/admin/group-report'><i class='fa fa-tag'></i><span>Group Loan Report</span></a>
                        </li>
                        <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Loan Reports</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href='http://localhost:8000/admin/report/client-information'><i
                                                class="fa fa-plus-square-o"></i> <span>Customer Info</span></a></li>
                                <li><a href='http://localhost:8000/admin/report/loan-outstanding'><i
                                                class="fa fa-plus-square-o"></i> <span>Loan Outstanding</span></a></li>
                            </ul>
                        </li>

                        <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Payment Reports</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">

                                <li><a href='http://localhost:8000/admin/report/plan-due-repayments'><i
                                                class="fa fa-plus-square-o"></i> <span>Plan Due Repayments</span></a>
                                </li>
                                <li><a href='http://localhost:8000/admin/report/plan-late-repayments'><i
                                                class="fa fa-plus-square-o"></i> <span>Plan Late Repayments</span></a>
                                </li>
                                <li><a href='http://localhost:8000/admin/report/payment-deposits'><i
                                                class="fa fa-plus-square-o"></i> <span>Payment Deposits</span></a></li>
                                <li><a href='http://localhost:8000/admin/report/loan-disbursements'><i
                                                class="fa fa-plus-square-o"></i> <span>Loan Disbursements</span></a>
                                </li>
                                <li><a href='http://localhost:8000/admin/report/loan-repayments'><i
                                                class="fa fa-plus-square-o"></i> <span>Loan Repayments</span></a></li>
                                <li><a href='http://localhost:8000/admin/report/plan-disbursements'><i
                                                class="fa fa-plus-square-o"></i> <span>Plan Disbursements</span></a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Saving Reports</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href='http://localhost:8000/admin/report/saving-deposit'><i
                                                class="fa fa-plus-square-o"></i> <span>Saving Deposit</span></a></li>
                                <li><a href='http://localhost:8000/admin/report/saving-withdrawal'><i
                                                class="fa fa-plus-square-o"></i> <span>Saving Withdrawal</span></a></li>
                                <li><a href='http://localhost:8000/admin/report/saving-accrued-interest'><i
                                                class="fa fa-plus-square-o"></i>
                                        <span>Saving Accrued Interest</span></a></li>
                            </ul>
                        </li>

                        <li class="treeview"><a href="#"><i class="fa fa-tag"></i> <span>Officer Reports</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href='http://localhost:8000/admin/report/officer-disbursement'><i
                                                class="fa fa-plus-square-o"></i> <span>Disbursement By C.O</span></a>
                                </li>
                                <li><a href='http://localhost:8000/admin/report/officer-collection'><i
                                                class="fa fa-plus-square-o"></i> <span>C.O Collections</span></a></li>
                                <li><a href='http://localhost:8000/admin/report/officer-transaction'><i
                                                class="fa fa-plus-square-o"></i> <span>C.O Transactions</span></a></li>
                                <li><a href='http://localhost:8000/admin/report/staff-performance'><i
                                                class="fa fa-plus-square-o"></i> <span>Staff Performance</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>


                <li class="treeview">
                    <a href="#"><i class="fa fa-group"></i> <span>USER</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">


                        <li><a href="http://localhost:8000/admin/user"><i class="fa fa-tag"></i> <span>Users</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/role"><i class="fa fa-tag"></i> <span>Roles</span></a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-cog"></i> <span>GENERAL SETTING</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">

                        <li><a href='http://localhost:8000/admin/setting'><i class='fa fa-tag'></i>
                                <span>Settings</span></a></li>

                        <li><a href="http://localhost:8000/admin/branch"><i class='fa fa-tag'></i>
                                <span>BRANCH</span></a></li>
                        <li><a href="http://localhost:8000/admin/centerleader"><i class='fa fa-tag'></i>
                                <span>CENTER</span></a></li>
                        <li><a href="http://localhost:8000/admin/holidayschedule"><i class='fa fa-tag'></i> <span>HOLIDAY</span></a>
                        </li>
                        <li><a href="http://localhost:8000/admin/audit"><i class='fa fa-area-chart'></i> <span>AUDIT TRAIL</span></a>
                        </li>
                    </ul>
                </li>


                <!-- ======================================= -->

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <span class="text-capitalize">loans</span>
                <small>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="http://localhost:8000/admin/loandisbursement" class="hidden-print my-back"><i
                                class="fa fa-angle-double-left"></i> <span></span></a>
                </small>


            </h1>

        </section>

        <!-- Main content -->
        <section class="content">


            <div class="row m-t-20">
                <div class="col-md-12">
                    <!-- Default box -->


                    <form method="post"
                          action="http://localhost:8000/admin/loandisbursement"
                    >
                        <input type="hidden" name="_token" value="K21BtJtjUL5WoFJkgNNrMqcanCQtRRg5HzbQDOYL">
                        <div class="col-md-12">
                            <div class="row display-flex-wrap">
                                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                                <input type="hidden" name="http_referrer"
                                       value=http://localhost:8000/admin/active-member-client>


                                <div class="tab-container col-xs-12">

                                    <div class="nav-tabs-custom" id="form_tabs">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#tab_client" aria-controls="tab_client" role="tab"
                                                   tab_name="client" data-toggle="tab" class="tab_toggler">Client</a>
                                            </li>
                                            <li role="presentation" class="">
                                                <a href="#tab_account" aria-controls="tab_account" role="tab"
                                                   tab_name="account" data-toggle="tab" class="tab_toggler">Account</a>
                                            </li>
                                            <li role="presentation" class="">
                                                <a href="#tab_photo" aria-controls="tab_photo" role="tab"
                                                   tab_name="photo" data-toggle="tab" class="tab_toggler">Photo</a>
                                            </li>
                                            <li role="presentation" class="">
                                                <a href="#tab_paymentschedule" aria-controls="tab_paymentschedule"
                                                   role="tab" tab_name="paymentschedule" data-toggle="tab"
                                                   class="tab_toggler">PaymentSchedule</a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <div class="tab-content box col-md-12">

                                    <div role="tabpanel" class="tab-pane active" id="tab_client">

                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- select2 from ajax -->
                                        <div class="form-group col-md-3 client_id  required">
                                            <label>Client ID</label>
                                            <select name="client_id" style="width: 100%" id="select2_ajax_client_id"
                                                    class="form-control">

                                            </select>
                                        </div>

                                        <div class="form-group col-md-3 ">
                                            <label>Client nrc</label>
                                            <input readonly type="text" name="client_nrc_number" value=""
                                                   class="form-control">
                                        </div>

                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- text input -->
                                        <div class="form-group col-md-3 ">
                                            <label>Client Name</label>
                                            <input readonly type="text" name="client_name" value=""
                                                   class="form-control">
                                        </div>

                                        <div class="form-group col-md-3 ">
                                            <label>Client phone</label>
                                            <input readonly type="text" name="client_phone" value=""
                                                   class="form-control">
                                        </div>

                                        <div class="form-group col-md-3 ">
                                            <label>Saving amount</label>
                                            <input readonly type="text" name="available_balance" value=""
                                                   class="form-control">
                                        </div>

                                        <div class="form-group col-md-3 ">
                                            <label>You are a group leader</label>
                                            <select name="you_are_a_group_leader" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 ">
                                            <label>You are a center leader</label>
                                            <select name="you_are_a_center_leader" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3 guarantor ">
                                            <label>Guarantor</label>
                                            <div class="input-group">
                                                <select name="guarantor_id" style="width: 100%"
                                                        id="select2_ajax_guarantor_id" class="form-control">
                                                </select>
                                                <div class="input-group-addon">
                                                    <a href="" data-remote="false" data-toggle="modal"
                                                       data-target="#show-create-guarantor"><span
                                                                class="glyphicon glyphicon-plus"></span></a>
                                                </div>
                                            </div>
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
                                            <input readonly type="text" name="g_nrc_number" value="" class="form-control">
                                        </div>


                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- text input -->
                                        <div class="form-group col-md-3 ">
                                            <label>Guarantor Name</label>
                                            <input readonly type="text" name="g_name" value="" class="form-control">
                                        </div>

                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- text input -->
                                        <div class="form-group col-md-3 ">
                                            <label>Guarantor ID</label>
                                            <input readonly type="text" name="g_id" value="" class="form-control">
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="tab_account">

                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- text input -->
                                        <div class="form-group col-md-4  required">
                                            <label>Loan Number</label>
                                            <input type="text" name="disbursement_number" value="LID-00016878" class="form-control">
                                        </div>


                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- select2 from ajax -->

                                        <div class="form-group col-md-4  required">
                                            <label>Branch</label>
                                            <select name="branch_id" style="width: 100%" id="select2_ajax_branch_id" class="form-control">
                                                <option value="" selected>
                                                    Select a branch
                                                </option>
                                            </select>


                                        </div>


                                        <div class="form-group col-md-4 ">
                                            <label>Center Name</label>
                                            <select name="center_leader_id" style="width: 100%" id="select2_ajax_center_leader_id" class="form-control">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4  required">
                                            <label>Loan Officer Name</label>
                                            <select name="loan_officer_id" style="width: 100%" id="select2_ajax_loan_officer_id" class="form-control">
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4 loan_product  required">
                                            <label>Loan Product</label>
                                            <select name="loan_production_id" style="width: 100%" id="select2_ajax_loan_production_id" class="form-control">

                                            </select>

                                        </div>


                                        <!-- include field specific select2 js-->


                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- bootstrap datepicker input -->


                                        <div class="form-group col-md-4  required">
                                            <input type="hidden" name="loan_application_date" value="2020-04-02">
                                            <label>Loan application date</label>
                                            <div class="input-group date">
                                                <input id="my-loan_application_date" data-bs-datepicker="{&quot;format&quot;:&quot;yyyy-mm-dd&quot;}" type="text" class="form-control">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                            </div>

                                        </div>


                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- bootstrap datepicker input -->


                                        <div class="form-group col-md-4  required">
                                            <input type="hidden" name="first_installment_date" value="2020-04-02">
                                            <label>First installment date</label>
                                            <div class="input-group date">
                                                <input id="my-first_installment_date" data-bs-datepicker="{&quot;format&quot;:&quot;yyyy-mm-dd&quot;}" type="text" class="form-control">
                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                            </div>


                                        </div>


                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- number input -->
                                        <div class="form-group col-md-4  required">
                                            <label for="loan_amount">Loan Amount</label>
                                            <input number="number" type="text" name="loan_amount" id="loan_amount" value="" class="form-control">
                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- number input -->
                                        <div class="form-group col-md-4  required">
                                            <label for="interest_rate">Interest rate</label>
                                            <input number="number" type="text" name="interest_rate" id="interest_rate" value="" class="form-control">

                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- enum -->
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
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- enum -->
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
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- number input -->
                                        <div class="form-group col-md-4  required">
                                            <label for="loan_term_value">Loan Term Value</label>
                                            <input type="number" name="loan_term_value" id="loan_term_value" value="" class="form-control">

                                        </div>

                                        <div class="form-group col-md-4  required">
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
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- select -->

                                        <div class="form-group col-md-4  required">
                                            <label>Currency</label>
                                            <select name="currency_id" class="form-control">
                                                <option value="1">Kyat</option>
                                                <option value="2">US Dollar</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4  required">
                                            <label>Transaction Type</label>
                                            <select name="transaction_type_id" class="form-control">
                                                <option value="1">Cash</option>
                                            </select>
                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- select2 from ajax -->

                                        <div class="form-group col-md-3 group_loan ">
                                            <label>Group ID</label>
                                            <div class="input-group"><select name="group_loan_id" style="width: 100%" id="select2_ajax_group_loan_id" class="form-control">
                                                </select>
                                                <div class="input-group-addon">
                                                    <a href="" data-remote="false" data-toggle="modal" id="modal-show" data-target="#show-create-group"><span class="glyphicon glyphicon-plus"></span></a>
                                                </div>
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


                                        <!-- include field specific select2 js-->


                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- view field -->

                                        <div class="form-group col-md-6 ">
                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- view field -->

                                        <div class="form-group col-xs-12">
                                            <div id="service-list">

                                            </div>
                                            <div id="service-list2">

                                            </div>
                                        </div>

                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="tab_photo">

                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- view field -->

                                        <div class="form-group col-md-6 ">
                                            <div class="col-md-6">

                                                <label>Guarantor Photo</label>

                                                <div>
                                                    <img width="300" height="300" class="g_image"
                                                         src="http://localhost:8000/No_Image_Available.jpg"/>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- view field -->

                                        <div class="form-group col-md-6 ">
                                            <div class="col-md-6">
                                                <label>Client Photo</label>
                                                <div>
                                                    <img width="300" height="300" class="c_image"
                                                         src="http://localhost:8000/No_Image_Available.jpg"/>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- hidden input -->
                                        <div class="hidden ">
                                            <input type="hidden" class="interest_rate_min" value="" class="form-control">
                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- hidden input -->
                                        <div class="hidden">
                                            <input type="hidden" class="interest_rate_max" value=""
                                                   class="form-control">
                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- hidden input -->
                                        <div class="hidden ">
                                            <input type="hidden" class="principal_min" value="" class="form-control">
                                        </div>
                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- hidden input -->
                                        <div class="hidden ">
                                            <input type="hidden" class="principal_max" value="" class="form-control">
                                        </div>

                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="tab_paymentschedule">

                                        <!-- load the view from type and view_namespace attribute if set -->

                                        <!-- view field -->

                                        <div class="form-group col-md-12 ">
                                            <table class="table-data" id="table-data" style="margin-top: 20px;">
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
                            <div class="">

                                <div id="saveActions" class="form-group">

                                    <input type="hidden" name="save_action" value="save_and_back">

                                    <div class="btn-group">

                                        <button type="submit" class="btn btn-success">
                                            <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
                                            &nbsp;
                                            <span data-value="save_and_back">Save and back</span>
                                        </button>

                                        <button type="button" class="btn btn-success dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">&#x25BC;</span>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0);" data-value="save_and_edit">Save and edit
                                                    this item</a></li>
                                            <li><a href="javascript:void(0);" data-value="save_and_new">Save and new
                                                    item</a></li>
                                        </ul>

                                    </div>

                                    <a href="http://localhost:8000/admin/loandisbursement" class="btn btn-default"><span
                                                class="fa fa-ban"></span> &nbsp;Cancel</a>
                                </div>

                            </div><!-- /.box-footer-->

                        </div><!-- /.box -->
                    </form>
                </div>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer text-sm clearfix">
    </footer>
</div>
<!-- ./wrapper -->


<!-- jQuery 3.3.1 -->
<script src="http://localhost:8000/vendor/adminlte/bower_components/jquery/dist/jquery.min.js"></script>


<!-- Bootstrap 3.3.7 -->
<script src="http://localhost:8000/vendor/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="http://localhost:8000/vendor/adminlte/plugins/pace/pace.min.js"></script>
<script src="http://localhost:8000/vendor/adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<script src="http://localhost:8000/vendor/adminlte/dist/js/adminlte.js"></script>

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

<script src="http://localhost:8000/vendor/backpack/pnotify/pnotify.custom.min.js"></script>


<script type="text/javascript">
    jQuery(document).ready(function ($) {

        PNotify.prototype.options.styling = "bootstrap3";
        PNotify.prototype.options.styling = "fontawesome";

    });
</script>
<script src="http://localhost:8000/vendor/backpack/crud/js/crud.js"></script>
<script src="http://localhost:8000/vendor/backpack/crud/js/form.js"></script>
<script src="http://localhost:8000/vendor/backpack/crud/js/create.js"></script>

<!-- CRUD FORM CONTENT - crud_fields_scripts stack -->
<!-- include select2 js-->
<script src="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/js/select2.min.js"></script>
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
                        url: "http://localhost:8000/api/get-client",
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


    });
</script>
<!-- include select2 js-->
<script src="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/js/select2.min.js"></script>
<style>
    .modal-dialog-x {
        width: 95%;
        padding: 0;
    }

    .modal-content {
        width: 98%;
    }
</style>
<script>
    jQuery(document).ready(function ($) {
        // trigger select2 for each untriggered select2 box
        $("#select2_ajax_guarantor_id").each(function (i, obj) {
            if (!$(obj).hasClass("select2-hidden-accessible")) {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select a guarantor",
                    minimumInputLength: "0",


                    allowClear: true,
                    ajax: {
                        url: "http://localhost:8000/api/get-guarantor",
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
    });
</script>

<script>
    $("#show-create-guarantor").on("show.bs.modal", function (e) {
        var link = 'http://localhost:8000/admin/guarantor/create?is_frame=1';
        $(this).find('iframe').attr('src', link);
    });
</script>
<!-- include select2 js-->
<script src="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/js/select2.min.js"></script>
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
                        url: "http://localhost:8000/api/get-branch",
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

    });
</script>
<!-- include select2 js-->
<script src="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/js/select2.min.js"></script>
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
                        url: "http://localhost:8000/api/get-center-leader-name",
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
<!-- include select2 js-->
<script src="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/js/select2.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        // trigger select2 for each untriggered select2 box
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
                        url: "http://localhost:8000/api/get-user",
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
                        url: "http://localhost:8000/api/get-loan-product",
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
<script src="http://localhost:8000/vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
    if (jQuery.ui) {
        var datepicker = $.fn.datepicker.noConflict();
        $.fn.bootstrapDP = datepicker;
    } else {
        $.fn.bootstrapDP = $.fn.datepicker;
    }

    var dateFormat = function () {
        var a = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
            b = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
            c = /[^-+\dA-Z]/g, d = function (a, b) {
                for (a = String(a), b = b || 2; a.length < b;) a = "0" + a;
                return a
            };
        return function (e, f, g) {
            var h = dateFormat;
            if (1 != arguments.length || "[object String]" != Object.prototype.toString.call(e) || /\d/.test(e) || (f = e, e = void 0), e = e ? new Date(e) : new Date, isNaN(e)) throw SyntaxError("invalid date");
            f = String(h.masks[f] || f || h.masks.default), "UTC:" == f.slice(0, 4) && (f = f.slice(4), g = !0);
            var i = g ? "getUTC" : "get", j = e[i + "Date"](), k = e[i + "Day"](), l = e[i + "Month"](),
                m = e[i + "FullYear"](), n = e[i + "Hours"](), o = e[i + "Minutes"](), p = e[i + "Seconds"](),
                q = e[i + "Milliseconds"](), r = g ? 0 : e.getTimezoneOffset(), s = {
                    d: j,
                    dd: d(j),
                    ddd: h.i18n.dayNames[k],
                    dddd: h.i18n.dayNames[k + 7],
                    m: l + 1,
                    mm: d(l + 1),
                    mmm: h.i18n.monthNames[l],
                    mmmm: h.i18n.monthNames[l + 12],
                    yy: String(m).slice(2),
                    yyyy: m,
                    h: n % 12 || 12,
                    hh: d(n % 12 || 12),
                    H: n,
                    HH: d(n),
                    M: o,
                    MM: d(o),
                    s: p,
                    ss: d(p),
                    l: d(q, 3),
                    L: d(q > 99 ? Math.round(q / 10) : q),
                    t: n < 12 ? "a" : "p",
                    tt: n < 12 ? "am" : "pm",
                    T: n < 12 ? "A" : "P",
                    TT: n < 12 ? "AM" : "PM",
                    Z: g ? "UTC" : (String(e).match(b) || [""]).pop().replace(c, ""),
                    o: (r > 0 ? "-" : "+") + d(100 * Math.floor(Math.abs(r) / 60) + Math.abs(r) % 60, 4),
                    S: ["th", "st", "nd", "rd"][j % 10 > 3 ? 0 : (j % 100 - j % 10 != 10) * j % 10]
                };
            return f.replace(a, function (a) {
                return a in s ? s[a] : a.slice(1, a.length - 1)
            })
        }
    }();
    dateFormat.masks = {
        default: "ddd mmm dd yyyy HH:MM:ss",
        shortDate: "m/d/yy",
        mediumDate: "mmm d, yyyy",
        longDate: "mmmm d, yyyy",
        fullDate: "dddd, mmmm d, yyyy",
        shortTime: "h:MM TT",
        mediumTime: "h:MM:ss TT",
        longTime: "h:MM:ss TT Z",
        isoDate: "yyyy-mm-dd",
        isoTime: "HH:MM:ss",
        isoDateTime: "yyyy-mm-dd'T'HH:MM:ss",
        isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
    }, dateFormat.i18n = {
        dayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
    }, Date.prototype.format = function (a, b) {
        return dateFormat(this, a, b)
    };

    jQuery(document).ready(function ($) {
        $('#my-loan_application_date').each(function () {

            var $fake = $(this),
                $field = $fake.parents('.form-group').find('input[type="hidden"]'),
                $customConfig = $.extend({
                    format: 'dd/mm/yyyy'
                }, $fake.data('bs-datepicker'));
            $picker = $fake.bootstrapDP($customConfig);

            var $existingVal = $field.val();

            if ($existingVal.length) {
                // Passing an ISO-8601 date string (YYYY-MM-DD) to the Date constructor results in
                // varying behavior across browsers. Splitting and passing in parts of the date
                // manually gives us more defined behavior.
                // See https://stackoverflow.com/questions/2587345/why-does-date-parse-give-incorrect-results
                var parts = $existingVal.split('-')
                var year = parts[0]
                var month = parts[1] - 1 // Date constructor expects a zero-indexed month
                var day = parts[2]
                preparedDate = new Date(year, month, day).format($customConfig.format);
                $fake.val(preparedDate);
                $picker.bootstrapDP('update', preparedDate);
            }

            $fake.on('keydown', function (e) {
                e.preventDefault();
                return false;
            });

            $picker.on('show hide change', function (e) {
                if (e.date) {
                    var sqlDate = e.format('yyyy-mm-dd');
                } else {
                    try {
                        var sqlDate = $fake.val();

                        if ($customConfig.format === 'dd/mm/yyyy') {
                            sqlDate = new Date(sqlDate.split('/')[2], sqlDate.split('/')[1] - 1, sqlDate.split('/')[0]).format('yyyy-mm-dd');
                        }
                    } catch (e) {
                        if ($fake.val()) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Whoops!',
                                text: 'Sorry we did not recognise that date format, please make sure it uses a yyyy mm dd combination',
                                type: 'error',
                                icon: false
                            });
                        }
                    }
                }

                $field.val(sqlDate);

                loan_application_date_change(sqlDate);
            });
        });
    });
</script>


<script>
    /*if (jQuery.ui) {
        var datepicker = $.fn.datepicker.noConflict();
        $.fn.bootstrapDP = datepicker;
    } else {
        $.fn.bootstrapDP = $.fn.datepicker;
    }

    var dateFormat=function(){var a=/d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,b=/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,c=/[^-+\dA-Z]/g,d=function(a,b){for(a=String(a),b=b||2;a.length<b;)a="0"+a;return a};return function(e,f,g){var h=dateFormat;if(1!=arguments.length||"[object String]"!=Object.prototype.toString.call(e)||/\d/.test(e)||(f=e,e=void 0),e=e?new Date(e):new Date,isNaN(e))throw SyntaxError("invalid date");f=String(h.masks[f]||f||h.masks.default),"UTC:"==f.slice(0,4)&&(f=f.slice(4),g=!0);var i=g?"getUTC":"get",j=e[i+"Date"](),k=e[i+"Day"](),l=e[i+"Month"](),m=e[i+"FullYear"](),n=e[i+"Hours"](),o=e[i+"Minutes"](),p=e[i+"Seconds"](),q=e[i+"Milliseconds"](),r=g?0:e.getTimezoneOffset(),s={d:j,dd:d(j),ddd:h.i18n.dayNames[k],dddd:h.i18n.dayNames[k+7],m:l+1,mm:d(l+1),mmm:h.i18n.monthNames[l],mmmm:h.i18n.monthNames[l+12],yy:String(m).slice(2),yyyy:m,h:n%12||12,hh:d(n%12||12),H:n,HH:d(n),M:o,MM:d(o),s:p,ss:d(p),l:d(q,3),L:d(q>99?Math.round(q/10):q),t:n<12?"a":"p",tt:n<12?"am":"pm",T:n<12?"A":"P",TT:n<12?"AM":"PM",Z:g?"UTC":(String(e).match(b)||[""]).pop().replace(c,""),o:(r>0?"-":"+")+d(100*Math.floor(Math.abs(r)/60)+Math.abs(r)%60,4),S:["th","st","nd","rd"][j%10>3?0:(j%100-j%10!=10)*j%10]};return f.replace(a,function(a){return a in s?s[a]:a.slice(1,a.length-1)})}}();dateFormat.masks={default:"ddd mmm dd yyyy HH:MM:ss",shortDate:"m/d/yy",mediumDate:"mmm d, yyyy",longDate:"mmmm d, yyyy",fullDate:"dddd, mmmm d, yyyy",shortTime:"h:MM TT",mediumTime:"h:MM:ss TT",longTime:"h:MM:ss TT Z",isoDate:"yyyy-mm-dd",isoTime:"HH:MM:ss",isoDateTime:"yyyy-mm-dd'T'HH:MM:ss",isoUtcDateTime:"UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"},dateFormat.i18n={dayNames:["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],monthNames:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","January","February","March","April","May","June","July","August","September","October","November","December"]},Date.prototype.format=function(a,b){return dateFormat(this,a,b)};
*/
    jQuery(document).ready(function ($) {
        $('#my-first_installment_date').each(function () {

            var $fake = $(this),
                $field = $fake.parents('.form-group').find('input[type="hidden"]'),
                $customConfig = $.extend({
                    format: 'dd/mm/yyyy'
                }, $fake.data('bs-datepicker'));
            $picker = $fake.bootstrapDP($customConfig);

            var $existingVal = $field.val();

            if ($existingVal.length) {
                // Passing an ISO-8601 date string (YYYY-MM-DD) to the Date constructor results in
                // varying behavior across browsers. Splitting and passing in parts of the date
                // manually gives us more defined behavior.
                // See https://stackoverflow.com/questions/2587345/why-does-date-parse-give-incorrect-results
                var parts = $existingVal.split('-')
                var year = parts[0]
                var month = parts[1] - 1 // Date constructor expects a zero-indexed month
                var day = parts[2]
                preparedDate = new Date(year, month, day).format($customConfig.format);
                $fake.val(preparedDate);
                $picker.bootstrapDP('update', preparedDate);
            }

            $fake.on('keydown', function (e) {
                e.preventDefault();
                return false;
            });

            $picker.on('show hide change', function (e) {
                if (e.date) {
                    var sqlDate = e.format('yyyy-mm-dd');
                } else {
                    try {
                        var sqlDate = $fake.val();

                        if ($customConfig.format === 'dd/mm/yyyy') {
                            sqlDate = new Date(sqlDate.split('/')[2], sqlDate.split('/')[1] - 1, sqlDate.split('/')[0]).format('yyyy-mm-dd');
                        }
                    } catch (e) {
                        if ($fake.val()) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Whoops!',
                                text: 'Sorry we did not recognise that date format, please make sure it uses a yyyy mm dd combination',
                                type: 'error',
                                icon: false
                            });
                        }
                    }
                }

                $field.val(sqlDate);

                first_installment_date_change(sqlDate);
            });
        });
    });
</script>
<!-- include select2 js-->
<script src="http://localhost:8000/vendor/adminlte/bower_components/select2/dist/js/select2.min.js"></script>
<script>
    jQuery(document).ready(function ($) {

        $("#modal-show").on('click', function () {
            /*$("#show-create-group").modal('show');*/

        });
        // trigger select2 for each untriggered select2 box
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

                        url: "http://localhost:8000/api/get-group-loan",
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

    });
</script>
<script>
    $("#show-create-group").on("show.bs.modal", function (e) {

        var link = 'http://localhost:8000/admin/grouploan/create?is_frame=1';
        $(this).find('iframe').attr('src', link);
    });
</script>
<script>
    $(function () {
        $('.group_loan').hide();


        $('body').on('change', '[name="loan_production_id"]', function () {

            var lp_id = $('[name="loan_production_id"]').val();

            $.ajax({
                type: 'GET',
                url: 'http://localhost:8000/api/get-loan-product' + '/' + lp_id,
                data: {
                    g_id: lp_id,
                },
                success: function (res) {
                    // console.log(res.compulsory_id);

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
        $('[name="repayment_term"]').on('change', function () {
            var repayment = $(this).val();
            var date = $('[name="loan_application_date"]').val();

            $.ajax({
                type: 'GET',
                url: 'http://localhost:8000/api/get-first-date-payment',
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
            url: 'http://localhost:8000/api/get-payment-schedule',
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
            url: 'http://localhost:8000/api/get-charge-service',
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
            url: 'http://localhost:8000/api/get-charge-service',
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
                url: 'http://localhost:8000/api/get-guarantor' + '/' + g_id,
                data: {
                    g_id: g_id,
                },
                success: function (res) {
                    $('[name="g_nrc_number"]').val(res.nrc_number);
                    $('[name="g_name"]').val(res.full_name_mm);
                    $('[name="g_id"]').val(res.id);
                    if (res.photo) {
                        $('.g_image').prop('src', 'http://localhost:8000/' + '/' + res.photo);
                    }

                }

            });


        });

        $('body').on('change', '.guarantor2', function () {

            var g_id = $('[name="guarantor2_id"]').val();

            $.ajax({
                type: 'GET',
                url: 'http://localhost:8000/api/get-guarantor' + '/' + g_id,
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
                url: 'http://localhost:8000/api/get-client-g' + '/' + c_id,
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
                        $('.c_image').prop('src', 'http://localhost:8000/' + '/' + res.photo_of_client);//group_loan_id

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
    var current_url = "http://localhost:8000/admin/loandisbursement/create";
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


        $('.change-branch-top').on('change', function () {
            var branch_id = $(this).val();

            $.ajax({
                type: 'GET',
                url: 'http://localhost:8000/api/change-branch',
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


<script src="http://localhost:8000/vendor/adminlte/plugins/jquery/jquery.jscroll.min.js"></script>

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

<!-- JavaScripts -->

<link rel='stylesheet' type='text/css' property='stylesheet'
      href='//localhost:8000/_debugbar/assets/stylesheets?v=1579246456'>
<script type='text/javascript' src='//localhost:8000/_debugbar/assets/javascript?v=1579246456'></script>
<script type="text/javascript">jQuery.noConflict(true);</script>
<script> Sfdump = window.Sfdump || (function (doc) {
        var refStyle = doc.createElement('style'), rxEsc = /([.*+?^${}()|\[\]\/\\])/g,
            idRx = /\bsf-dump-\d+-ref[012]\w+\b/,
            keyHint = 0 <= navigator.platform.toUpperCase().indexOf('MAC') ? 'Cmd' : 'Ctrl',
            addEventListener = function (e, n, cb) {
                e.addEventListener(n, cb, false);
            };
        (doc.documentElement.firstElementChild || doc.documentElement.children[0]).appendChild(refStyle);
        if (!doc.addEventListener) {
            addEventListener = function (element, eventName, callback) {
                element.attachEvent('on' + eventName, function (e) {
                    e.preventDefault = function () {
                        e.returnValue = false;
                    };
                    e.target = e.srcElement;
                    callback(e);
                });
            };
        }

        function toggle(a, recursive) {
            var s = a.nextSibling || {}, oldClass = s.className, arrow, newClass;
            if (/\bsf-dump-compact\b/.test(oldClass)) {
                arrow = '▼';
                newClass = 'sf-dump-expanded';
            } else if (/\bsf-dump-expanded\b/.test(oldClass)) {
                arrow = '▶';
                newClass = 'sf-dump-compact';
            } else {
                return false;
            }
            if (doc.createEvent && s.dispatchEvent) {
                var event = doc.createEvent('Event');
                event.initEvent('sf-dump-expanded' === newClass ? 'sfbeforedumpexpand' : 'sfbeforedumpcollapse', true, false);
                s.dispatchEvent(event);
            }
            a.lastChild.innerHTML = arrow;
            s.className = s.className.replace(/\bsf-dump-(compact|expanded)\b/, newClass);
            if (recursive) {
                try {
                    a = s.querySelectorAll('.' + oldClass);
                    for (s = 0; s < a.length; ++s) {
                        if (-1 == a[s].className.indexOf(newClass)) {
                            a[s].className = newClass;
                            a[s].previousSibling.lastChild.innerHTML = arrow;
                        }
                    }
                } catch (e) {
                }
            }
            return true;
        };

        function collapse(a, recursive) {
            var s = a.nextSibling || {}, oldClass = s.className;
            if (/\bsf-dump-expanded\b/.test(oldClass)) {
                toggle(a, recursive);
                return true;
            }
            return false;
        };

        function expand(a, recursive) {
            var s = a.nextSibling || {}, oldClass = s.className;
            if (/\bsf-dump-compact\b/.test(oldClass)) {
                toggle(a, recursive);
                return true;
            }
            return false;
        };

        function collapseAll(root) {
            var a = root.querySelector('a.sf-dump-toggle');
            if (a) {
                collapse(a, true);
                expand(a);
                return true;
            }
            return false;
        }

        function reveal(node) {
            var previous, parents = [];
            while ((node = node.parentNode || {}) && (previous = node.previousSibling) && 'A' === previous.tagName) {
                parents.push(previous);
            }
            if (0 !== parents.length) {
                parents.forEach(function (parent) {
                    expand(parent);
                });
                return true;
            }
            return false;
        }

        function highlight(root, activeNode, nodes) {
            resetHighlightedNodes(root);
            Array.from(nodes || []).forEach(function (node) {
                if (!/\bsf-dump-highlight\b/.test(node.className)) {
                    node.className = node.className + ' sf-dump-highlight';
                }
            });
            if (!/\bsf-dump-highlight-active\b/.test(activeNode.className)) {
                activeNode.className = activeNode.className + ' sf-dump-highlight-active';
            }
        }

        function resetHighlightedNodes(root) {
            Array.from(root.querySelectorAll('.sf-dump-str, .sf-dump-key, .sf-dump-public, .sf-dump-protected, .sf-dump-private')).forEach(function (strNode) {
                strNode.className = strNode.className.replace(/\bsf-dump-highlight\b/, '');
                strNode.className = strNode.className.replace(/\bsf-dump-highlight-active\b/, '');
            });
        }

        return function (root, x) {
            root = doc.getElementById(root);
            var indentRx = new RegExp('^(' + (root.getAttribute('data-indent-pad') || ' ').replace(rxEsc, '\\$1') + ')+', 'm'),
                options = {"maxDepth": 1, "maxStringLength": 160, "fileLinkFormat": false},
                elt = root.getElementsByTagName('A'), len = elt.length, i = 0, s, h, t = [];
            while (i < len) t.push(elt[i++]);
            for (i in x) {
                options[i] = x[i];
            }

            function a(e, f) {
                addEventListener(root, e, function (e, n) {
                    if ('A' == e.target.tagName) {
                        f(e.target, e);
                    } else if ('A' == e.target.parentNode.tagName) {
                        f(e.target.parentNode, e);
                    } else if ((n = e.target.nextElementSibling) && 'A' == n.tagName) {
                        if (!/\bsf-dump-toggle\b/.test(n.className)) {
                            n = n.nextElementSibling;
                        }
                        f(n, e, true);
                    }
                });
            };

            function isCtrlKey(e) {
                return e.ctrlKey || e.metaKey;
            }

            function xpathString(str) {
                var parts = str.match(/[^'"]+|['"]/g).map(function (part) {
                    if ("'" == part) {
                        return '"\'"';
                    }
                    if ('"' == part) {
                        return "'\"'";
                    }
                    return "'" + part + "'";
                });
                return "concat(" + parts.join(",") + ", '')";
            }

            function xpathHasClass(className) {
                return "contains(concat(' ', normalize-space(@class), ' '), ' " + className + " ')";
            }

            addEventListener(root, 'mouseover', function (e) {
                if ('' != refStyle.innerHTML) {
                    refStyle.innerHTML = '';
                }
            });
            a('mouseover', function (a, e, c) {
                if (c) {
                    e.target.style.cursor = "pointer";
                } else if (a = idRx.exec(a.className)) {
                    try {
                        refStyle.innerHTML = '.phpdebugbar pre.sf-dump .' + a[0] + '{background-color: #B729D9; color: #FFF !important; border-radius: 2px}';
                    } catch (e) {
                    }
                }
            });
            a('click', function (a, e, c) {
                if (/\bsf-dump-toggle\b/.test(a.className)) {
                    e.preventDefault();
                    if (!toggle(a, isCtrlKey(e))) {
                        var r = doc.getElementById(a.getAttribute('href').substr(1)), s = r.previousSibling,
                            f = r.parentNode, t = a.parentNode;
                        t.replaceChild(r, a);
                        f.replaceChild(a, s);
                        t.insertBefore(s, r);
                        f = f.firstChild.nodeValue.match(indentRx);
                        t = t.firstChild.nodeValue.match(indentRx);
                        if (f && t && f[0] !== t[0]) {
                            r.innerHTML = r.innerHTML.replace(new RegExp('^' + f[0].replace(rxEsc, '\\$1'), 'mg'), t[0]);
                        }
                        if (/\bsf-dump-compact\b/.test(r.className)) {
                            toggle(s, isCtrlKey(e));
                        }
                    }
                    if (c) {
                    } else if (doc.getSelection) {
                        try {
                            doc.getSelection().removeAllRanges();
                        } catch (e) {
                            doc.getSelection().empty();
                        }
                    } else {
                        doc.selection.empty();
                    }
                } else if (/\bsf-dump-str-toggle\b/.test(a.className)) {
                    e.preventDefault();
                    e = a.parentNode.parentNode;
                    e.className = e.className.replace(/\bsf-dump-str-(expand|collapse)\b/, a.parentNode.className);
                }
            });
            elt = root.getElementsByTagName('SAMP');
            len = elt.length;
            i = 0;
            while (i < len) t.push(elt[i++]);
            len = t.length;
            for (i = 0; i < len; ++i) {
                elt = t[i];
                if ('SAMP' == elt.tagName) {
                    a = elt.previousSibling || {};
                    if ('A' != a.tagName) {
                        a = doc.createElement('A');
                        a.className = 'sf-dump-ref';
                        elt.parentNode.insertBefore(a, elt);
                    } else {
                        a.innerHTML += ' ';
                    }
                    a.title = (a.title ? a.title + '\n[' : '[') + keyHint + '+click] Expand all children';
                    a.innerHTML += '<span>▼</span>';
                    a.className += ' sf-dump-toggle';
                    x = 1;
                    if ('sf-dump' != elt.parentNode.className) {
                        x += elt.parentNode.getAttribute('data-depth') / 1;
                    }
                    elt.setAttribute('data-depth', x);
                    var className = elt.className;
                    elt.className = 'sf-dump-expanded';
                    if (className ? 'sf-dump-expanded' !== className : (x > options.maxDepth)) {
                        toggle(a);
                    }
                } else if (/\bsf-dump-ref\b/.test(elt.className) && (a = elt.getAttribute('href'))) {
                    a = a.substr(1);
                    elt.className += ' ' + a;
                    if (/[\[{]$/.test(elt.previousSibling.nodeValue)) {
                        a = a != elt.nextSibling.id && doc.getElementById(a);
                        try {
                            s = a.nextSibling;
                            elt.appendChild(a);
                            s.parentNode.insertBefore(a, s);
                            if (/^[@#]/.test(elt.innerHTML)) {
                                elt.innerHTML += ' <span>▶</span>';
                            } else {
                                elt.innerHTML = '<span>▶</span>';
                                elt.className = 'sf-dump-ref';
                            }
                            elt.className += ' sf-dump-toggle';
                        } catch (e) {
                            if ('&' == elt.innerHTML.charAt(0)) {
                                elt.innerHTML = '…';
                                elt.className = 'sf-dump-ref';
                            }
                        }
                    }
                }
            }
            if (doc.evaluate && Array.from && root.children.length > 1) {
                root.setAttribute('tabindex', 0);
                SearchState = function () {
                    this.nodes = [];
                    this.idx = 0;
                };
                SearchState.prototype = {
                    next: function () {
                        if (this.isEmpty()) {
                            return this.current();
                        }
                        this.idx = this.idx < (this.nodes.length - 1) ? this.idx + 1 : 0;
                        return this.current();
                    }, previous: function () {
                        if (this.isEmpty()) {
                            return this.current();
                        }
                        this.idx = this.idx > 0 ? this.idx - 1 : (this.nodes.length - 1);
                        return this.current();
                    }, isEmpty: function () {
                        return 0 === this.count();
                    }, current: function () {
                        if (this.isEmpty()) {
                            return null;
                        }
                        return this.nodes[this.idx];
                    }, reset: function () {
                        this.nodes = [];
                        this.idx = 0;
                    }, count: function () {
                        return this.nodes.length;
                    },
                };

                function showCurrent(state) {
                    var currentNode = state.current(), currentRect, searchRect;
                    if (currentNode) {
                        reveal(currentNode);
                        highlight(root, currentNode, state.nodes);
                        if ('scrollIntoView' in currentNode) {
                            currentNode.scrollIntoView(true);
                            currentRect = currentNode.getBoundingClientRect();
                            searchRect = search.getBoundingClientRect();
                            if (currentRect.top < (searchRect.top + searchRect.height)) {
                                window.scrollBy(0, -(searchRect.top + searchRect.height + 5));
                            }
                        }
                    }
                    counter.textContent = (state.isEmpty() ? 0 : state.idx + 1) + ' of ' + state.count();
                }

                var search = doc.createElement('div');
                search.className = 'sf-dump-search-wrapper sf-dump-search-hidden';
                search.innerHTML = ' <input type="text" class="sf-dump-search-input"> <span class="sf-dump-search-count">0 of 0<\/span> <button type="button" class="sf-dump-search-input-previous" tabindex="-1"> <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1683 1331l-166 165q-19 19-45 19t-45-19L896 965l-531 531q-19 19-45 19t-45-19l-166-165q-19-19-19-45.5t19-45.5l742-741q19-19 45-19t45 19l742 741q19 19 19 45.5t-19 45.5z"\/><\/svg> <\/button> <button type="button" class="sf-dump-search-input-next" tabindex="-1"> <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1683 808l-742 741q-19 19-45 19t-45-19L109 808q-19-19-19-45.5t19-45.5l166-165q19-19 45-19t45 19l531 531 531-531q19-19 45-19t45 19l166 165q19 19 19 45.5t-19 45.5z"\/><\/svg> <\/button> ';
                root.insertBefore(search, root.firstChild);
                var state = new SearchState();
                var searchInput = search.querySelector('.sf-dump-search-input');
                var counter = search.querySelector('.sf-dump-search-count');
                var searchInputTimer = 0;
                var previousSearchQuery = '';
                addEventListener(searchInput, 'keyup', function (e) {
                    var searchQuery = e.target.value; /* Don't perform anything if the pressed key didn't change the query */
                    if (searchQuery === previousSearchQuery) {
                        return;
                    }
                    previousSearchQuery = searchQuery;
                    clearTimeout(searchInputTimer);
                    searchInputTimer = setTimeout(function () {
                        state.reset();
                        collapseAll(root);
                        resetHighlightedNodes(root);
                        if ('' === searchQuery) {
                            counter.textContent = '0 of 0';
                            return;
                        }
                        var classMatches = ["sf-dump-str", "sf-dump-key", "sf-dump-public", "sf-dump-protected", "sf-dump-private",].map(xpathHasClass).join(' or ');
                        var xpathResult = doc.evaluate('.//span[' + classMatches + '][contains(translate(child::text(), ' + xpathString(searchQuery.toUpperCase()) + ', ' + xpathString(searchQuery.toLowerCase()) + '), ' + xpathString(searchQuery.toLowerCase()) + ')]', root, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
                        while (node = xpathResult.iterateNext()) state.nodes.push(node);
                        showCurrent(state);
                    }, 400);
                });
                Array.from(search.querySelectorAll('.sf-dump-search-input-next, .sf-dump-search-input-previous')).forEach(function (btn) {
                    addEventListener(btn, 'click', function (e) {
                        e.preventDefault();
                        -1 !== e.target.className.indexOf('next') ? state.next() : state.previous();
                        searchInput.focus();
                        collapseAll(root);
                        showCurrent(state);
                    })
                });
                addEventListener(root, 'keydown', function (e) {
                    var isSearchActive = !/\bsf-dump-search-hidden\b/.test(search.className);
                    if ((114 === e.keyCode && !isSearchActive) || (isCtrlKey(e) && 70 === e.keyCode)) { /* F3 or CMD/CTRL + F */
                        e.preventDefault();
                        search.className = search.className.replace(/\bsf-dump-search-hidden\b/, '');
                        searchInput.focus();
                    } else if (isSearchActive) {
                        if (27 === e.keyCode) { /* ESC key */
                            search.className += ' sf-dump-search-hidden';
                            e.preventDefault();
                            resetHighlightedNodes(root);
                            searchInput.value = '';
                        } else if ((isCtrlKey(e) && 71 === e.keyCode) /* CMD/CTRL + G */ || 13 === e.keyCode /* Enter */ || 114 === e.keyCode /* F3 */) {
                            e.preventDefault();
                            e.shiftKey ? state.previous() : state.next();
                            collapseAll(root);
                            showCurrent(state);
                        }
                    }
                });
            }
            if (0 >= options.maxStringLength) {
                return;
            }
            try {
                elt = root.querySelectorAll('.sf-dump-str');
                len = elt.length;
                i = 0;
                t = [];
                while (i < len) t.push(elt[i++]);
                len = t.length;
                for (i = 0; i < len; ++i) {
                    elt = t[i];
                    s = elt.innerText || elt.textContent;
                    x = s.length - options.maxStringLength;
                    if (0 < x) {
                        h = elt.innerHTML;
                        elt[elt.innerText ? 'innerText' : 'textContent'] = s.substring(0, options.maxStringLength);
                        elt.className += ' sf-dump-str-collapse';
                        elt.innerHTML = '<span class=sf-dump-str-collapse>' + h + '<a class="sf-dump-ref sf-dump-str-toggle" title="Collapse"> ◀</a></span>' + '<span class=sf-dump-str-expand>' + elt.innerHTML + '<a class="sf-dump-ref sf-dump-str-toggle" title="' + x + ' remaining characters"> ▶</a></span>';
                    }
                }
            } catch (e) {
            }
        };
    })(document); </script>
<style> .phpdebugbar pre.sf-dump {
        display: block;
        white-space: pre;
        padding: 5px;
        overflow: initial !important;
    }

    .phpdebugbar pre.sf-dump:after {
        content: "";
        visibility: hidden;
        display: block;
        height: 0;
        clear: both;
    }

    .phpdebugbar pre.sf-dump span {
        display: inline;
    }

    .phpdebugbar pre.sf-dump .sf-dump-compact {
        display: none;
    }

    .phpdebugbar pre.sf-dump abbr {
        text-decoration: none;
        border: none;
        cursor: help;
    }

    .phpdebugbar pre.sf-dump a {
        text-decoration: none;
        cursor: pointer;
        border: 0;
        outline: none;
        color: inherit;
    }

    .phpdebugbar pre.sf-dump .sf-dump-ellipsis {
        display: inline-block;
        overflow: visible;
        text-overflow: ellipsis;
        max-width: 5em;
        white-space: nowrap;
        overflow: hidden;
        vertical-align: top;
    }

    .phpdebugbar pre.sf-dump .sf-dump-ellipsis + .sf-dump-ellipsis {
        max-width: none;
    }

    .phpdebugbar pre.sf-dump code {
        display: inline;
        padding: 0;
        background: none;
    }

    .sf-dump-str-collapse .sf-dump-str-collapse {
        display: none;
    }

    .sf-dump-str-expand .sf-dump-str-expand {
        display: none;
    }

    .sf-dump-public.sf-dump-highlight, .sf-dump-protected.sf-dump-highlight, .sf-dump-private.sf-dump-highlight, .sf-dump-str.sf-dump-highlight, .sf-dump-key.sf-dump-highlight {
        background: rgba(111, 172, 204, 0.3);
        border: 1px solid #7DA0B1;
        border-radius: 3px;
    }

    .sf-dump-public.sf-dump-highlight-active, .sf-dump-protected.sf-dump-highlight-active, .sf-dump-private.sf-dump-highlight-active, .sf-dump-str.sf-dump-highlight-active, .sf-dump-key.sf-dump-highlight-active {
        background: rgba(253, 175, 0, 0.4);
        border: 1px solid #ffa500;
        border-radius: 3px;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-hidden {
        display: none !important;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-wrapper {
        font-size: 0;
        white-space: nowrap;
        margin-bottom: 5px;
        display: flex;
        position: -webkit-sticky;
        position: sticky;
        top: 5px;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > * {
        vertical-align: top;
        box-sizing: border-box;
        height: 21px;
        font-weight: normal;
        border-radius: 0;
        background: #FFF;
        color: #757575;
        border: 1px solid #BBB;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > input.sf-dump-search-input {
        padding: 3px;
        height: 21px;
        font-size: 12px;
        border-right: none;
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
        color: #000;
        min-width: 15px;
        width: 100%;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next, .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous {
        background: #F2F2F2;
        outline: none;
        border-left: none;
        font-size: 0;
        line-height: 0;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next {
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next > svg, .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous > svg {
        pointer-events: none;
        width: 12px;
        height: 12px;
    }

    .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-count {
        display: inline-block;
        padding: 0 5px;
        margin: 0;
        border-left: none;
        line-height: 21px;
        font-size: 12px;
    }

    .phpdebugbar pre.sf-dump, .phpdebugbar pre.sf-dump .sf-dump-default {
        word-wrap: break-word;
        white-space: pre-wrap;
        word-break: normal
    }

    .phpdebugbar pre.sf-dump .sf-dump-num {
        font-weight: bold;
        color: #1299DA
    }

    .phpdebugbar pre.sf-dump .sf-dump-const {
        font-weight: bold
    }

    .phpdebugbar pre.sf-dump .sf-dump-str {
        font-weight: bold;
        color: #3A9B26
    }

    .phpdebugbar pre.sf-dump .sf-dump-note {
        color: #1299DA
    }

    .phpdebugbar pre.sf-dump .sf-dump-ref {
        color: #7B7B7B
    }

    .phpdebugbar pre.sf-dump .sf-dump-public {
        color: #000000
    }

    .phpdebugbar pre.sf-dump .sf-dump-protected {
        color: #000000
    }

    .phpdebugbar pre.sf-dump .sf-dump-private {
        color: #000000
    }

    .phpdebugbar pre.sf-dump .sf-dump-meta {
        color: #B729D9
    }

    .phpdebugbar pre.sf-dump .sf-dump-key {
        color: #3A9B26
    }

    .phpdebugbar pre.sf-dump .sf-dump-index {
        color: #1299DA
    }

    .phpdebugbar pre.sf-dump .sf-dump-ellipsis {
        color: #A0A000
    }

    .phpdebugbar pre.sf-dump .sf-dump-ns {
        user-select: none;
    }</style>
<script type="text/javascript">
    var phpdebugbar = new PhpDebugBar.DebugBar();
    phpdebugbar.addIndicator("php_version", new PhpDebugBar.DebugBar.Indicator({
        "icon": "code",
        "tooltip": "Version"
    }), "right");
    phpdebugbar.addTab("messages", new PhpDebugBar.DebugBar.Tab({
        "icon": "list-alt",
        "title": "Messages",
        "widget": new PhpDebugBar.Widgets.MessagesWidget()
    }));
    phpdebugbar.addIndicator("time", new PhpDebugBar.DebugBar.Indicator({
        "icon": "clock-o",
        "tooltip": "Request Duration"
    }), "right");
    phpdebugbar.addTab("timeline", new PhpDebugBar.DebugBar.Tab({
        "icon": "tasks",
        "title": "Timeline",
        "widget": new PhpDebugBar.Widgets.TimelineWidget()
    }));
    phpdebugbar.addIndicator("memory", new PhpDebugBar.DebugBar.Indicator({
        "icon": "cogs",
        "tooltip": "Memory Usage"
    }), "right");
    phpdebugbar.addTab("exceptions", new PhpDebugBar.DebugBar.Tab({
        "icon": "bug",
        "title": "Exceptions",
        "widget": new PhpDebugBar.Widgets.ExceptionsWidget()
    }));
    phpdebugbar.addTab("views", new PhpDebugBar.DebugBar.Tab({
        "icon": "leaf",
        "title": "Views",
        "widget": new PhpDebugBar.Widgets.TemplatesWidget()
    }));
    phpdebugbar.addTab("route", new PhpDebugBar.DebugBar.Tab({
        "icon": "share",
        "title": "Route",
        "widget": new PhpDebugBar.Widgets.VariableListWidget()
    }));
    phpdebugbar.addIndicator("currentroute", new PhpDebugBar.DebugBar.Indicator({
        "icon": "share",
        "tooltip": "Route"
    }), "right");
    phpdebugbar.addTab("queries", new PhpDebugBar.DebugBar.Tab({
        "icon": "database",
        "title": "Queries",
        "widget": new PhpDebugBar.Widgets.LaravelSQLQueriesWidget()
    }));
    phpdebugbar.addTab("emails", new PhpDebugBar.DebugBar.Tab({
        "icon": "inbox",
        "title": "Mails",
        "widget": new PhpDebugBar.Widgets.MailsWidget()
    }));
    phpdebugbar.addTab("auth", new PhpDebugBar.DebugBar.Tab({
        "icon": "lock",
        "title": "Auth",
        "widget": new PhpDebugBar.Widgets.VariableListWidget()
    }));
    phpdebugbar.addIndicator("auth.name", new PhpDebugBar.DebugBar.Indicator({
        "icon": "user",
        "tooltip": "Auth status"
    }), "right");
    phpdebugbar.addTab("gate", new PhpDebugBar.DebugBar.Tab({
        "icon": "list-alt",
        "title": "Gate",
        "widget": new PhpDebugBar.Widgets.MessagesWidget()
    }));
    phpdebugbar.addTab("session", new PhpDebugBar.DebugBar.Tab({
        "icon": "archive",
        "title": "Session",
        "widget": new PhpDebugBar.Widgets.VariableListWidget()
    }));
    phpdebugbar.addTab("request", new PhpDebugBar.DebugBar.Tab({
        "icon": "tags",
        "title": "Request",
        "widget": new PhpDebugBar.Widgets.HtmlVariableListWidget()
    }));
    phpdebugbar.setDataMap({
        "php_version": ["php.version",],
        "messages": ["messages.messages", []],
        "messages:badge": ["messages.count", null],
        "time": ["time.duration_str", '0ms'],
        "timeline": ["time", {}],
        "memory": ["memory.peak_usage_str", '0B'],
        "exceptions": ["exceptions.exceptions", []],
        "exceptions:badge": ["exceptions.count", null],
        "views": ["views", []],
        "views:badge": ["views.nb_templates", 0],
        "route": ["route", {}],
        "currentroute": ["route.uri",],
        "queries": ["queries", []],
        "queries:badge": ["queries.nb_statements", 0],
        "emails": ["swiftmailer_mails.mails", []],
        "emails:badge": ["swiftmailer_mails.count", null],
        "auth": ["auth.guards", {}],
        "auth.name": ["auth.names",],
        "gate": ["gate.messages", []],
        "gate:badge": ["gate.count", null],
        "session": ["session", {}],
        "request": ["request", {}]
    });
    phpdebugbar.restoreState();
    phpdebugbar.ajaxHandler = new PhpDebugBar.AjaxHandler(phpdebugbar, undefined, true);
    phpdebugbar.ajaxHandler.bindToXHR();
    phpdebugbar.setOpenHandler(new PhpDebugBar.OpenHandler({"url": "http:\/\/localhost:8000\/_debugbar\/open"}));
    phpdebugbar.addDataSet({
        "__meta": {
            "id": "Xbc423e048f65aede254251ad0d1d90ef",
            "datetime": "2020-04-02 16:14:15",
            "utime": 1585818855.362034,
            "method": "GET",
            "uri": "\/admin\/loandisbursement\/create",
            "ip": "127.0.0.1"
        },
        "php": {"version": "7.2.13", "interface": "cli-server"},
        "messages": {"count": 0, "messages": []},
        "time": {
            "start": 1585818849.149411,
            "end": 1585818855.362055,
            "duration": 6.212644100189209,
            "duration_str": "6.21s",
            "measures": [{
                "label": "Booting",
                "start": 1585818849.149411,
                "relative_start": 0,
                "end": 1585818849.469013,
                "relative_end": 1585818849.469013,
                "duration": 0.31960201263427734,
                "duration_str": "319.6ms",
                "params": [],
                "collector": null
            }, {
                "label": "Application",
                "start": 1585818849.471594,
                "relative_start": 0.32218313217163086,
                "end": 1585818855.362057,
                "relative_end": 1.9073486328125e-6,
                "duration": 5.890462875366211,
                "duration_str": "5.89s",
                "params": [],
                "collector": null
            }]
        },
        "memory": {"peak_usage": 23586680, "peak_usage_str": "22.49MB"},
        "exceptions": {"count": 0, "exceptions": []},
        "views": {
            "nb_templates": 152,
            "templates": [{
                "name": "crud::create (\\resources\\views\\vendor\\backpack\\crud\\create.blade.php)",
                "param_count": 4,
                "params": ["crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "crud::inc.grouped_errors (\\resources\\views\\vendor\\backpack\\crud\\inc\\grouped_errors.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "vendor.backpack.crud.form_content (\\resources\\views\\vendor\\backpack\\crud\\form_content.blade.php)",
                "param_count": 9,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action"],
                "type": "blade"
            }, {
                "name": "crud::inc.show_tabbed_fields (\\resources\\views\\vendor\\backpack\\crud\\inc\\show_tabbed_fields.blade.php)",
                "param_count": 9,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action"],
                "type": "blade"
            }, {
                "name": "crud::inc.show_fields (\\resources\\views\\vendor\\backpack\\crud\\inc\\show_fields.blade.php)",
                "param_count": 10,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs"],
                "type": "blade"
            }, {
                "name": "crud::inc.show_fields (\\resources\\views\\vendor\\backpack\\crud\\inc\\show_fields.blade.php)",
                "param_count": 14,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop"],
                "type": "blade"
            }, {
                "name": "crud::fields.select2_from_ajax_client (\\resources\\views\\vendor\\backpack\\crud\\fields\\select2_from_ajax_client.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "old_value"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 18,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "old_value", "default_class"],
                "type": "blade"
            }, {
                "name": "crud::fields.text_read (\\resources\\views\\vendor\\backpack\\crud\\fields\\text_read.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.text_read (\\resources\\views\\vendor\\backpack\\crud\\fields\\text_read.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.text_read (\\resources\\views\\vendor\\backpack\\crud\\fields\\text_read.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.text_read (\\resources\\views\\vendor\\backpack\\crud\\fields\\text_read.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.enum (\\resources\\views\\vendor\\backpack\\crud\\fields\\enum.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "entity_model"],
                "type": "blade"
            }, {
                "name": "crud::fields.enum (\\resources\\views\\vendor\\backpack\\crud\\fields\\enum.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "entity_model"],
                "type": "blade"
            }, {
                "name": "crud::fields.select2_from_ajax_guarantor (\\resources\\views\\vendor\\backpack\\crud\\fields\\select2_from_ajax_guarantor.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 21,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value", "entity_model", "default_class"],
                "type": "blade"
            }, {
                "name": "crud::fields.text_read (\\resources\\views\\vendor\\backpack\\crud\\fields\\text_read.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.text_read (\\resources\\views\\vendor\\backpack\\crud\\fields\\text_read.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.text_read (\\resources\\views\\vendor\\backpack\\crud\\fields\\text_read.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.show_fields (\\resources\\views\\vendor\\backpack\\crud\\inc\\show_fields.blade.php)",
                "param_count": 14,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop"],
                "type": "blade"
            }, {
                "name": "crud::fields.text (\\resources\\views\\vendor\\backpack\\crud\\fields\\text.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.select2_from_ajax (\\resources\\views\\vendor\\backpack\\crud\\fields\\select2_from_ajax.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 21,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value", "entity_model", "default_class"],
                "type": "blade"
            }, {
                "name": "crud::fields.select2_from_ajax_center (\\resources\\views\\vendor\\backpack\\crud\\fields\\select2_from_ajax_center.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 21,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value", "entity_model", "default_class"],
                "type": "blade"
            }, {
                "name": "crud::fields.select2_from_ajax_loan_officer (\\resources\\views\\vendor\\backpack\\crud\\fields\\select2_from_ajax_loan_officer.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 21,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value", "entity_model", "default_class"],
                "type": "blade"
            }, {
                "name": "crud::fields.select2_from_ajax (\\resources\\views\\vendor\\backpack\\crud\\fields\\select2_from_ajax.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 21,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value", "entity_model", "default_class"],
                "type": "blade"
            }, {
                "name": "crud::fields.date_picker_event (\\resources\\views\\vendor\\backpack\\crud\\fields\\date_picker_event.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "field_language"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "field_language"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "field_language"],
                "type": "blade"
            }, {
                "name": "crud::fields.date_picker_event2 (\\resources\\views\\vendor\\backpack\\crud\\fields\\date_picker_event2.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "field_language"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "field_language"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "field_language"],
                "type": "blade"
            }, {
                "name": "crud::fields.number2 (\\resources\\views\\vendor\\backpack\\crud\\fields\\number2.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.number2 (\\resources\\views\\vendor\\backpack\\crud\\fields\\number2.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.enum (\\resources\\views\\vendor\\backpack\\crud\\fields\\enum.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "entity_model"],
                "type": "blade"
            }, {
                "name": "crud::fields.enum (\\resources\\views\\vendor\\backpack\\crud\\fields\\enum.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "entity_model"],
                "type": "blade"
            }, {
                "name": "crud::fields.number (\\resources\\views\\vendor\\backpack\\crud\\fields\\number.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.enum (\\resources\\views\\vendor\\backpack\\crud\\fields\\enum.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 17,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "entity_model"],
                "type": "blade"
            }, {
                "name": "crud::fields.select_not_null (\\resources\\views\\vendor\\backpack\\crud\\fields\\select_not_null.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "current_value", "entity_model", "options"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "current_value", "entity_model", "options"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "current_value", "entity_model", "options"],
                "type": "blade"
            }, {
                "name": "crud::fields.select_not_null (\\resources\\views\\vendor\\backpack\\crud\\fields\\select_not_null.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "current_value", "entity_model", "options"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_translatable_icon (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_translatable_icon.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "current_value", "entity_model", "options"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "current_value", "entity_model", "options"],
                "type": "blade"
            }, {
                "name": "crud::fields.select2_from_ajax_group_loan (\\resources\\views\\vendor\\backpack\\crud\\fields\\select2_from_ajax_group_loan.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 21,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace", "connected_entity", "connected_entity_key_name", "old_value", "entity_model", "default_class"],
                "type": "blade"
            }, {
                "name": "crud::fields.view (\\resources\\views\\vendor\\backpack\\crud\\fields\\view.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "partials.loan_disbursement.custom_ajax_loan_product (\\resources\\views\\partials\\loan_disbursement\\custom_ajax_loan_product.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.view (\\resources\\views\\vendor\\backpack\\crud\\fields\\view.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "partials.loan_disbursement.charge-service (\\resources\\views\\partials\\loan_disbursement\\charge-service.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.show_fields (\\resources\\views\\vendor\\backpack\\crud\\inc\\show_fields.blade.php)",
                "param_count": 14,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop"],
                "type": "blade"
            }, {
                "name": "crud::fields.view (\\resources\\views\\vendor\\backpack\\crud\\fields\\view.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "partials.loan_disbursement.custom_ajax_loan_disbursement (\\resources\\views\\partials\\loan_disbursement\\custom_ajax_loan_disbursement.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.view (\\resources\\views\\vendor\\backpack\\crud\\fields\\view.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "partials.loan_disbursement.custom_ajax_client (\\resources\\views\\partials\\loan_disbursement\\custom_ajax_client.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.hidden_no_name (\\resources\\views\\vendor\\backpack\\crud\\fields\\hidden_no_name.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.hidden_no_name (\\resources\\views\\vendor\\backpack\\crud\\fields\\hidden_no_name.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.hidden_no_name (\\resources\\views\\vendor\\backpack\\crud\\fields\\hidden_no_name.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::fields.hidden_no_name (\\resources\\views\\vendor\\backpack\\crud\\fields\\hidden_no_name.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.show_fields (\\resources\\views\\vendor\\backpack\\crud\\inc\\show_fields.blade.php)",
                "param_count": 14,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop"],
                "type": "blade"
            }, {
                "name": "crud::fields.view (\\resources\\views\\vendor\\backpack\\crud\\fields\\view.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.field_wrapper_attributes (\\resources\\views\\vendor\\backpack\\crud\\inc\\field_wrapper_attributes.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "partials.loan_disbursement.custom_ajax_payment_schedule (\\resources\\views\\partials\\loan_disbursement\\custom_ajax_payment_schedule.blade.php)",
                "param_count": 16,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "action", "horizontalTabs", "__currentLoopData", "tab", "k", "loop", "field", "fieldsViewNamespace"],
                "type": "blade"
            }, {
                "name": "crud::inc.form_save_buttons (\\resources\\views\\vendor\\backpack\\crud\\inc\\form_save_buttons.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "backpack::layout (\\resources\\views\\vendor\\backpack\\base\\layout.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "backpack::inc.head (\\resources\\views\\vendor\\backpack\\base\\inc\\head.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "backpack::inc.main_header (\\resources\\views\\vendor\\backpack\\base\\inc\\main_header.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "backpack::inc.menu (\\resources\\views\\vendor\\backpack\\base\\inc\\menu.blade.php)",
                "param_count": 13,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "branches", "s_branch_id", "__currentLoopData", "r", "loop"],
                "type": "blade"
            }, {
                "name": "backpack::inc.topbar_left_content (\\resources\\views\\vendor\\backpack\\base\\inc\\topbar_left_content.blade.php)",
                "param_count": 15,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "branches", "s_branch_id", "__currentLoopData", "r", "loop", "user", "unreadNotifications"],
                "type": "blade"
            }, {
                "name": "pagination::bootstrap-4 (\\resources\\views\\vendor\\pagination\\bootstrap-4.blade.php)",
                "param_count": 2,
                "params": ["paginator", "elements"],
                "type": "blade"
            }, {
                "name": "backpack::inc.topbar_right_content (\\resources\\views\\vendor\\backpack\\base\\inc\\topbar_right_content.blade.php)",
                "param_count": 19,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "branches", "s_branch_id", "__currentLoopData", "r", "loop", "user", "unreadNotifications", "m", "logo", "company", "notifications"],
                "type": "blade"
            }, {
                "name": "backpack::inc.sidebar (\\resources\\views\\vendor\\backpack\\base\\inc\\sidebar.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "backpack::inc.sidebar_user_panel (\\resources\\views\\vendor\\backpack\\base\\inc\\sidebar_user_panel.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "backpack::inc.sidebar_content (\\resources\\views\\vendor\\backpack\\base\\inc\\sidebar_content.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "vendor.backpack.base.inc.company.mkt (\\resources\\views\\vendor\\backpack\\base\\inc\\company\\mkt.blade.php)",
                "param_count": 8,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title"],
                "type": "blade"
            }, {
                "name": "backpack::inc.footer (\\resources\\views\\vendor\\backpack\\base\\inc\\footer.blade.php)",
                "param_count": 9,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "Url"],
                "type": "blade"
            }, {
                "name": "backpack::inc.scripts (\\resources\\views\\vendor\\backpack\\base\\inc\\scripts.blade.php)",
                "param_count": 9,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "Url"],
                "type": "blade"
            }, {
                "name": "backpack::inc.alerts (\\resources\\views\\vendor\\backpack\\base\\inc\\alerts.blade.php)",
                "param_count": 9,
                "params": ["obLevel", "__env", "app", "errors", "crud", "saveAction", "fields", "title", "Url"],
                "type": "blade"
            }]
        },
        "route": {
            "uri": "GET admin\/loandisbursement\/create",
            "middleware": "web, admin",
            "as": "crud.loandisbursement.create",
            "controller": "App\\Http\\Controllers\\Admin\\LoanCrudController@create",
            "namespace": "App\\Http\\Controllers\\Admin",
            "prefix": "admin",
            "where": [],
            "file": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\Operations\\CreateOperation.php:14-27"
        },
        "queries": {
            "nb_statements": 36,
            "nb_failed_statements": 0,
            "accumulated_duration": 0.522,
            "accumulated_duration_str": "522ms",
            "statements": [{
                "sql": "select * from `users` where `users`.`id` = 1 limit 1",
                "type": "query",
                "params": [],
                "bindings": ["1"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "<code>LIMIT<\/code> without <code>ORDER BY<\/code> causes non-deterministic results, depending on the query execution plan"],
                "backtrace": [{"index": 16, "namespace": null, "name": "\\app\\User.php", "line": 95}, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\EloquentUserProvider.php",
                    "line": 52
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\SessionGuard.php",
                    "line": 131
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 60
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 70
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 66
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 29, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php",
                    "line": 63
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php",
                    "line": 37
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php",
                    "line": 66
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 104
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 684
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 659
                }],
                "duration": 0.006730000000000001,
                "duration_str": "6.73ms",
                "stmt_id": "\\app\\User.php:95",
                "connection": "micron_f"
            }, {
                "sql": "select `branches`.*, `user_branches`.`user_id` as `pivot_user_id`, `user_branches`.`branch_id` as `pivot_branch_id` from `branches` inner join `user_branches` on `branches`.`id` = `user_branches`.`branch_id` where `user_branches`.`user_id` = 1",
                "type": "query",
                "params": [],
                "bindings": ["1"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table"],
                "backtrace": [{"index": 20, "namespace": null, "name": "\\app\\User.php", "line": 98}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\EloquentUserProvider.php",
                    "line": 52
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\SessionGuard.php",
                    "line": 131
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 60
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 70
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 66
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 33, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php",
                    "line": 63
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php",
                    "line": 37
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php",
                    "line": 66
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.00083,
                "duration_str": "830\u03bcs",
                "stmt_id": "\\app\\User.php:98",
                "connection": "micron_f"
            }, {
                "sql": "select * from `user_branches` where `branch_id` in (2, 3, 1, 4, 5, 6)",
                "type": "query",
                "params": [],
                "bindings": ["2", "3", "1", "4", "5", "6"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table"],
                "backtrace": [{"index": 14, "namespace": null, "name": "\\app\\User.php", "line": 106}, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\EloquentUserProvider.php",
                    "line": 52
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\SessionGuard.php",
                    "line": 131
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 60
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 70
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 66
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 27, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php",
                    "line": 63
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php",
                    "line": 37
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php",
                    "line": 66
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 104
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 684
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 659
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 625
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 614
                }],
                "duration": 0.0008399999999999999,
                "duration_str": "840\u03bcs",
                "stmt_id": "\\app\\User.php:106",
                "connection": "micron_f"
            }, {
                "sql": "select * from `users` where `id` = 1 limit 1",
                "type": "query",
                "params": [],
                "bindings": ["1"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "<code>LIMIT<\/code> without <code>ORDER BY<\/code> causes non-deterministic results, depending on the query execution plan"],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\EloquentUserProvider.php",
                    "line": 52
                }, {
                    "index": 16,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\SessionGuard.php",
                    "line": 131
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 60
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\GuardHelpers.php",
                    "line": 70
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 66
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 22, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php",
                    "line": 63
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php",
                    "line": 37
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php",
                    "line": 66
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 104
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 684
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 659
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 625
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 614
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
                    "line": 176
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }],
                "duration": 0.0005200000000000001,
                "duration_str": "520\u03bcs",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\EloquentUserProvider.php:52",
                "connection": "micron_f"
            }, {
                "sql": "select column_name as `column_name` from information_schema.columns where table_schema = 'micron_f' and table_name = 'loans'",
                "type": "query",
                "params": [],
                "bindings": ["micron_f", "loans"],
                "hints": [],
                "backtrace": [{
                    "index": 9,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\PanelTraits\\Columns.php",
                    "line": 415
                }, {
                    "index": 10,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\PanelTraits\\Columns.php",
                    "line": 84
                }, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\app\\Http\\Controllers\\Admin\\loan_inc.php",
                    "line": 330
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 43
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 16,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 74
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 22, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php",
                    "line": 63
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php",
                    "line": 37
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php",
                    "line": 66
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 104
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 684
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 659
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 625
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 614
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
                    "line": 176
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }],
                "duration": 0.03744,
                "duration_str": "37.44ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\PanelTraits\\Columns.php:415",
                "connection": "micron_f"
            }, {
                "sql": "select `key`, `value` from `settings`",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["The <code>SELECT<\/code> statement has no <code>WHERE<\/code> clause and could examine many more rows than intended"],
                "backtrace": [{
                    "index": 14,
                    "namespace": null,
                    "name": "\\app\\Helpers\\f.php",
                    "line": 1
                }, {
                    "index": 16,
                    "namespace": null,
                    "name": "\\app\\Http\\Controllers\\Admin\\LoanCrudController.php",
                    "line": 337
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 43
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 74
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 26, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php",
                    "line": 63
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php",
                    "line": 37
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php",
                    "line": 66
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 104
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 684
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 659
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 625
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 614
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php",
                    "line": 176
                }],
                "duration": 0.0006,
                "duration_str": "600\u03bcs",
                "stmt_id": "\\app\\Helpers\\f.php:1",
                "connection": "micron_f"
            }, {
                "sql": "select `branches`.*, `user_branches`.`user_id` as `pivot_user_id`, `user_branches`.`branch_id` as `pivot_branch_id` from `branches` inner join `user_branches` on `branches`.`id` = `user_branches`.`branch_id` where `user_branches`.`user_id` = 1",
                "type": "query",
                "params": [],
                "bindings": ["1"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table"],
                "backtrace": [{
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Optional.php",
                    "line": 54
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Optional.php",
                    "line": 41
                }, {"index": 22, "namespace": null, "name": "\\app\\Models\\Loan.php", "line": 322}, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {"index": 31, "namespace": null, "name": "\\app\\Models\\Loan.php", "line": 283}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\app\\Http\\Controllers\\Admin\\LoanCrudController.php",
                    "line": 337
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 43
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 74
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 42, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.0008399999999999999,
                "duration_str": "840\u03bcs",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Optional.php:54",
                "connection": "micron_f"
            }, {
                "sql": "select max(`seq`) as aggregate from `loans`",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["The <code>SELECT<\/code> statement has no <code>WHERE<\/code> clause and could examine many more rows than intended"],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {"index": 18, "namespace": null, "name": "\\app\\Models\\Loan.php", "line": 283}, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\app\\Http\\Controllers\\Admin\\LoanCrudController.php",
                    "line": 337
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 43
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 74
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 29, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken.php",
                    "line": 75
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Middleware\\ShareErrorsFromSession.php",
                    "line": 49
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php",
                    "line": 63
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php",
                    "line": 37
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php",
                    "line": 66
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 104
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 684
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 659
                }],
                "duration": 0.032729999999999995,
                "duration_str": "32.73ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"you_are_a_group_leader\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 15}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.01925,
                "duration_str": "19.25ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"you_are_a_group_leader\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 16}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.011470000000000001,
                "duration_str": "11.47ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"you_are_a_center_leader\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 15}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.015,
                "duration_str": "15ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"you_are_a_center_leader\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 16}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.01124,
                "duration_str": "11.24ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "select * from `branches` where `branches`.`id` = 1 limit 1",
                "type": "query",
                "params": [],
                "bindings": ["1"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "<code>LIMIT<\/code> without <code>ORDER BY<\/code> causes non-deterministic results, depending on the query execution plan"],
                "backtrace": [{
                    "index": 16,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "crud::fields.select2_from_ajax",
                    "line": 21
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 36,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.00065,
                "duration_str": "650\u03bcs",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"interest_rate_period\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 15}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.019829999999999997,
                "duration_str": "19.83ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"interest_rate_period\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 16}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.01123,
                "duration_str": "11.23ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"loan_term\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 15}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.01056,
                "duration_str": "10.56ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"loan_term\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 16}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.01294,
                "duration_str": "12.94ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"repayment_term\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 15}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.012960000000000001,
                "duration_str": "12.96ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "SHOW COLUMNS FROM `loans` WHERE Field = \"repayment_term\"",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": [],
                "backtrace": [{
                    "index": 8,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\CrudTrait.php",
                    "line": 30
                }, {"index": 9, "namespace": "view", "name": "crud::fields.enum", "line": 16}, {
                    "index": 11,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 12,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 13,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 14,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 15, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 27,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.01177,
                "duration_str": "11.77ms",
                "stmt_id": "\\vendor\\backpack\\crud\\src\\CrudTrait.php:30",
                "connection": "micron_f"
            }, {
                "sql": "select * from `currencies`",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "The <code>SELECT<\/code> statement has no <code>WHERE<\/code> clause and could examine many more rows than intended"],
                "backtrace": [{
                    "index": 15,
                    "namespace": "view",
                    "name": "crud::fields.select_not_null",
                    "line": 7
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 27, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 33,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 39, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }],
                "duration": 0.013890000000000001,
                "duration_str": "13.89ms",
                "stmt_id": "view::crud::fields.select_not_null:7",
                "connection": "micron_f"
            }, {
                "sql": "select * from `transaction_types`",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "The <code>SELECT<\/code> statement has no <code>WHERE<\/code> clause and could examine many more rows than intended"],
                "backtrace": [{
                    "index": 15,
                    "namespace": "view",
                    "name": "crud::fields.select_not_null",
                    "line": 7
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 27, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 33,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 39, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }],
                "duration": 0.007809999999999999,
                "duration_str": "7.81ms",
                "stmt_id": "view::crud::fields.select_not_null:7",
                "connection": "micron_f"
            }, {
                "sql": "select * from `loans` where `id` = 0 limit 1",
                "type": "query",
                "params": [],
                "bindings": ["0"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "<code>LIMIT<\/code> without <code>ORDER BY<\/code> causes non-deterministic results, depending on the query execution plan"],
                "backtrace": [{
                    "index": 15,
                    "namespace": "view",
                    "name": "partials.loan_disbursement.custom_ajax_loan_product",
                    "line": 4
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "crud::fields.view", "line": 4}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 27, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 45, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }],
                "duration": 0.00182,
                "duration_str": "1.82ms",
                "stmt_id": "view::partials.loan_disbursement.custom_ajax_loan_product:4",
                "connection": "micron_f"
            }, {
                "sql": "select * from `loan_products` where `loan_products`.`id` is null limit 1",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "<code>LIMIT<\/code> without <code>ORDER BY<\/code> causes non-deterministic results, depending on the query execution plan"],
                "backtrace": [{
                    "index": 16,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 19,
                    "namespace": "view",
                    "name": "partials.loan_disbursement.custom_ajax_loan_product",
                    "line": 5
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 25, "namespace": "view", "name": "crud::fields.view", "line": 4}, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 31, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 37, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 43,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 49, "namespace": "view", "name": "crud::create", "line": 43}],
                "duration": 0.00069,
                "duration_str": "690\u03bcs",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select * from `loan_disbursement_calculate` where `disbursement_id` is null order by `id` asc",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table"],
                "backtrace": [{
                    "index": 14,
                    "namespace": "view",
                    "name": "partials.loan_disbursement.custom_ajax_payment_schedule",
                    "line": 3
                }, {
                    "index": 16,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 20, "namespace": "view", "name": "crud::fields.view", "line": 4}, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 26, "namespace": "view", "name": "crud::inc.show_fields", "line": 8}, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 32, "namespace": "view", "name": "crud::inc.show_tabbed_fields", "line": 51}, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 38,
                    "namespace": "view",
                    "name": "vendor.backpack.crud.form_content",
                    "line": 9
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 44, "namespace": "view", "name": "crud::create", "line": 43}, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }],
                "duration": 0.03835,
                "duration_str": "38.35ms",
                "stmt_id": "view::partials.loan_disbursement.custom_ajax_payment_schedule:3",
                "connection": "micron_f"
            }, {
                "sql": "select * from `branches`",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table", "The <code>SELECT<\/code> statement has no <code>WHERE<\/code> clause and could examine many more rows than intended"],
                "backtrace": [{
                    "index": 15,
                    "namespace": "view",
                    "name": "backpack::inc.main_header",
                    "line": 9
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "backpack::layout", "line": 88}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 27, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\app\\Http\\Middleware\\CheckIfAdmin.php",
                    "line": 74
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {"index": 48, "namespace": "middleware", "name": "bindings", "line": 41}, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.00098,
                "duration_str": "980\u03bcs",
                "stmt_id": "view::backpack::inc.main_header:9",
                "connection": "micron_f"
            }, {
                "sql": "select * from `notifications` where `notifications`.`notifiable_id` = 1 and `notifications`.`notifiable_id` is not null and `notifications`.`notifiable_type` = 'App\\User' and `read_at` is null order by `created_at` desc",
                "type": "query",
                "params": [],
                "bindings": ["1", "App\\User"],
                "hints": ["Use <code>SELECT *<\/code> only if you need all columns from table"],
                "backtrace": [{"index": 19, "namespace": "view", "name": "backpack::inc.menu", "line": 7}, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 25, "namespace": "view", "name": "backpack::inc.main_header", "line": 40}, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 31, "namespace": "view", "name": "backpack::layout", "line": 88}, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 37, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }],
                "duration": 0.052969999999999996,
                "duration_str": "52.97ms",
                "stmt_id": "view::backpack::inc.menu:7",
                "connection": "micron_f"
            }, {
                "sql": "select `key`, `value` from `settings`",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["The <code>SELECT<\/code> statement has no <code>WHERE<\/code> clause and could examine many more rows than intended"],
                "backtrace": [{
                    "index": 14,
                    "namespace": null,
                    "name": "\\app\\Helpers\\f.php",
                    "line": 1
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "backpack::inc.main_header", "line": 40}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 27, "namespace": "view", "name": "backpack::layout", "line": 88}, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.00069,
                "duration_str": "690\u03bcs",
                "stmt_id": "\\app\\Helpers\\f.php:1",
                "connection": "micron_f"
            }, {
                "sql": "select count(*) as aggregate from `notifications` where `notifiable_id` = 1",
                "type": "query",
                "params": [],
                "bindings": ["1"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": "view",
                    "name": "backpack::inc.menu",
                    "line": 50
                }, {
                    "index": 17,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 18,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 19,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 21, "namespace": "view", "name": "backpack::inc.main_header", "line": 40}, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 24,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 25,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 27, "namespace": "view", "name": "backpack::layout", "line": 88}, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 30,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 31,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 33, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 36,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 37,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 750
                }, {
                    "index": 42,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 722
                }, {
                    "index": 43,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php",
                    "line": 682
                }, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 30
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\backpack\\crud\\src\\app\\Http\\Controllers\\CrudController.php",
                    "line": 45
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 145
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php",
                    "line": 53
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php",
                    "line": 31
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php",
                    "line": 163
                }],
                "duration": 0.0005899999999999999,
                "duration_str": "590\u03bcs",
                "stmt_id": "view::backpack::inc.menu:50",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans`",
                "type": "query",
                "params": [],
                "bindings": [],
                "hints": ["The <code>SELECT<\/code> statement has no <code>WHERE<\/code> clause and could examine many more rows than intended"],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 76
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.05514,
                "duration_str": "55.14ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans` where `disbursement_status` = 'Pending'",
                "type": "query",
                "params": [],
                "bindings": ["Pending"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 78
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.05539,
                "duration_str": "55.39ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans` where `disbursement_status` = 'Approved'",
                "type": "query",
                "params": [],
                "bindings": ["Approved"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 79
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.011970000000000001,
                "duration_str": "11.97ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans` where `disbursement_status` in ('Activated')",
                "type": "query",
                "params": [],
                "bindings": ["Activated"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 80
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.013519999999999999,
                "duration_str": "13.52ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans` where `disbursement_status` = 'Closed'",
                "type": "query",
                "params": [],
                "bindings": ["Closed"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 81
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.01394,
                "duration_str": "13.94ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans` where `disbursement_status` = 'Declined'",
                "type": "query",
                "params": [],
                "bindings": ["Declined"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 82
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.011460000000000001,
                "duration_str": "11.46ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans` where `disbursement_status` = 'Canceled'",
                "type": "query",
                "params": [],
                "bindings": ["Canceled"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 83
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.01281,
                "duration_str": "12.81ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }, {
                "sql": "select count(`id`) as aggregate from `loans` where `disbursement_status` = 'Written-Off'",
                "type": "query",
                "params": [],
                "bindings": ["Written-Off"],
                "hints": [],
                "backtrace": [{
                    "index": 15,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php",
                    "line": 23
                }, {
                    "index": 18,
                    "namespace": "view",
                    "name": "vendor.backpack.base.inc.company.mkt",
                    "line": 85
                }, {
                    "index": 20,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 21,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 22,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 23,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 24, "namespace": "view", "name": "backpack::inc.sidebar_content", "line": 4}, {
                    "index": 26,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 27,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 28,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 29,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 30, "namespace": "view", "name": "backpack::inc.sidebar", "line": 16}, {
                    "index": 32,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 33,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 34,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 35,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 36, "namespace": "view", "name": "backpack::layout", "line": 94}, {
                    "index": 38,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 39,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 40,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 41,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {"index": 42, "namespace": "view", "name": "crud::create", "line": 61}, {
                    "index": 44,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php",
                    "line": 59
                }, {
                    "index": 45,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 142
                }, {
                    "index": 46,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 125
                }, {
                    "index": 47,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php",
                    "line": 90
                }, {
                    "index": 48,
                    "namespace": null,
                    "name": "\\vendor\\laravel\\framework\\src\\Illuminate\\Http\\Response.php",
                    "line": 42
                }, {
                    "index": 49,
                    "namespace": null,
                    "name": "\\vendor\\symfony\\http-foundation\\Response.php",
                    "line": 202
                }],
                "duration": 0.01255,
                "duration_str": "12.55ms",
                "stmt_id": "\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\ForwardsCalls.php:23",
                "connection": "micron_f"
            }]
        },
        "swiftmailer_mails": {"count": 0, "mails": []},
        "auth": {
            "guards": {
                "web": "array:2 [\n  \"name\" => \"ksmart.lion@gmail.com\"\n  \"user\" => array:23 [\n    \"id\" => 1\n    \"finger_print_no\" => null\n    \"name\" => \"ksmart.lion\"\n    \"phone\" => null\n    \"email\" => \"ksmart.lion@gmail.com\"\n    \"photo\" => null\n    \"branch_id\" => null\n    \"center_leader_id\" => null\n    \"email_verified_at\" => null\n    \"created_at\" => \"2019-06-18 23:28:33\"\n    \"updated_at\" => \"2019-11-22 18:47:21\"\n    \"created_by\" => 0\n    \"updated_by\" => 7327\n    \"user_code\" => null\n    \"repayment_seq\" => 0\n    \"disbursement_seq\" => 0\n    \"transfer_seq\" => 0\n    \"expense_seq\" => 0\n    \"profit_seq\" => 0\n    \"loan_seq\" => 0\n    \"deposit_seq\" => 0\n    \"branches\" => array:6 [\n      0 => array:24 [\n        \"id\" => 2\n        \"code\" => \"BR002\"\n        \"title\" => \"Mandalay Branch\"\n        \"phone\" => \"09797002155\"\n        \"location\" => null\n        \"description\" => null\n        \"country_id\" => null\n        \"address\" => null\n        \"province_id\" => null\n        \"district_id\" => null\n        \"commune_id\" => null\n        \"village_id\" => null\n        \"street_number\" => null\n        \"house_number\" => null\n        \"branch_manager_id\" => 0\n        \"seq\" => 1\n        \"created_at\" => \"2019-06-19 23:35:31\"\n        \"updated_at\" => \"2019-11-08 09:13:09\"\n        \"created_by\" => 1\n        \"updated_by\" => 1\n        \"client_prefix\" => null\n        \"cash_account_id\" => 2\n        \"address1\" => null\n        \"pivot\" => array:2 [\n          \"user_id\" => 1\n          \"branch_id\" => 2\n        ]\n      ]\n      1 => array:24 [\n        \"id\" => 3\n        \"code\" => \"BR003\"\n        \"title\" => \"Magway Branch\"\n        \"phone\" => \"09797002155\"\n        \"location\" => null\n        \"description\" => null\n        \"country_id\" => null\n        \"address\" => null\n        \"province_id\" => null\n        \"district_id\" => null\n        \"commune_id\" => null\n        \"village_id\" => null\n        \"street_number\" => null\n        \"house_number\" => null\n        \"branch_manager_id\" => 0\n        \"seq\" => 2\n        \"created_at\" => \"2019-06-19 23:35:59\"\n        \"updated_at\" => \"2019-11-08 09:12:47\"\n        \"created_by\" => 1\n        \"updated_by\" => 1\n        \"client_prefix\" => null\n        \"cash_account_id\" => 2\n        \"address1\" => null\n        \"pivot\" => array:2 [\n          \"user_id\" => 1\n          \"branch_id\" => 3\n        ]\n      ]\n      2 => array:24 [\n        \"id\" => 1\n        \"code\" => \"BR001\"\n        \"title\" => \"Head Office\"\n        \"phone\" => \"09797002156\"\n        \"location\" => null\n        \"description\" => null\n        \"country_id\" => null\n        \"address\" => null\n        \"province_id\" => null\n        \"district_id\" => null\n        \"commune_id\" => null\n        \"village_id\" => null\n        \"street_number\" => null\n        \"house_number\" => null\n        \"branch_manager_id\" => 0\n        \"seq\" => 0\n        \"created_at\" => \"2019-02-01 11:33:34\"\n        \"updated_at\" => \"2019-11-25 14:10:46\"\n        \"created_by\" => 0\n        \"updated_by\" => 1\n        \"client_prefix\" => \"HO\"\n        \"cash_account_id\" => 2\n        \"address1\" => null\n        \"pivot\" => array:2 [\n          \"user_id\" => 1\n          \"branch_id\" => 1\n        ]\n      ]\n      3 => array:24 [\n        \"id\" => 4\n        \"code\" => \"BR004\"\n        \"title\" => \"Meikhitlar\"\n        \"phone\" => \"09797002155\"\n        \"location\" => null\n        \"description\" => null\n        \"country_id\" => null\n        \"address\" => null\n        \"province_id\" => null\n        \"district_id\" => null\n        \"commune_id\" => null\n        \"village_id\" => null\n        \"street_number\" => null\n        \"house_number\" => null\n        \"branch_manager_id\" => 0\n        \"seq\" => 3\n        \"created_at\" => \"2019-11-08 09:11:06\"\n        \"updated_at\" => \"2019-11-08 09:12:24\"\n        \"created_by\" => 1\n        \"updated_by\" => 1\n        \"client_prefix\" => null\n        \"cash_account_id\" => 2\n        \"address1\" => null\n        \"pivot\" => array:2 [\n          \"user_id\" => 1\n          \"branch_id\" => 4\n        ]\n      ]\n      4 => array:24 [\n        \"id\" => 5\n        \"code\" => \"BR005\"\n        \"title\" => \"NPT Branch 00\"\n        \"phone\" => \"09797002155\"\n        \"location\" => null\n        \"description\" => null\n        \"country_id\" => null\n        \"address\" => null\n        \"province_id\" => null\n        \"district_id\" => null\n        \"commune_id\" => null\n        \"village_id\" => null\n        \"street_number\" => null\n        \"house_number\" => null\n        \"branch_manager_id\" => 0\n        \"seq\" => 4\n        \"created_at\" => \"2019-11-08 09:11:18\"\n        \"updated_at\" => \"2019-11-08 09:12:07\"\n        \"created_by\" => 1\n        \"updated_by\" => 1\n        \"client_prefix\" => null\n        \"cash_account_id\" => 2\n        \"address1\" => null\n        \"pivot\" => array:2 [\n          \"user_id\" => 1\n          \"branch_id\" => 5\n        ]\n      ]\n      5 => array:24 [\n        \"id\" => 6\n        \"code\" => \"BR006\"\n        \"title\" => \"NPT Branch 01\"\n        \"phone\" => \"095023187\"\n        \"location\" => null\n        \"description\" => null\n        \"country_id\" => null\n        \"address\" => null\n        \"province_id\" => null\n        \"district_id\" => null\n        \"commune_id\" => null\n        \"village_id\" => null\n        \"street_number\" => null\n        \"house_number\" => null\n        \"branch_manager_id\" => 0\n        \"seq\" => 5\n        \"created_at\" => \"2019-11-08 09:11:41\"\n        \"updated_at\" => \"2019-11-08 09:11:50\"\n        \"created_by\" => 1\n        \"updated_by\" => 1\n        \"client_prefix\" => null\n        \"cash_account_id\" => 2\n        \"address1\" => null\n        \"pivot\" => array:2 [\n          \"user_id\" => 1\n          \"branch_id\" => 6\n        ]\n      ]\n    ]\n    \"unread_notifications\" => []\n  ]\n]",
                "api": "null",
                "backpack": "null"
            }, "names": "web: ksmart.lion@gmail.com"
        },
        "gate": {"count": 0, "messages": []},
        "session": {
            "_token": "K21BtJtjUL5WoFJkgNNrMqcanCQtRRg5HzbQDOYL",
            "branch_id": "1",
            "url": "[]",
            "_previous": "array:1 [\n  \"url\" => \"http:\/\/localhost:8000\/admin\/loandisbursement\/create\"\n]",
            "_flash": "array:2 [\n  \"old\" => []\n  \"new\" => []\n]",
            "login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d": "1",
            "s_branch_id": "1",
            "save_action": "save_and_back",
            "PHPDEBUGBAR_STACK_DATA": "[]"
        },
        "request": {
            "path_info": "\/admin\/loandisbursement\/create",
            "status_code": "<pre class=sf-dump id=sf-dump-132389429 data-indent-pad=\"  \"><span class=sf-dump-num>200<\/span>\n<\/pre><script>Sfdump(\"sf-dump-132389429\", {\"maxDepth\":0})<\/script>\n",
            "status_text": "OK",
            "format": "html",
            "content_type": "text\/html; charset=UTF-8",
            "request_query": "<pre class=sf-dump id=sf-dump-166570900 data-indent-pad=\"  \">[]\n<\/pre><script>Sfdump(\"sf-dump-166570900\", {\"maxDepth\":0})<\/script>\n",
            "request_request": "<pre class=sf-dump id=sf-dump-1483158864 data-indent-pad=\"  \">[]\n<\/pre><script>Sfdump(\"sf-dump-1483158864\", {\"maxDepth\":0})<\/script>\n",
            "request_headers": "<pre class=sf-dump id=sf-dump-1098593474 data-indent-pad=\"  \"><span class=sf-dump-note>array:12<\/span> [<samp>\n  \"<span class=sf-dump-key>host<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"14 characters\">localhost:8000<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>connection<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"10 characters\">keep-alive<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>upgrade-insecure-requests<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str>1<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>user-agent<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"115 characters\">Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/80.0.3987.149 Safari\/537.36<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>sec-fetch-dest<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"8 characters\">document<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>accept<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"124 characters\">text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/webp,image\/apng,*\/*;q=0.8,application\/signed-exchange;v=b3;q=0.9<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>sec-fetch-site<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"4 characters\">none<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>sec-fetch-mode<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"8 characters\">navigate<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>referer<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"48 characters\">http:\/\/localhost:8000\/admin\/active-member-client<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>accept-encoding<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"17 characters\">gzip, deflate, br<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>accept-language<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"35 characters\">en-GB,en-US;q=0.9,en;q=0.8,km;q=0.7<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>cookie<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"549 characters\">_ga=GA1.1.997739072.1581080251; XSRF-TOKEN=eyJpdiI6IkV5ZEYxM1wvTHRhMWZoSXR6bWRmNG53PT0iLCJ2YWx1ZSI6ImhTdTBiT0pOYzZRYXFidzE5VTBBOUtSYW9wcnpFZER2VyswSzNZRysyMWxZdjJPU1JJZVNIWWJcL3QrcTE0UmF3IiwibWFjIjoiNTlhY2ZlZjY0YTZiNWNhZGJkOTdmZjg5OTMxNGNkZWFiOTA1N2E2ZTA1ZjhjYjUxMDczNTI1MjYzNWNiMjc1NiJ9; laravel_session=eyJpdiI6ImorN3FOMnAyWDVSeGJ3aUtWT3FESXc9PSIsInZhbHVlIjoiMEtrTWZHQ2h6R0ZYVWJZU2tRTStNdFwvVGlCU3czYUVZQ1RUMmFESURGR2Zlc0V5K2p0NE1QNEthbWYrd1wvVXdBIiwibWFjIjoiZWVlNGQxMmJkYTAxMGEyYTEzZDE5NTNkYjM1YjcxY2E4ODg2OWY0YzA1ZGMwODEyM2UxMWEwYzgzNmQ5ZDc0ZiJ9<\/span>\"\n  <\/samp>]\n<\/samp>]\n<\/pre><script>Sfdump(\"sf-dump-1098593474\", {\"maxDepth\":0})<\/script>\n",
            "request_server": "<pre class=sf-dump id=sf-dump-171881545 data-indent-pad=\"  \"><span class=sf-dump-note>array:27<\/span> [<samp>\n  \"<span class=sf-dump-key>DOCUMENT_ROOT<\/span>\" => \"<span class=sf-dump-str title=\"41 characters\">D:\\cloudnet\\microfinance-2020-last\\public<\/span>\"\n  \"<span class=sf-dump-key>REMOTE_ADDR<\/span>\" => \"<span class=sf-dump-str title=\"9 characters\">127.0.0.1<\/span>\"\n  \"<span class=sf-dump-key>REMOTE_PORT<\/span>\" => \"<span class=sf-dump-str title=\"5 characters\">50888<\/span>\"\n  \"<span class=sf-dump-key>SERVER_SOFTWARE<\/span>\" => \"<span class=sf-dump-str title=\"29 characters\">PHP 7.2.13 Development Server<\/span>\"\n  \"<span class=sf-dump-key>SERVER_PROTOCOL<\/span>\" => \"<span class=sf-dump-str title=\"8 characters\">HTTP\/1.1<\/span>\"\n  \"<span class=sf-dump-key>SERVER_NAME<\/span>\" => \"<span class=sf-dump-str title=\"9 characters\">127.0.0.1<\/span>\"\n  \"<span class=sf-dump-key>SERVER_PORT<\/span>\" => \"<span class=sf-dump-str title=\"4 characters\">8000<\/span>\"\n  \"<span class=sf-dump-key>REQUEST_URI<\/span>\" => \"<span class=sf-dump-str title=\"30 characters\">\/admin\/loandisbursement\/create<\/span>\"\n  \"<span class=sf-dump-key>REQUEST_METHOD<\/span>\" => \"<span class=sf-dump-str title=\"3 characters\">GET<\/span>\"\n  \"<span class=sf-dump-key>SCRIPT_NAME<\/span>\" => \"<span class=sf-dump-str title=\"10 characters\">\/index.php<\/span>\"\n  \"<span class=sf-dump-key>SCRIPT_FILENAME<\/span>\" => \"<span class=sf-dump-str title=\"51 characters\">D:\\cloudnet\\microfinance-2020-last\\public\\index.php<\/span>\"\n  \"<span class=sf-dump-key>PATH_INFO<\/span>\" => \"<span class=sf-dump-str title=\"30 characters\">\/admin\/loandisbursement\/create<\/span>\"\n  \"<span class=sf-dump-key>PHP_SELF<\/span>\" => \"<span class=sf-dump-str title=\"40 characters\">\/index.php\/admin\/loandisbursement\/create<\/span>\"\n  \"<span class=sf-dump-key>HTTP_HOST<\/span>\" => \"<span class=sf-dump-str title=\"14 characters\">localhost:8000<\/span>\"\n  \"<span class=sf-dump-key>HTTP_CONNECTION<\/span>\" => \"<span class=sf-dump-str title=\"10 characters\">keep-alive<\/span>\"\n  \"<span class=sf-dump-key>HTTP_UPGRADE_INSECURE_REQUESTS<\/span>\" => \"<span class=sf-dump-str>1<\/span>\"\n  \"<span class=sf-dump-key>HTTP_USER_AGENT<\/span>\" => \"<span class=sf-dump-str title=\"115 characters\">Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/80.0.3987.149 Safari\/537.36<\/span>\"\n  \"<span class=sf-dump-key>HTTP_SEC_FETCH_DEST<\/span>\" => \"<span class=sf-dump-str title=\"8 characters\">document<\/span>\"\n  \"<span class=sf-dump-key>HTTP_ACCEPT<\/span>\" => \"<span class=sf-dump-str title=\"124 characters\">text\/html,application\/xhtml+xml,application\/xml;q=0.9,image\/webp,image\/apng,*\/*;q=0.8,application\/signed-exchange;v=b3;q=0.9<\/span>\"\n  \"<span class=sf-dump-key>HTTP_SEC_FETCH_SITE<\/span>\" => \"<span class=sf-dump-str title=\"4 characters\">none<\/span>\"\n  \"<span class=sf-dump-key>HTTP_SEC_FETCH_MODE<\/span>\" => \"<span class=sf-dump-str title=\"8 characters\">navigate<\/span>\"\n  \"<span class=sf-dump-key>HTTP_REFERER<\/span>\" => \"<span class=sf-dump-str title=\"48 characters\">http:\/\/localhost:8000\/admin\/active-member-client<\/span>\"\n  \"<span class=sf-dump-key>HTTP_ACCEPT_ENCODING<\/span>\" => \"<span class=sf-dump-str title=\"17 characters\">gzip, deflate, br<\/span>\"\n  \"<span class=sf-dump-key>HTTP_ACCEPT_LANGUAGE<\/span>\" => \"<span class=sf-dump-str title=\"35 characters\">en-GB,en-US;q=0.9,en;q=0.8,km;q=0.7<\/span>\"\n  \"<span class=sf-dump-key>HTTP_COOKIE<\/span>\" => \"<span class=sf-dump-str title=\"549 characters\">_ga=GA1.1.997739072.1581080251; XSRF-TOKEN=eyJpdiI6IkV5ZEYxM1wvTHRhMWZoSXR6bWRmNG53PT0iLCJ2YWx1ZSI6ImhTdTBiT0pOYzZRYXFidzE5VTBBOUtSYW9wcnpFZER2VyswSzNZRysyMWxZdjJPU1JJZVNIWWJcL3QrcTE0UmF3IiwibWFjIjoiNTlhY2ZlZjY0YTZiNWNhZGJkOTdmZjg5OTMxNGNkZWFiOTA1N2E2ZTA1ZjhjYjUxMDczNTI1MjYzNWNiMjc1NiJ9; laravel_session=eyJpdiI6ImorN3FOMnAyWDVSeGJ3aUtWT3FESXc9PSIsInZhbHVlIjoiMEtrTWZHQ2h6R0ZYVWJZU2tRTStNdFwvVGlCU3czYUVZQ1RUMmFESURGR2Zlc0V5K2p0NE1QNEthbWYrd1wvVXdBIiwibWFjIjoiZWVlNGQxMmJkYTAxMGEyYTEzZDE5NTNkYjM1YjcxY2E4ODg2OWY0YzA1ZGMwODEyM2UxMWEwYzgzNmQ5ZDc0ZiJ9<\/span>\"\n  \"<span class=sf-dump-key>REQUEST_TIME_FLOAT<\/span>\" => <span class=sf-dump-num>1585818849.1494<\/span>\n  \"<span class=sf-dump-key>REQUEST_TIME<\/span>\" => <span class=sf-dump-num>1585818849<\/span>\n<\/samp>]\n<\/pre><script>Sfdump(\"sf-dump-171881545\", {\"maxDepth\":0})<\/script>\n",
            "request_cookies": "<pre class=sf-dump id=sf-dump-2121544660 data-indent-pad=\"  \"><span class=sf-dump-note>array:3<\/span> [<samp>\n  \"<span class=sf-dump-key>_ga<\/span>\" => <span class=sf-dump-const>null<\/span>\n  \"<span class=sf-dump-key>XSRF-TOKEN<\/span>\" => \"<span class=sf-dump-str title=\"40 characters\">K21BtJtjUL5WoFJkgNNrMqcanCQtRRg5HzbQDOYL<\/span>\"\n  \"<span class=sf-dump-key>laravel_session<\/span>\" => \"<span class=sf-dump-str title=\"40 characters\">z8bNVaaQpd5lsH6cBno18yIXRZLRYyNfKMxNhGE9<\/span>\"\n<\/samp>]\n<\/pre><script>Sfdump(\"sf-dump-2121544660\", {\"maxDepth\":0})<\/script>\n",
            "response_headers": "<pre class=sf-dump id=sf-dump-261661166 data-indent-pad=\"  \"><span class=sf-dump-note>array:5<\/span> [<samp>\n  \"<span class=sf-dump-key>cache-control<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"17 characters\">no-cache, private<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>date<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"29 characters\">Thu, 02 Apr 2020 09:14:10 GMT<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>content-type<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"24 characters\">text\/html; charset=UTF-8<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>set-cookie<\/span>\" => <span class=sf-dump-note>array:2<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"318 characters\">XSRF-TOKEN=eyJpdiI6InBYdWtQdlMwMERVM3VUcU5IR2J5ckE9PSIsInZhbHVlIjoidmhEZDRNM0ZJNFRHRHhHTUNTTXBnWk9iczkxY3ZrXC9yVlZ1ZUNzSFdNdGtSWWVoQ2t6ZXVPTVYyY3k3R0RyemgiLCJtYWMiOiIzOTBlY2EwN2E3ODMxZTI4MDM3NmE3MjhhYTQ0NjVmNDdmNjdmYTUxODQzNjFjNzlmOGVlMDUyN2EwZTUyZGFjIn0%3D; expires=Thu, 02-Apr-2020 11:14:15 GMT; Max-Age=7200; path=\/<\/span>\"\n    <span class=sf-dump-index>1<\/span> => \"<span class=sf-dump-str title=\"339 characters\">laravel_session=eyJpdiI6IldlblBzbEYxXC9PU3Q3TTJIYnd0eUtBPT0iLCJ2YWx1ZSI6IkdBTlpPQXZVYmZTUTY2Nk5jdnpXSENXbEhtREx1Z2xkQVwvNyswSm9kNkR6alhWM0lzTlQ4WEJKXC96bW10MWVWayIsIm1hYyI6IjVjNmQ2ZDhlZTY3YmVkMGFlMzBjNDg4NmNmZTQ4MjE3OTFhOTg3Mjg1NjcxYjViMTc1NGFkYzM0MzYwNzI2ZjIifQ%3D%3D; expires=Thu, 02-Apr-2020 11:14:15 GMT; Max-Age=7200; path=\/; httponly<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>Set-Cookie<\/span>\" => <span class=sf-dump-note>array:2<\/span> [<samp>\n    <span class=sf-dump-index>0<\/span> => \"<span class=sf-dump-str title=\"304 characters\">XSRF-TOKEN=eyJpdiI6InBYdWtQdlMwMERVM3VUcU5IR2J5ckE9PSIsInZhbHVlIjoidmhEZDRNM0ZJNFRHRHhHTUNTTXBnWk9iczkxY3ZrXC9yVlZ1ZUNzSFdNdGtSWWVoQ2t6ZXVPTVYyY3k3R0RyemgiLCJtYWMiOiIzOTBlY2EwN2E3ODMxZTI4MDM3NmE3MjhhYTQ0NjVmNDdmNjdmYTUxODQzNjFjNzlmOGVlMDUyN2EwZTUyZGFjIn0%3D; expires=Thu, 02-Apr-2020 11:14:15 GMT; path=\/<\/span>\"\n    <span class=sf-dump-index>1<\/span> => \"<span class=sf-dump-str title=\"325 characters\">laravel_session=eyJpdiI6IldlblBzbEYxXC9PU3Q3TTJIYnd0eUtBPT0iLCJ2YWx1ZSI6IkdBTlpPQXZVYmZTUTY2Nk5jdnpXSENXbEhtREx1Z2xkQVwvNyswSm9kNkR6alhWM0lzTlQ4WEJKXC96bW10MWVWayIsIm1hYyI6IjVjNmQ2ZDhlZTY3YmVkMGFlMzBjNDg4NmNmZTQ4MjE3OTFhOTg3Mjg1NjcxYjViMTc1NGFkYzM0MzYwNzI2ZjIifQ%3D%3D; expires=Thu, 02-Apr-2020 11:14:15 GMT; path=\/; httponly<\/span>\"\n  <\/samp>]\n<\/samp>]\n<\/pre><script>Sfdump(\"sf-dump-261661166\", {\"maxDepth\":0})<\/script>\n",
            "session_attributes": "<pre class=sf-dump id=sf-dump-999216527 data-indent-pad=\"  \"><span class=sf-dump-note>array:9<\/span> [<samp>\n  \"<span class=sf-dump-key>_token<\/span>\" => \"<span class=sf-dump-str title=\"40 characters\">K21BtJtjUL5WoFJkgNNrMqcanCQtRRg5HzbQDOYL<\/span>\"\n  \"<span class=sf-dump-key>branch_id<\/span>\" => <span class=sf-dump-num>1<\/span>\n  \"<span class=sf-dump-key>url<\/span>\" => []\n  \"<span class=sf-dump-key>_previous<\/span>\" => <span class=sf-dump-note>array:1<\/span> [<samp>\n    \"<span class=sf-dump-key>url<\/span>\" => \"<span class=sf-dump-str title=\"51 characters\">http:\/\/localhost:8000\/admin\/loandisbursement\/create<\/span>\"\n  <\/samp>]\n  \"<span class=sf-dump-key>_flash<\/span>\" => <span class=sf-dump-note>array:2<\/span> [<samp>\n    \"<span class=sf-dump-key>old<\/span>\" => []\n    \"<span class=sf-dump-key>new<\/span>\" => []\n  <\/samp>]\n  \"<span class=sf-dump-key>login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d<\/span>\" => <span class=sf-dump-num>1<\/span>\n  \"<span class=sf-dump-key>s_branch_id<\/span>\" => <span class=sf-dump-num>1<\/span>\n  \"<span class=sf-dump-key>save_action<\/span>\" => \"<span class=sf-dump-str title=\"13 characters\">save_and_back<\/span>\"\n  \"<span class=sf-dump-key>PHPDEBUGBAR_STACK_DATA<\/span>\" => []\n<\/samp>]\n<\/pre><script>Sfdump(\"sf-dump-999216527\", {\"maxDepth\":0})<\/script>\n"
        }
    }, "Xbc423e048f65aede254251ad0d1d90ef");

</script>
</body>
</html>
