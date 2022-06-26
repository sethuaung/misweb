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
                    <td class="text-center" width="10px"><b>{{_t('No')}}</b></td>
                    <td class="text-center"><b>{{_t('Description')}}</b></td>
                    <td class="text-center" width="300px"><b>{{_t('Chart of Account')}}</b></td>
                </tr>
                </thead>
                <tbody class="">

                <tr>
                    <td>
                        <b>1</b>
                    </td>
                    <td><b>Interest</b></td>
                    <td></td>
                </tr>
                @include('partials.account.frd_account_setting_detail', ['no' => 1.1,'title'=>'Interest Income from Loans to Customers','code'=>'pl-1.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => 1.2,'title'=>'Accounts with Banks and Financial Institutions','code'=>'pl-1.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => 1.3,'title'=>'Securities and Investments','code'=>'pl-1.3'])
                @include('partials.account.frd_account_setting_detail', ['no' => 1.3,'title'=>'Others','code'=>'pl-1.4'])

                <tr>
                    <td>
                        <b>2</b>
                    </td>
                    <td><b>Interest Expenses</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => 2.1,'title'=>'Interest on Customers Deposits','code'=>'pl-2.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => 2.2,'title'=>'Interest on Deposits from Banks and Other Financial Institutions','code'=>'pl-2.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => 2.3,'title'=>'Interest Expense on Borrowings','code'=>'pl-2.3'])
                @include('partials.account.frd_account_setting_detail', ['no' => 2.4,'title'=>'Others','code'=>'pl-2.4'])


                <tr>
                    <td>
                        <b>3</b>
                    </td>
                    <td><b>Net Interest Income (1-2)</b></td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                        <b>4</b>
                    </td>
                    <td><b>Non-interest Income (net)</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => 4.1,'title'=>'Commission and Fees Income','code'=>'pl-4.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => 4.2,'title'=>'Other Non-interest Income','code'=>'pl-4.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => 5,'title'=>'Foreign Exchange Gain / Loss','code'=>'pl-5'])
                @include('partials.account.frd_account_setting_detail', ['no' => 6,'title'=>'Other Income','code'=>'pl-6'])


                <tr>
                    <td>
                        <b>7</b>
                    </td>
                    <td><b>Operating Income (3+4+5+6)</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => 8,'title'=>'Staff Expenses','code'=>'pl-8'])
                @include('partials.account.frd_account_setting_detail', ['no' => 9,'title'=>'Admin and General Expenses','code'=>'pl-9'])
                @include('partials.account.frd_account_setting_detail', ['no' => 10,'title'=>'Depreciation','code'=>'pl-10'])
                @include('partials.account.frd_account_setting_detail', ['no' => 11,'title'=>'Loan Loss Provision','code'=>'pl-11'])


                <tr>
                    <td>
                        <b>12</b>
                    </td>
                    <td><b>Profit from Operations (7-8+9+10+11)</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => 13,'title'=>'Grant Income','code'=>'pl-13'])
                @include('partials.account.frd_account_setting_detail', ['no' => 14,'title'=>'Adjustments for Subsidies','code'=>'pl-14'])

                <tr>
                    <td>
                        <b>15</b>
                    </td>
                    <td><b>Profit before Tax (12+13+14)</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => 16,'title'=>'Tax on Profit','code'=>'pl-16'])


                <tr>
                    <td>
                        <b>17</b>
                    </td>
                    <td><b>Net Profit for the period (15-16)</b></td>
                    <td></td>
                </tr>

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
                    <td class="text-center" width="10px"><b>{{_t('No')}}</b></td>
                    <td class="text-center"><b>{{_t('Account Name')}}</b></td>
                    <td class="text-center" width="300px"><b>{{_t('Chart of Account')}}</b></td>
                </tr>
                </thead>
                <tbody class="">

                <tr>
                    <td>
                        <b>Assets</b>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                        <b>1</b>
                    </td>
                    <td><b>Cash and Balances in Banks</b></td>
                    <td></td>
                </tr>



                @include('partials.account.frd_account_setting_detail', ['no' => 1.1,'title'=>'Cash on Hand and in Vault','code'=>'bs-1.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => 1.2,'title'=>'Balance with Banks and Other Financial Institutions','code'=>'bs-1.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>2</b>','title'=>'<b>Marketable Securities and Short-Term Investments</b>','code'=>'bs-2'])


                <tr>
                    <td>
                        <b>3</b>
                    </td>
                    <td><b>Loans to Customers (3.1 - 3.2)</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => 3.1,'title'=>'Total Loans Outstanding','code'=>'bs-3.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => 3.2,'title'=>'Less: Loan Loss Reserve','code'=>'bs-3.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>4</b>','title'=>'<b>Prepayments and Other Receivables</b>','code'=>'bs-4'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>5</b>','title'=>'<b>Long-term Investments</b>','code'=>'bs-5'])

                <tr>
                    <td>
                        <b>6</b>
                    </td>
                    <td><b>Property and Equipment</b></td>
                    <td></td>
                </tr>


                @include('partials.account.frd_account_setting_detail', ['no' => 6.1,'title'=>'Land','code'=>'bs-6.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.1.1','title'=>'<span style="margin-left:20px"></span>'.'Land at Cost','code'=>'bs-6.1.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.1.2','title'=>'<span style="margin-left:20px"></span>'.'Less: Accumulated Depreciation (Land)','code'=>'bs-6.1.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.2','title'=>'Buildings','code'=>'bs-6.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.2.1','title'=>'<span style="margin-left:20px"></span>'.'Building at Cost','code'=>'bs-6.2.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.2.2','title'=>'<span style="margin-left:20px"></span>'.'Less: Accumulated Depreciation (Buildings)','code'=>'bs-6.2.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.3','title'=>'Other Fixed Assets','code'=>'bs-6.3'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.3.1','title'=>'<span style="margin-left:20px"></span>'.'Other Fixed Assets at Cost','code'=>'bs-6.3.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '6.3.2','title'=>'<span style="margin-left:20px"></span>'.'Less: Accumulated Depreciation (Other Fixed Assets)','code'=>'bs-6.3.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>7</b>','title'=>'<b>Other Assets</b>','code'=>'bs-7'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>8</b>','title'=>'<b>Interest Receivable</b>','code'=>'bs-8'])

                <tr>
                    <td>
                    </td>
                    <td><b>Total Assets (1+2+3+4+5+6+7+8)</b></td>
                    <td></td>
                </tr>


                <tr>
                    <td>
                        <b>9</b>
                    </td>
                    <td><b>Customers' Deposits</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => '9.1','title'=>'Compulsory Savings','code'=>'bs-9.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '9.2','title'=>'Voluntary Savings','code'=>'bs-9.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '9.2.1','title'=>'<span style="margin-left:20px"></span>'.'Saving Deposits','code'=>'bs-9.2.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '9.2.2','title'=>'<span style="margin-left:20px"></span>'.'Demand Deposits','code'=>'bs-9.2.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '9.2.3','title'=>'<span style="margin-left:20px"></span>'.'Term Deposits','code'=>'bs-9.2.3'])
                @include('partials.account.frd_account_setting_detail', ['no' => '9.2.4','title'=>'<span style="margin-left:20px"></span>'.'Other Deposits','code'=>'bs-9.2.4'])


                @include('partials.account.frd_account_setting_detail', ['no' => '<b>10</b>','title'=>'<b>Deposits from Banks and Other Financial Institutions</b>','code'=>'bs-10'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>11</b>','title'=>'<b>Accounts Payable</b>','code'=>'bs-11'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>12</b>','title'=>'<b>Accrued Expenses</b>','code'=>'bs-12'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>13</b>','title'=>'<b>Long-term Borrowing</b>','code'=>'bs-13'])

                @include('partials.account.frd_account_setting_detail', ['no' => '13.1','title'=>'<span style="margin-left:20px"></span>'.'Borrowings from Financial Institutions','code'=>'bs-13.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '13.2','title'=>'<span style="margin-left:20px"></span>'.'Borrowings from Non-Financial Institutions','code'=>'bs-13.2'])


                @include('partials.account.frd_account_setting_detail', ['no' => '<b>14</b>','title'=>'<b>Deferred Grant Income</b>','code'=>'bs-14'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>15</b>','title'=>'<b>Suspense, Clearing and Inter-branch Account</b>','code'=>'bs-15'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>16</b>','title'=>'<b>Other Liabilities</b>','code'=>'bs-16'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>17</b>','title'=>'<b>Interest Payable for Deposit</b>','code'=>'bs-17'])
                @include('partials.account.frd_account_setting_detail', ['no' => '<b>18</b>','title'=>'<b>Interest Payable for Borrowing</b>','code'=>'bs-18'])

                <tr>
                    <td>
                    </td>
                    <td><b>Total Liabilities (9+10+11+12+13+14+15+16+17+18)</b></td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                        <b>Assets</b>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                        <b>19</b>
                    </td>
                    <td><b>Equity</b></td>
                    <td></td>
                </tr>

                @include('partials.account.frd_account_setting_detail', ['no' => '19.1','title'=>'<span style="margin-left:20px"></span>'.'Paid up Capital','code'=>'bs-19.1'])
                @include('partials.account.frd_account_setting_detail', ['no' => '19.2','title'=>'<span style="margin-left:20px"></span>'.'Premium on Share Capital','code'=>'bs-19.2'])
                @include('partials.account.frd_account_setting_detail', ['no' => '19.3','title'=>'<span style="margin-left:20px"></span>'.'Donated Capital','code'=>'bs-19.3'])
                @include('partials.account.frd_account_setting_detail', ['no' => '19.4','title'=>'<span style="margin-left:20px"></span>'.'Hybrid Capital Instruments','code'=>'bs-19.4'])
                @include('partials.account.frd_account_setting_detail', ['no' => '19.5','title'=>'<span style="margin-left:20px"></span>'.'Reserves','code'=>'bs-19.5'])
                @include('partials.account.frd_account_setting_detail', ['no' => '19.6','title'=>'<span style="margin-left:20px"></span>'.'Retained Earnings','code'=>'bs-19.6'])
                @include('partials.account.frd_account_setting_detail', ['no' => '19.7','title'=>'<span style="margin-left:20px"></span>'.'Year to Date Undistributed Surplus','code'=>'bs-19.7'])


                <tr>
                    <td>

                    </td>
                    <td>Total Equity(19)</td>
                    <td></td>
                </tr>

                <tr>
                    <td>
                        <b>20</b>
                    </td>
                    <td><b>Total Liabilities and Total Equity</b></td>
                    <td></td>
                </tr>

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
                url: '{{url("api/acc-chart-frd")}}',
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
                url: '{{backpack_url('update-frd-profit-loss')}}',
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
