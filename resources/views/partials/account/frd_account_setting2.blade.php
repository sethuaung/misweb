<?php
$_e = isset($entry)?$entry:null;
$journal_details = optional($_e)->journal_details;
$currency = optional($_e)->currency;
$symbol = isset($currency->currency_symbol)?$currency->currency_symbol:'';

?>

{{--@extends('backpack::layout')--}}
<?php //$base=asset('vendor/adminlte') ?>
@section('after_styles')

    <!-- Font Awesome -->
    {{--<link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
{{--    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />--}}

    <!-- Theme style -->
{{--    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">--}}


@endsection
@push('crud_fields_styles')
    <style>
        .red{
            color:red;
            font-weight: bold;
        }




    </style>
@endpush

@push('after_styles')
    <style>
        .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{
            border-top: 1px solid #2c2c2c !important;
        }

        .select2-container .select2-selection--single, .select2-container .select2-selection--multiple, .select2-container--bootstrap.select2-container--focus .select2-selection, .select2-container--bootstrap.select2-container--open .select2-selection{
            border-radius: 3px !important;
        }
        .select2-container .select2-selection--multiple .select2-selection__rendered{
            padding-left: 0px;
        }
    </style>
@endpush


<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" data-target="#tab1" href="javascript:void(0)">Profit / Loss</a></li>
    <li><a data-toggle="tab" data-target="#tab2" href="javascript:void(0)">Balance Sheet</a></li>
</ul>

<div class="tab-content">
    <div id="tab1" class="tab-pane fade in active">
        <br>
        <div class="table-responsive">
            <table class="table table-data" border="1">
                <thead>
                <tr style="">
                    <td class="text-center" width="130px"><b>{{_t('No')}}</b></td>
                    <td class="text-center"><b>{{_t('Name')}}</b></td>
                    <td class="text-center" width="300px"><b>{{_t('Account')}}</b></td>
                </tr>
                </thead>
                <tbody class="">



                @include('partials.account.frd_account_setting_detail2', ['no' => '5-21-100-001','title'=>'Credit Income(General Loan)','code'=>'pl-5-21-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-21-100-002','title'=>'Credit Income (Special Loan)','code'=>'pl-5-21-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-21-100-003','title'=>'Credit Income(Agri Loan)','code'=>'pl-5-21-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-21-100-004','title'=>'Credit Income(Teacher Loan)','code'=>'pl-5-21-100-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-21-100-007','title'=>'Credit Income  Staff Loan (Internal )','code'=>'pl-5-21-100-007'])
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '5-21-100-008','title'=>'Credit Income(Micro-Enterprise Loan)','code'=>'pl-5-21-100-008'])--}}
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-21-100-012','title'=>'Credit Income  Staff Loan (External)','code'=>'pl-5-21-100-012'])

