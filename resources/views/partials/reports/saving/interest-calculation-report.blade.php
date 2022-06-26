@extends('backpack::layout')
<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\Saving;
use App\Models\SavingProduct;
use App\Models\SavingTransaction;
use Carbon\Carbon;

$base = asset('vendor/adminlte') ?>
@section('before_styles')

<style>
    .header-search {
        background: #fff;
        height: 100%;
        padding: 20px;
        margin: 10px 0 10px 0;
        border-radius: 8px;
    }

    .pagination {
        margin: 22px 9px !important;
    }

    #table-data thead th,
    #table-data tbody td {
        border: 1px solid #c7c7c7;
    }
</style>
@endsection

@section('content')
<div class="header-search">
    <form method="GET" id="form-search">
        <div class="row">
            <div class="form-group col-lg-3">
                <label>Start Date</label>
                <input name="start_date" id="start_date" autocomplete="off" class="form-control radius-all" type="text" value="{{ isset($_REQUEST['start_date'])? $_REQUEST['start_date']:''}}">
            </div>
            <div class="form-group col-lg-3">
                <label>To Date</label>
                <input name="end_date" id="end_date" autocomplete="off" class="form-control" type="text" value="{{ isset($_REQUEST['end_date'])? $_REQUEST['end_date']:''}}">
            </div>
            <div class="form-group col-lg-3">
                <label>Saving ID</label>
                <input name="saving_number" id="saving_number" class="form-control" type="text" value="{{ isset($_REQUEST['saving_number'])? $_REQUEST['saving_number']:''}}">
            </div>
            <div class="form-group col-lg-3">
                <label>Reference No</label>
                <input name="reference_no" id="reference_no" class="form-control" type="text" value="{{ isset($_REQUEST['reference_no'])? $_REQUEST['reference_no']:''}}">
            </div>
            <div class="form-group col-lg-3">
                <label>Client ID</label>
                <select class="form-control js-client-id" name="client_id" id="client_id">
                    <option value="0">-</option>
                    @if(isset($_REQUEST['client_id']))
                    <?php
                    $client_id = \App\Models\ClientU::find($_REQUEST['client_id']);
                    ?>
                    @if($client_id != null)
                    <option selected="selected" value="{{$client_id->id}}">{{$client_id->client_number}}-{{$client_id->name??$client_id->name_other}}</option>
                    @endif
                    @endif
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label>Branch</label>
                <select class="form-control js-branch-id" name="branch_id" id="branch_id">
                    <option value="0">-</option>
                    @if(isset($_REQUEST['branch_id']))
                    <?php
                    $branch = \App\Models\Branch::find($_REQUEST['branch_id']);
                    ?>
                    @if($branch != null)
                    <option selected="selected" value="{{$branch->id}}">{{$branch->title}}</option>
                    @endif
                    @endif
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label>Product Type</label>
                <select class="form-control js-product-id" name="saving_product" id="saving_product">
                    <option value="0">-</option>
                    @if(isset($_REQUEST['saving_product']))
                    <?php
                    $sav_product = \App\Models\SavingProduct::find($_REQUEST['saving_product']);
                    ?>
                    @if($sav_product != null)
                    <option selected="selected" value="{{$sav_product->id}}">{{$sav_product->name}}</option>
                    @endif
                    @endif
                </select>
            </div>
            <div class="form-group col-lg-6">

                <button type="button" class="btn btn-danger btn-clear"><span class="fa fa-eraser"></span> Clear</button>
                <button type="submit" class="btn btn-primary btn-search"><span class="fa fa-search"></span> Search</button>
                <button type="button" class="btn btn-success" onclick="exportTableToExcel('table-data', 'General-Journal-{{date('d-m-Y')}}')">
                    <i class="fa fa-file-excel-o"></i> Excel
                </button>
            </div>
        </div>
    </form>
    <?php
    $m = getSetting();
    $logo = getSettingKey('logo', $m);
    $company = getSettingKey('company-name', $m);
    ?>