{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '5-23-100-001','title'=>'Over Due Income (General Loan)','code'=>'pl-5-23-100-001'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '5-23-100-002','title'=>'Over Due Income (Special  Loan)','code'=>'pl-5-23-100-002'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '5-23-100-003','title'=>'Over Due Income (Agri  Loan)','code'=>'pl-5-23-100-003'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '5-23-100-004','title'=>'Over Due Income (Teacher  Loan)','code'=>'pl-5-23-100-004'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '5-23-100-005','title'=>'Over Due Income (Staff  Loan Internal)','code'=>'pl-5-23-100-005'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '5-23-100-006','title'=>'Over Due Income (Staff  Loan External)','code'=>'pl-5-23-100-006'])--}}

                @include('partials.account.frd_account_setting_detail2', ['no' => '5-73-100-01','title'=>'Member fees','code'=>'pl-5-73-100-01'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-73-100-02','title'=>'Savings Book Revenue','code'=>'pl-5-73-100-02'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-73-100-03','title'=>'Late Fee Collected','code'=>'pl-5-73-100-03'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '5-73-200-001','title'=>'Other income','code'=>'pl-5-73-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-73-200-002','title'=>'Interest on Bank','code'=>'pl-5-73-200-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-73-200-003','title'=>'Penalty Charges','code'=>'pl-5-73-200-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '5-73-200-004','title'=>'Baddebt Recover','code'=>'pl-5-73-200-004'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-12-200-001','title'=>'Interest expenses ( Compulsory )','code'=>'pl-6-12-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-12-200-002','title'=>'Interest expenses ( Voluntory )','code'=>'pl-6-12-200-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-12-200-003','title'=>'Loan Interest to Employee Saving','code'=>'6-12-200-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '67-71-100-001','title'=>'Provisions For Doubtful Debts','code'=>'pl-67-71-100-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-001','title'=>'Salary & Wages - Permanent Staff','code'=>'pl-6-31-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-002','title'=>'Provisions For Doubtful Debts','code'=>'pl-6-31-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-003','title'=>'Without Pay Leave Deduction','code'=>'pl-6-31-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-004','title'=>'Absent Deduction','code'=>'pl-6-31-100-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-005','title'=>'Other Deductioin','code'=>'pl-6-31-100-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-006','title'=>'Salary & Wages -Temporary staff','code'=>'pl-6-31-100-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-007','title'=>'Salary & Wages -Temporary staff','code'=>'pl-6-31-100-007'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-008','title'=>'Earn Leave Payments','code'=>'pl-6-31-100-008'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-010','title'=>'Directors\' Salaries','code'=>'pl-6-31-100-010'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-011','title'=>'Directors\' Remuneration','code'=>'pl-6-31-100-011'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-012','title'=>'Overtime','code'=>'pl-6-31-100-012'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-013','title'=>'Bonus','code'=>'pl-6-31-100-013'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-100-014','title'=>'Attendance Bonus','code'=>'pl-6-31-100-014'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-001','title'=>'Meal Allowance','code'=>'pl-6-31-900-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-002','title'=>'Stipend for Staffs\' Children Sholarship','code'=>'pl-6-31-900-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-003','title'=>'SSB - Employer Contribution','code'=>'pl-6-31-900-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-004','title'=>'SSB - Employee Contribution','code'=>'pl-6-31-900-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-005','title'=>'Recruiting','code'=>'pl-6-31-900-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-006','title'=>'Recruiting','code'=>'pl-6-31-900-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-007','title'=>'Staff incentive award','code'=>'pl-6-31-900-007'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-008','title'=>'Compensation','code'=>'pl-6-31-900-008'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-009','title'=>'Income Tax - Employee Contribution','code'=>'pl-6-31-900-009'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-010','title'=>'Income Tax - Employer Contribution','code'=>'pl-6-31-900-010'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-011','title'=>'Employee Insurance Contributions','code'=>'pl-6-31-900-011'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-012','title'=>'Employer Insurance Contributions','code'=>'pl-6-31-900-012'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-900-013','title'=>'Other personnel expenses','code'=>'pl-6-31-900-013'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-300-001','title'=>'Training Fees','code'=>'pl-6-31-300-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-300-002','title'=>'Meal Allowance & Other','code'=>'pl-6-31-300-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-300-003','title'=>'Transportation Charges (Training fare)','code'=>'pl-6-31-300-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-001','title'=>'Client Incentive Expenses','code'=>'pl-6-53-300-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-002','title'=>'Staff Uniform','code'=>'pl-6-53-300-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-003','title'=>'Entertainment & Incentive Expenses','code'=>'pl-6-53-300-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-004','title'=>'Meeting Expeneses','code'=>'pl-6-53-300-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-31-400-001','title'=>'Medical Benefit','code'=>'pl-6-31-400-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-005','title'=>'Staff Amenities','code'=>'pl-6-53-300-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-69-900-001','title'=>'Miscellaneous Expenses','code'=>'pl-6-69-900-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-200-001','title'=>'Meal & Entertianment','code'=>'pl-6-55-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-006','title'=>'Warranty Expenses','code'=>'pl-6-53-300-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-007','title'=>'Courier & Postage','code'=>'pl-6-53-300-007'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-400-001','title'=>'Licences & Stamp Duty','code'=>'pl-6-54-400-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-008','title'=>'Research & Development','code'=>'pl-6-53-300-008'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-009','title'=>'Transportation','code'=>'pl-6-53-300-009'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-57-100-001','title'=>'Staionary & Utilities Expenses','code'=>'pl-6-57-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-010','title'=>'Housekeeping & Office Supplies','code'=>'pl-6-53-300-010'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-58-900-001','title'=>'Vehicle Insurance','code'=>'pl-6-58-900-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-58-900-002','title'=>'General Insurance','code'=>'pl-6-58-900-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-011','title'=>'Equipment Hire','code'=>'pl-6-53-300-011'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-012','title'=>'Subscriptions','code'=>'pl-6-53-300-012'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-63-100-001','title'=>'Donations','code'=>'pl-6-63-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-013','title'=>'Fax & Telephone Charges','code'=>'pl-6-53-300-013'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-014','title'=>'Other administrative expenses','code'=>'pl-6-53-300-014'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-300-015','title'=>'Bank Charges Paid','code'=>'pl-6-53-300-015'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-100-001','title'=>'Building Maintenance','code'=>'pl-6-53-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-100-002','title'=>'Machinery & Equipment Maintenance','code'=>'pl-6-53-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-100-003','title'=>'Repairs & Renewals','code'=>'pl-6-53-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-100-004','title'=>'Repairs & Maintenance (Office Equipment)','code'=>'pl-6-53-100-004'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-500-001','title'=>'Utilities','code'=>'pl-6-53-500-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-500-002','title'=>'Electricity','code'=>'pl-6-53-500-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-500-003','title'=>'Water','code'=>'pl-6-53-500-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-300-001','title'=>'Audit services','code'=>'pl-6-54-300-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-300-002','title'=>'Consulting services','code'=>'pl-6-54-300-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-500-001','title'=>'Legal services','code'=>'pl-6-54-500-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-300-003','title'=>'Accountancy Fees','code'=>'pl-6-54-300-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-300-004','title'=>'External Services','code'=>'pl-6-54-300-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-300-005','title'=>'Other Professional Fees','code'=>'pl-6-54-300-005'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-300-001','title'=>'Communication Allowance','code'=>'pl-6-55-300-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-300-002','title'=>'SAP Service Fees','code'=>'pl-6-55-300-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-300-003','title'=>'Fiber Internet Line Expenses','code'=>'pl-6-55-300-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-300-004','title'=>'Computer Accessories','code'=>'pl-6-55-300-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-300-005','title'=>'Hardware Expenses','code'=>'pl-6-55-300-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-300-006','title'=>'Other IT Expenses','code'=>'pl-6-55-300-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-300-007','title'=>'Corporate Media','code'=>'pl-6-55-300-007'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-100-02','title'=>'Fares','code'=>'pl-6-55-100-02'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-100-03','title'=>'Accommodation - domestic','code'=>'pl-6-55-100-03'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-100-04','title'=>'Travel Costs - Other','code'=>'pl-6-55-100-04'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-150-001','title'=>'Fares','code'=>'pl-6-55-150-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-150-002','title'=>'Accommodation - international','code'=>'pl-6-55-150-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-55-150-003','title'=>'Travel Costs - Other','code'=>'pl-6-55-150-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-54-100-001','title'=>'Advertising & Promotion, own','code'=>'pl-6-54-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-57-200-001','title'=>'Printing and Forms Expenses','code'=>'pl-6-57-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-51-500-001','title'=>'Bad Debt Expense','code'=>'pl-6-51-500-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-68-100-001','title'=>'Motor Vehicle Up keep  for Office','code'=>'pl-6-68-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-68-100-002','title'=>'Motor Vehicle Up keep  for Staff','code'=>'pl-6-68-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-68-100-003','title'=>'Auto  Vehicle Up keep  for Office','code'=>'pl-6-68-100-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-68-200-001','title'=>'Motor Vehicle (Petrol) For Office','code'=>'pl-6-68-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-68-200-002','title'=>'Motor Vehicle (Petrol) for Staff','code'=>'pl-6-68-200-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-68-200-003','title'=>'Auto Vehicle (Petrol) For Office','code'=>'pl-6-68-200-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-200-001','title'=>'Rental Charges of Office','code'=>'pl-6-53-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-200-002','title'=>'Rental Charges of Vehicle','code'=>'pl-6-53-200-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-200-003','title'=>'Rental Charges of Cycle','code'=>'pl-6-53-200-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-53-200-004','title'=>'Rental Charges of computer','code'=>'pl-6-53-200-004'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-42-400-002','title'=>'Depreciation of Tools & Equipments','code'=>'pl-6-42-400-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-42-350-001','title'=>'Depreciation of Furniture, Fixture & Fitting','code'=>'pl-6-42-350-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-42-500-001','title'=>'Depreciation of Computer & IT','code'=>'pl-6-42-500-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-42-600-001','title'=>'Depreciation of Motor Vehicles','code'=>'pl-6-42-600-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-42-600-002','title'=>'Depreciation of Motor Cycles','code'=>'pl-6-42-600-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-42-400-001','title'=>'Depreciation of Electronic & Electrical','code'=>'pl-6-42-400-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-43-100-01','title'=>'Amortization of Capitalised formation costs','code'=>'pl-6-43-100-01'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-43-100-02','title'=>'Amortization of cap software','code'=>'pl-6-43-100-02'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-43-100-03','title'=>'Amortization of other intangible assets','code'=>'pl-6-43-100-03'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-92-100-01','title'=>'Interest expenses','code'=>'pl-6-92-100-01'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-92-100-02','title'=>'Bank Interest Paid','code'=>'pl-6-92-100-02'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '6-69-100-001','title'=>'Current income taxes','code'=>'pl-6-69-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '6-69-100-002','title'=>'Deferred taxes','code'=>'pl-6-69-100-002'])




                </tbody>

            </table>
        </div>

    </div>
    <div id="tab2" class="tab-pane fade">
        <br>
        <div class="table-responsive">
            <table class="table table-data" border="1">
                <thead>
                <tr style="">
                    <td class="text-center" width="130px"><b>{{_t('No')}}</b></td>
                    <td class="text-center"><b>{{_t('Name')}}</b></td>
                    <td class="text-center" width="300px"><b>{{_t('Account')}}</b></td>
                </tr>
                </thead>
                <tbody class="">


                @include('partials.account.frd_account_setting_detail2', ['no' => '2-95-100-001','title'=>'Capitalised Business Expansion Costs','code'=>'bs-2-95-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-920-001','title'=>'Capitalised Formation acct amortization','code'=>'bs-2-94-920-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-05-200-001','title'=>'Capitalised Software - Cost','code'=>'bs-2-05-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-920-002','title'=>'Capitalised Software - acct amortization','code'=>'bs-2-94-920-002'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-95-300-001','title'=>'Other Intangible Assets','code'=>'bs-2-95-300-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-910-002','title'=>'Other Intangible Assets-acct amortization','code'=>'bs-2-94-910-002'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-93-100-001','title'=>'Buildings - Cost','code'=>'bs-2-93-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-400-001','title'=>'Building - acct depreciation','code'=>'bs-2-94-400-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-93-200-001','title'=>'Furnitures Fixtures & Fittings - Cost','code'=>'bs-2-93-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-400-002','title'=>'Furnitures  Furniture & Fittings - acct depreciation','code'=>'bs-2-94-400-002'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-93-300-001','title'=>'General Electronics - Cost','code'=>'bs-2-93-300-001'])
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-400-003','title'=>'General Electronics - accumulated depreciation','code'=>'bs-2-94-400-003'])--}}

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-93-400-001','title'=>'Computer & IT Equipments - Cost','code'=>'bs-2-93-400-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-400-004','title'=>'Computer & IT Equipments - acct depreciation','code'=>'bs-2-94-400-004'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-400-005','title'=>'Motor Vehicles - acct depreciation','code'=>'bs-2-94-400-005'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-93-600-001','title'=>'Tools & Equipment - Cost','code'=>'bs-2-93-600-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-94-400-006','title'=>'Tools & Equipment - acct depreciation','code'=>'bs-2-94-400-006'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-001','title'=>'KBZ Bank (CC) (027-103-027-030-800-01) HO','code'=>'bs-1-15-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-002','title'=>'KBZ Bank (169-503-169-000-551-01)(HO)','code'=>'bs-1-15-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-003','title'=>'KBZ Bank (CC)(169-103-169-000-551-01)(HO)','code'=>'bs-1-15-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-004','title'=>'KBZ Bank (049-503-049-007-888-01)','code'=>'bs-1-15-100-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-005','title'=>'KBZ Bank (CC) (049-103-049-007-872-01)','code'=>'bs-1-15-100-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-006','title'=>'KBZ Bank (056-113-000-007-7)','code'=>'bs-1-15-100-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-007','title'=>'KBZ Bank (164-502-999-336-419-01)','code'=>'bs-1-15-100-007'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-008','title'=>'KBZ Bank (CC) (164-103-164-006-936-01)','code'=>'bs-1-15-100-008'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-009','title'=>'KBZ Bank (164-503-164-006-936-01)','code'=>'bs-1-15-100-009'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-010','title'=>'KBZ Bank (352-503-352-000-779-01)','code'=>'bs-1-15-100-010'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-011','title'=>'KBZ Bank (CC) (352-103-352-000-779-01)','code'=>'bs-1-15-100-011'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-012','title'=>'KBZ Bank (CC) (025-103-025-015-841-01)','code'=>'bs-1-15-100-012'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-013','title'=>'KBZ Bank (025-113-000-039-0)','code'=>'bs-1-15-100-013'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-014','title'=>'KBZ Bank (025-503-025-015-841-01)','code'=>'bs-1-15-100-014'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-015','title'=>'KBZ Bank(312-503-174-002-497-01)','code'=>'bs-1-15-100-015'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-016','title'=>'KBZ Bank(CC) (312-103-174-002-497-01)','code'=>'bs-1-15-100-016'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-017','title'=>'KBZ Bank (174-503-174-002-500-01)','code'=>'bs-1-15-100-017'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-018','title'=>'KBZ Bank (CC) (174-103-174-002-497-01)','code'=>'bs-1-15-100-018'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-019','title'=>'KBZ Bank (168-173-000-001-9)','code'=>'bs-1-15-100-019'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-020','title'=>'KBZ Bank (168-503-168-003-751-01)','code'=>'bs-1-15-100-020'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-021','title'=>'KBZ Bank (CC) (168-103-168-003-749-01)','code'=>'bs-1-15-100-021'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-022','title'=>'KBZ Bank (067-503-067-004-937-01)','code'=>'bs-1-15-100-022'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-023','title'=>'KBZ Bank (CC)  (067-103-067-004-924-01)','code'=>'bs-1-15-100-023'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-024','title'=>'KBZ Bank (CC) (056-113-000-007-7)','code'=>'bs-1-15-100-024'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-025','title'=>'KBZ Bank (056-503-056-005-467-01)','code'=>'bs-1-15-100-025'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-026','title'=>'KBZ Bank (092-503-092-004-060-01)','code'=>'bs-1-15-100-026'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-027','title'=>'KBZ Bank (CC) (092-103-092-004-056-01)','code'=>'bs-1-15-100-027'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-028','title'=>'KBZ Bank (307-503-307-000-574-01)','code'=>'bs-1-15-100-028'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-029','title'=>'KBZ Bank (CC) (307-103-307-000-574-01)','code'=>'bs-1-15-100-029'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-030','title'=>'KBZ Bank (031-503-031-010-203-01)','code'=>'bs-1-15-100-030'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-031','title'=>'KBZ Bank (CC) (031-103-031-010-197-01)','code'=>'bs-1-15-100-031'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-032','title'=>'KBZ Bank (129-503-129-002-722-01)','code'=>'bs-1-15-100-032'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-033','title'=>'KBZ Bank (CC) (129-103-129-002-720-01)','code'=>'bs-1-15-100-033'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-034','title'=>'KBZ Bank (058-503-058-011-982-01)','code'=>'bs-1-15-100-034'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-035','title'=>'KBZ Bank (CC) (058-103-058-011-982-01)','code'=>'bs-1-15-100-035'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-036','title'=>'KBZ Bank (341-503-341-000-709-01)','code'=>'bs-1-15-100-036'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-037','title'=>'KBZ Bank (CC) (341-103-341-000-709-01)','code'=>'bs-1-15-100-037'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-038','title'=>'KBZ Bank (035-503-035-007-946-01)','code'=>'bs-1-15-100-038'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-039','title'=>'KBZ Bank (CC) (035-103-035-007-946-01)','code'=>'bs-1-15-100-039'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-040','title'=>'KBZ Bank (196-503-196-003-904-01)','code'=>'bs-1-15-100-040'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-041','title'=>'KBZ Bank (CC)  (196-103-196-003-904-01)','code'=>'bs-1-15-100-041'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-042','title'=>'KBZ (087-503-087-015-045-01)','code'=>'bs-1-15-100-042'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-043','title'=>'KBZ (CC) (087-103-087-015-045-01)','code'=>'bs-1-15-100-043'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-045','title'=>'AYA Bank (007-822-401-000-884-5) (HO)','code'=>'bs-1-15-100-045'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-046','title'=>'AYA Bank (CC) (007-810-301-000-755-4) (HO)','code'=>'bs-1-15-100-046'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-047','title'=>'AYA Bank (015-122-401-000-116-5)','code'=>'bs-1-15-100-047'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-048','title'=>'AYA Bank (CC) (015-110-301-000-034-0)','code'=>'bs-1-15-100-048'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-049','title'=>'AYA Bank (016-222-401-000-055-9)','code'=>'bs-1-15-100-049'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-050','title'=>'AYA Bank (CC) (016-210-301-000-010-6)','code'=>'bs-1-15-100-050'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-051','title'=>'AYA Bank (000-722-401-000-491-9)','code'=>'bs-1-15-100-051'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-052','title'=>'AYA Bank (CC) (000-710-201-000-725-4)','code'=>'bs-1-15-100-052'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-053','title'=>'AYA Bank (005-822-401-000-150-1)','code'=>'bs-1-15-100-053'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-054','title'=>'AYA Bank (CC) (005-810-301-000-003-4)','code'=>'bs-1-15-100-054'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-055','title'=>'AYA Bank (008-822-401-000-138-3)','code'=>'bs-1-15-100-055'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-056','title'=>'AYA Bank (CC) (008-810-201-000-120-7)','code'=>'bs-1-15-100-056'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-057','title'=>'AYA Bank (008-820-201-001-948-3)','code'=>'bs-1-15-100-057'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-058','title'=>'AYA Bank (002-810-301-000-032-4)','code'=>'bs-1-15-100-058'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-059','title'=>'AYA Bank (CC) (002-822-401-000-094-0)','code'=>'bs-1-15-100-059'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-060','title'=>'AYA Bank (002-622-401-000-188-8)','code'=>'bs-1-15-100-060'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-061','title'=>'AYA Bank (CC) (002-610-301-000-098-8)','code'=>'bs-1-15-100-061'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-062','title'=>'AYA Bank (018-622-401-000-036-0)','code'=>'bs-1-15-100-062'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-063','title'=>'AYA Bank (CC) (018-610-301-000-016-4)','code'=>'bs-1-15-100-063'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-064','title'=>'AYA Bank (014-722-401-000-092-4)','code'=>'bs-1-15-100-064'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-065','title'=>'AYA Bank (CC) (014-710-301-000-027-8)','code'=>'bs-1-15-100-065'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-068','title'=>'GTB Bank-C-(070-113-000-002-8) BR002','code'=>'bs-1-15-100-068'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-069','title'=>'CB Bank(011-660-080-000-007-8)','code'=>'bs-1-15-100-069'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-070','title'=>'CB Bank (CC) (011-610-050-000-001-9)','code'=>'bs-1-15-100-070'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-071','title'=>'CB Bank (009-160-080-000-096-5)','code'=>'bs-1-15-100-071'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-072','title'=>'CB Bank (CC) (009-110-050-000-012-4)','code'=>'bs-1-15-100-072'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-073','title'=>'CB Bank(007-060-080-000-081-7)','code'=>'bs-1-15-100-073'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-074','title'=>'CB Bank (CC) (007-010-050-000-005-1)','code'=>'bs-1-15-100-074'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-075','title'=>'MOB Bank(000-260-030-000-1098) (HO)','code'=>'bs-1-15-100-075'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-076','title'=>'MOB Bank (CC) (000-210-030-001-2475) (HO)','code'=>'bs-1-15-100-076'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-077','title'=>'MOB Bank(CC)(203-113-000-002-2)','code'=>'bs-1-15-100-077'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-078','title'=>'MOB Bank(203-123-000-003-8)','code'=>'bs-1-15-100-078'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-079','title'=>'MOB Bank (003-260-530-000-001-9)','code'=>'bs-1-15-100-079'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-080','title'=>'MOB Bank (CC) (003-210-030-000-002-7)','code'=>'bs-1-15-100-080'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-081','title'=>'MOB Bank(000-210-110-000-136-8) (HO)Overdraft','code'=>'bs-1-15-100-081'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-001','title'=>'Cash Account (HO)','code'=>'bs-1-11-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-002','title'=>'01 Cash Account (Kyauk Se-2)','code'=>'bs-1-11-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-003','title'=>'02 Cash Account(Kyaung Gone)','code'=>'bs-1-11-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-004','title'=>'03 Cash Account(Shwe Bo-1)','code'=>'bs-1-11-100-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-005','title'=>'04 Cash Account(Maddaya-1)','code'=>'bs-1-11-100-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-006','title'=>'05 Cash Account(Kyauk Se-1)','code'=>'bs-1-11-100-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-007','title'=>'06 Cash Account(Kyone Pyaw)','code'=>'bs-1-11-100-007'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-008','title'=>'07 Cash Account(MonYwar)','code'=>'bs-1-11-100-008'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-009','title'=>'08 Cash Account(WetLet)','code'=>'bs-1-11-100-009'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-010','title'=>'09 Cash Account(Sint Kaing)','code'=>'bs-1-11-100-010'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-011','title'=>'10 Cash Account(KuMe)','code'=>'bs-1-11-100-011'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-012','title'=>'11 Cash Account(Maddaya-2)','code'=>'bs-1-11-100-012'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-013','title'=>'12 Cash Account(Pyin Oo Lwin)','code'=>'bs-1-11-100-013'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-014','title'=>'13 Cash Account(Nyaung  Oo)','code'=>'bs-1-11-100-014'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-015','title'=>'14 Cash Account(Shwe Bo-2)','code'=>'bs-1-11-100-015'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-016','title'=>'15 Cash Account(Meik Hti La)','code'=>'bs-1-11-100-016'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-017','title'=>'16 Cash Account(Eain Me)','code'=>'bs-1-11-100-017'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-018','title'=>'17 Cash Account(Myin Gyan 1)','code'=>'bs-1-11-100-018'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-019','title'=>'18 Cash Account(Wun Dwin)','code'=>'bs-1-11-100-019'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-020','title'=>'19 Cash Account(TaDaOo)','code'=>'bs-1-11-100-020'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-021','title'=>'20 Cash Account(Sagaing)','code'=>'bs-1-11-100-021'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-022','title'=>'21 Cash Account(Pyay)','code'=>'bs-1-11-100-022'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-024','title'=>'22 Cash Account(Aung Ban)','code'=>'bs-1-11-100-024'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-025','title'=>'23 Cash Account(Taungoo)','code'=>'bs-1-11-100-025'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-026','title'=>'24 Cash Account(Paungde)','code'=>'bs-1-11-100-026'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-11-100-027','title'=>'25 Cash Account(Amayapura)','code'=>'bs-1-11-100-027'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-001','title'=>'01 Cash Transit  (Kyauk Se-2)','code'=>'bs-2-96-500-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-002','title'=>'02 Cash Transit (Kyaung Gone)','code'=>'bs-2-96-500-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-003','title'=>'03 Cash Transit (Shwe Bo-1)','code'=>'bs-2-96-500-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-004','title'=>'04 Cash Transit (Maddaya-1)','code'=>'bs-2-96-500-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-006','title'=>'05 Cash Transit (Kyauk Se-1)','code'=>'bs-2-96-500-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-009','title'=>'06 Cash Transit (Kyone Pyaw)','code'=>'bs-2-96-500-009'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-007','title'=>'07 Cash Transit (MonYwar)','code'=>'bs-2-96-500-007'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-008','title'=>'08 Cash Transit (WetLet)','code'=>'bs-2-96-500-008'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-005','title'=>'09 Cash Transit (Sint Kaing)','code'=>'bs-2-96-500-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-010','title'=>'10 Cash Transit (KuMe)','code'=>'bs-2-96-500-010'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-011','title'=>'11 Cash Transit (Maddaya-2)','code'=>'bs-2-96-500-011'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-012','title'=>'12 Cash Transit (Pyin Oo Lwin)','code'=>'bs-2-96-500-012'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-013','title'=>'13 Cash Transit (Nyaung  Oo)','code'=>'bs-2-96-500-013'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-014','title'=>'14 Cash Transit (Shwe Bo-2)','code'=>'bs-2-96-500-014'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-015','title'=>'15 Cash Transit (Meik Hti La)','code'=>'bs-2-96-500-015'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-016','title'=>'16 Cash Transit (Eain Me)','code'=>'bs-2-96-500-016'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-017','title'=>'17 Cash Transit (Myin Gyan 1)','code'=>'bs-2-96-500-017'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-018','title'=>'18 Cash Transit (Wun Dwin)','code'=>'bs-2-96-500-018'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-019','title'=>'19 Cash Transit (TaDaOo)','code'=>'bs-2-96-500-019'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-020','title'=>'20 Cash Transit (Sagaing)','code'=>'bs-2-96-500-020'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-021','title'=>'21 Cash Transit (Pyay)','code'=>'bs-2-96-500-021'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-022','title'=>'BR022 Cash Transit (Myin gyan 2)','code'=>'bs-2-96-500-022'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-023','title'=>'22 Cash Transit (Aung Ban)','code'=>'bs-2-96-500-023'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-024','title'=>'23 Cash Transit (Taungoo)','code'=>'bs-2-96-500-024'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-025','title'=>'24 Cash Transit (Amarapura)','code'=>'bs-2-96-500-025'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-026','title'=>'25 Cash Transit (Paungde)','code'=>'bs-2-96-500-026'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-96-500-050','title'=>'Cash Transit (H.O)','code'=>'bs-2-96-500-050'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-045','title'=>'Inventories in transit','code'=>'bs-1-15-100-045'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-046','title'=>'Inventories in transit','code'=>'bs-1-15-100-046'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-15-100-047','title'=>'Stationery Supply and Inventory','code'=>'bs-1-15-100-047'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-31-100-001','title'=>'Service Receivable(General Loan)','code'=>'bs-1-31-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-31-100-002','title'=>'Service Receivable(Special Loan)','code'=>'bs-1-31-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-31-100-003','title'=>'Service Receivable(Agri Loan)','code'=>'bs-1-31-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-31-100-004','title'=>'Service Receivable(Teacher Loan)','code'=>'bs-1-31-100-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-31-100-005','title'=>'Service Receivable(Staff Loan Internal)','code'=>'bs-1-31-100-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-31-100-006','title'=>'Service Receivable - Staff Loan (External)','code'=>'bs-1-31-100-006'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '1-31-100-007','title'=>'Service Receivable(Micro-Enterprise Loan)','code'=>'bs-1-31-100-007'])

{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-51-100-001','title'=>'Interest Receivable(General Loan)','code'=>'bs-2-51-100-001'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-51-100-002','title'=>'Interest Receivable(Special Loan)','code'=>'bs-2-51-100-002'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-51-100-003','title'=>'Interest Receivable(Agri Loan)','code'=>'bs-2-51-100-003'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-51-100-004','title'=>'Interest Receivable(Teacher Loan)','code'=>'bs-2-51-100-004'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-51-100-007','title'=>'Interest Receivable(Staff Loan Internal)','code'=>'bs-2-51-100-007'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-51-100-009','title'=>'Interest Receivable(Micro-Enterprise Loan)','code'=>'bs-2-51-100-009'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '2-51-100-010','title'=>'Interest Receivable - Staff Loan (External)','code'=>'bs-2-51-100-010'])--}}

                @include('partials.account.frd_account_setting_detail2', ['no' => '1-71-200-001','title'=>'Provisions For Doubtful Debts','code'=>'bs-1-71-200-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-89-800-001','title'=>'Advance Income Tax','code'=>'bs-2-89-800-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '2-21-500-001','title'=>'Prepayment Expenses','code'=>'bs-2-21-500-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-21-500-002','title'=>'Other Advance Payments','code'=>'bs-2-21-500-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-21-500-003','title'=>'Advances on Salaries and Wages','code'=>'bs-2-21-500-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-21-500-004','title'=>'Advances on Travel Expenses','code'=>'bs-2-21-500-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-21-500-005','title'=>'Advance Payments to Staff','code'=>'bs-2-21-500-005'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '2-21-500-006','title'=>'Prepaid Rent','code'=>'bs-2-21-500-006'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-33-200-001','title'=>'Other loans due >1 year','code'=>'bs-3-33-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-33-200-002','title'=>'Bank loans long - term','code'=>'bs-3-33-200-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-33-200-003','title'=>'Mortgages long- term','code'=>'bs-3-33-200-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-22-900-001','title'=>'Refundable Saving (General Loan)','code'=>'bs-3-22-900-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-22-900-002','title'=>'Voluntary Savings (General Loan)','code'=>'bs-3-22-900-002'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-42-200-001','title'=>'Interest Payable Saving (General Loan)','code'=>'bs-3-42-200-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-42-200-002','title'=>'Interest Payable Voluntary(General Loan)','code'=>'bs-3-42-200-002'])

{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '3-22-900-003','title'=>'Employees\' Saving Schemes Payments(Principle)','code'=>'bs-3-22-900-003'])--}}
{{--                @include('partials.account.frd_account_setting_detail2', ['no' => '3-42-200-003','title'=>'Employees\' Saving Schemes Payments(Interest)','code'=>'bs-3-42-200-003'])--}}

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-81-100-002','title'=>'Amount Due to CCM','code'=>'bs-3-81-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-81-100-003','title'=>'Bank Loans due <1 year','code'=>'bs-3-81-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-81-100-004','title'=>'Bank overdrafts','code'=>'bs-3-81-100-004'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-81-100-005','title'=>'Bank loans & mortages ct','code'=>'bs-3-81-100-005'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-63-100-001','title'=>'Service Tax Payable','code'=>'bs-3-63-100-001'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-61-100-001','title'=>'Salaries & Wages Payable','code'=>'bs-3-61-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-61-100-002','title'=>'SSB Payable','code'=>'bs-3-61-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-61-100-003','title'=>'Earn Leave  payable','code'=>'bs-3-61-100-003'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-61-100-004','title'=>'Other Payables ( Uniform )','code'=>'bs-3-61-100-004'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-62-100-001','title'=>'Accrued expenses other','code'=>'bs-3-62-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-62-100-002','title'=>'Accrued purchases (goods in transit)','code'=>'bs-3-62-100-002'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '3-89-980-001','title'=>'Payable Audit Fees','code'=>'bs-3-89-980-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-89-800-001','title'=>'Sinking Funds (1% Mutual Assistant Fund)','code'=>'bs-3-89-800-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '3-81-100-002','title'=>'Other Payable(Tractor Finance)','code'=>'bs-3-81-100-002'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '4-01-100-01','title'=>'Paid In Capital','code'=>'bs-4-01-100-01'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '4-01-100-02','title'=>'Shares & paid in capital','code'=>'bs-4-01-100-02'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '4-05-500-001','title'=>'General Reserves','code'=>'bs-4-05-500-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '4-05-500-002','title'=>'Project Reserves','code'=>'bs-4-05-500-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '4-05-500-003','title'=>'Other Reserves','code'=>'bs-4-05-500-003'])

                @include('partials.account.frd_account_setting_detail2', ['no' => '4-07-100-001','title'=>'Retained Earnings','code'=>'bs-4-07-100-001'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '4-07-100-002','title'=>'Retained Earning Current Year','code'=>'bs-4-07-100-002'])
                @include('partials.account.frd_account_setting_detail2', ['no' => '4-07-100-003','title'=>'Suspense Account','code'=>'bs-4-07-100-003'])



                </tbody>
            </table>

        </div>
    </div>