</div>
<div style="padding: 15px">
    <div class="row shadow" style="background: #fff;padding-bottom: 20px!important;">
        <div class="row" style="margin-bottom: 20px !important;">
            <div class="col-md-12">
                @if(isset($_REQUEST['start_date']))
                <b>{{$_REQUEST['start_date']}}</b>
                @endif
                @if(isset($_REQUEST['end_date']))
                <b>{{$_REQUEST['end_date']}}</b>
                @endif
            </div>
        </div>
        <div style="position:relative;width:100%;">
        <div class="" style="overflow: auto;border-radius: 8px;height:700px;">
            <table class="table table-primary table-striped" id="table-data">
                <thead>
                    <tr>
                        <th rowspan="2" style="text-align:center">Date</th>
                        <th rowspan="2">Saving ID</th>
                        <th rowspan="2">Member ID</th>
                        <th rowspan="2">Member Name</th>
                        <th rowspan="2">Center</th>
                        <th>Deposit</th>
                        <th rowspan="2">Interest</th>
                        <th colspan="3" style="text-align:center">Withdrawal</th>
                        <th colspan="3" style="text-align:center">Balance</th>
                    </tr>
                    <tr>
                        <th>Principle</th>
                        <th style="text-align:center">Date</th>
                        <th>Principle</th>
                        <th>Interest</th>
                        <th>Principle</th>
                        <th>Interest</th>
                        <th>Total</th>
                    </tr>
                </thead>


                <tbody class="show-list">
                    @if($transactions != null)
                    <?php
                    $accrue = 0;
                    $principal = 0;
                    $sum_deposit = 0;
                    $sum_accrue = 0;
                    $sum_withdrawal_prin = 0;
                    $sum_withdrawal_int = 0;
                    $sum_principle = 0;
                    $sum_interest = 0;
                    $sum_total = 0;
                    ?>
                    @foreach($transactions as $tran)
                    <?php
                    $member = \App\Models\Client::find($tran->client_id);
                    $center = \App\Models\CenterLeader::find($tran->center_id);

                    if($tran->tran_type == "deposit" || $tran->tran_type == "compound-interest"){
                        $sum_deposit += $tran->amount ?? 0;
                        $principal += $tran->amount ?? 0;
                    }

                    if($tran->tran_type == "accrue-interest"){
                        $accrue += $tran->amount ?? 0;
                    }

                    if($tran->tran_type == "compound-interest"){
                        $accrue = 0;
                    }

                    if($tran->tran_type == "withdrawal"){
                        $sum_withdrawal_prin += $tran->amount ?? 0;
                        $principal += $tran->amount ?? 0;
                    }

                    $total = $principal + $accrue;

                    $sum_interest += $accrue;

                    $sum_principle += $principal;
                    

                    if($tran->tran_type == "accrue-interest"){
                        $sum_accrue += $tran->total_interest;
                    }
                    $sum_total += $total;
                    ?>
                    <tr>

                        <td style="white-space: nowrap;text-align: right">
                            @if($tran->tran_type != "withdrawal")
                            {{$tran->date}}
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            {{$tran->saving_number}}
                        </td>
                        <td style="white-space: nowrap;">
                            {{$member->client_number}}
                        </td>
                        <td style="white-space: nowrap;">
                            {{$member->name}}
                        </td>
                        <td style="white-space: nowrap;">
                            {{optional($center)->code}}
                        </td>

                        @if($tran->tran_type == "deposit")
                        <td style="text-align: right">
                            {{$tran->amount>0?numb_format($tran->amount,2):''}}
                        </td>
                        @elseif($tran->tran_type == "compound-interest")
                        <td style="text-align: right">
                            {{$tran->amount>0?numb_format($tran->amount,2):''}}
                        </td>
                        @else
                        <td style="text-align: right">
                        </td>
                        @endif

                        <td style="text-align: right">
                        @if($tran->tran_type == "accrue-interest")
                            {{$tran->amount>0?numb_format($tran->amount,2):''}}
                        @endif
                        </td>

                        @if($tran->tran_type == "withdrawal")
                        <td style="white-space: nowrap;">
                            {{$tran->date}}
                        </td>
                        <td style="text-align: right">
                            {{numb_format(abs($tran->amount),2)}}
                        </td>
                        <td></td>
                        @else
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif

                        <td style="text-align: right">
                            {{$principal>0?numb_format($principal,2):''}}
                        </td>
                        <td style="text-align: right">
                            @if($tran->tran_type != "compound-interest")
                                {{$accrue>0?numb_format($accrue,2):''}}
                            @endif
                        </td>
                        <td style="text-align: right">
                            {{$total>0?numb_format($total,2):''}}
                        </td>
                    </tr>

                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align: center"><b>Grand Total</b></td>
                        <td style="text-align: right">{{numb_format($sum_deposit,2)}}</td>
                        <td style="text-align: right">{{numb_format($sum_accrue,2)}}</td>
                        <td></td>
                        <td style="text-align: right">{{numb_format(abs($sum_withdrawal_prin),2)}}</td>
                        <td style="text-align: right">{{numb_format($sum_withdrawal_int,2)}}</td>
                        <td style="text-align: right">{{numb_format($sum_principle,2)}}</td>
                        <td style="text-align: right">{{numb_format($sum_interest,2)}}</td>
                        <td style="text-align: right">{{numb_format($sum_total,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: center"><b>Total</b></td>
                        <td style="text-align: right">{{numb_format($totals['deposit'] + $totals['compound-interest'],2)}}</td>
                        <td style="text-align: right">{{numb_format($totals['accrue-interest'],2)}}</td>
                        <td></td>
                        <td style="text-align: right">{{numb_format(abs($totals['withdrawal']),2)}}</td>
                        <td style="text-align: right">{{numb_format($sum_withdrawal_int,2)}}</td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <?php
    $transactions->appends(request()->query());
    ?>
    {{ $transactions->links() }}
    <!-- <form id="form-page" class="form-inline" method="GET" action="{{url()->current()}}">
            <div class="form-group">
                <label for="perPage">Example select: </label>
                <select class="form-control" id="perPage" name="perPage">
                    @if(isset($_REQUEST['perPage']))
                    <option value="{{$_REQUEST['perPage']}}">{{$_REQUEST['perPage']}}</option>
                    @endif
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                </select>
            </div>
        </form> -->
</div>

@endsection

@section('after_scripts')

{{--<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{$base}}/bower_components/select2/dist/js/select2.min.js"></script>--}}
<script src="{{ asset('vendor/adminlte/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>

<script>
    $(function() {
        $('.approve_date').datepicker({
            format: 'yyyy-mm-dd'
        });
    });
</script>

<script>
    $(function() {
        $(".pagination").css('display:inline-block');
        // $('.select-province').select2();
        $('#check_all_group').on('change', function(event) {

            if (this.checked) { // check select status
                $('.c-checked-group').each(function() {
                    this.checked = true; //select all

                });
            } else {
                $('.c-checked-group').each(function() {
                    this.checked = false; //deselect all
                });
            }
        });
        $('.c-checked-group').on('change', function() {

        });

    });
    $(function() {
        // $('#perPage').change(function() {
        //     var form = $("#form-page").serialize();
        //     var string =  window.location.href.split('&perPage')[0];
        //     string =  string.split('?perPage')[0];
        //     var url = '';
        //     if (string.indexOf('?') == -1) {
        //         url = string+ '&' + form;
        //     }else{
        //         url = string+ '?' + form;
        //     }
        //     window.location.replace(url);
        // })

        $('#start_date').datepicker({
            format: 'yyyy-mm-dd',
        });

        $('#end_date').datepicker({
            format: 'yyyy-mm-dd',
        });

        $('.date-pick').datepicker({
            format: 'yyyy-mm-dd',
        });
        $('.date-pick2').datepicker({
            format: 'yyyy-mm-dd',
        });
        $('.js-client-id').select2({
            theme: 'bootstrap',
            multiple: false,
            ajax: {
                url: '{{url("api/get-client")}}',
                dataType: 'json',
                quietMillis: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page, // pagination
                    };
                },
                processResults: function(data, params) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    params.page = params.page || 1;
                    var result = {
                        results: $.map(data.data, function(item) {

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
                }
            }
        });

        $('.js-branch-id').select2({
            theme: 'bootstrap',
            multiple: false,
            ajax: {
                url: '{{url("api/get-branch")}}',
                dataType: 'json',
                quietMillis: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page, // pagination
                    };
                },
                processResults: function(data, params) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    params.page = params.page || 1;
                    var result = {
                        results: $.map(data.data, function(item) {

                            return {
                                text: item["title"],
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
        $('.js-product-id').select2({
            theme: 'bootstrap',
            multiple: false,
            ajax: {
                url: '{{url("api/get-saving-product")}}',
                dataType: 'json',
                quietMillis: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page, // pagination
                    };
                },
                processResults: function(data, params) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'
                    params.page = params.page || 1;
                    var result = {
                        results: $.map(data.data, function(item) {

                            return {
                                text: item["name"],
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

        $('.btn-clear').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#saving_number').val('');
            $('#client_id').val('');
            $('#reference_no').val('');
            $('#saving_product').val('');
            $('#branch_id').val('');
            $('#form-search').submit();
        });
    });


    function exportTableToExcel(tableID, filename = ' ') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }

    function Excel(tableID, filename = '') {
        $.ajax({
            url: "{{route('excel-confirm')}}",
            method: "get",
            data: {

            },
            success: function(data) {
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var ref_no = $('#saving_number').val();
                var client_id = $('#client_id').val();
                var frd_acc_code = $('#frd_acc_code').val();
                var acc_code = $('#acc_code').val();
                var branch_id = $('#branch_id').val();

                var url = "<?php echo route('print-excel') ?>";

                var download_url = url + '?branch_id=' + branch_id + '&acc_code=' + acc_code + '&frd_acc_code=' + frd_acc_code + '&client_id=' + client_id + '&ref_no=' + ref_no + '&start_date=' + start_date + '&end_date=' + end_date;

                window.open(download_url);
            },
        });
    }
</script>



@endsection