</div>

@push('after_scripts')
{{--    <script src="{{ asset('vendor/adminlte/bower_components/moment/min/moment.min.js') }}"></script>--}}
{{--    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>--}}
{{--    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>--}}
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>

    <script>
        $(function () {
            $('#saveActions').hide();

        });

        $('.js-acc_code').select2({
            theme: 'bootstrap',
            multiple: true,
            ajax: {
                url: '{{url("api/acc-chart-frd2")}}',
                dataType: 'json',
                quietMillis: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page, // pagination
                    };
                },
                processResults: function (data, params) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    params.page = params.page || 1;
                    var result = {
                        results: $.map(data.data, function (item) {

                            return {
                                text: item["code"]+'-'+item["name"],
                                id: item["id"]
                            }
                        }),
                        pagination: {
                            more: data.current_page < data.last_page
                        }
                    };
                    return result;
                }
            }
        });


        $('.js-acc_code').on("change",function () {

            var code=$(this).data('code');
            var v=$(this).val();
            // console.log(code);

            $.ajax({
                url: '{{backpack_url('update-frd-profit-loss2')}}',
                type: 'GET',
                async: false,
                dataType: 'json',
                data: {
                    code:code,
                    v:v
                },
                success: function (d) {
                    // console.log(d);
                },
                error: function (d) {
                    alert('error');
                }
            });


        });




    </script>
@endpush
