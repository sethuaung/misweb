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
</style>
@endsection

@section('content')
<div class="header-search">
    <form method="GET" id="form-search">
        <div class="row">
            <input type="hidden" name="type" value="customer">
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
    $client = null;
    $product = null;
    $branch = null;
    $saving = null;
    $ref_arr = [];
    foreach ($journals as $journal) {
        array_push($ref_arr, $journal->saving_id);
    }
    $ref_arr = array_unique($ref_arr);

    if (count($ref_arr) == 1) {
        $saving = Saving::find($ref_arr[0]);
    }

    if ($saving) { // if search have only one saving
        $client = Client::find(optional($saving)->client_id);
        $product = SavingProduct::find(optional($saving)->saving_product_id);
        $branch = Branch::find(optional($saving)->branch_id);
    } else {
        if (isset($_REQUEST['client_id'])) {
            if ($_REQUEST['client_id']) {
                $client = Client::find($_REQUEST['client_id']);
            }
        }
        if (isset($_REQUEST['saving_product'])) {
            if ($_REQUEST['saving_product']) {
                $product = SavingProduct::find($_REQUEST['saving_product']);
            }
        }
        if (isset($_REQUEST['branch_id'])) {
            if ($_REQUEST['branch_id']) {
                $branch = Branch::find($_REQUEST['branch_id']);
            }
        }
    }
    ?>
</div>
<div style="padding: 15px">
    <div class="row shadow" style="background: #fff;padding-bottom: 20px!important;overflow-x:scroll;">
        <div class="row text-center">
            <div class="col-md-12" style="float:left">
                <img src="{{asset($logo)}}" width="200" />
            </div>
        </div>
        <div class="row text-center" style="border: 1px solid black;background: #fff;margin: 20px 0 !important;">
            <div class="col-md-12" style="float:left">
                Customer -
                @if($client)
                <b>{{optional($client)->name}}</b>
                @endif
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px !important;">
            <div class="col-md-offset-1 col-md-2">
                ID Code -
            </div>
            <div class="col-md-3">
                @if($client)
                <b>{{optional($client)->client_number}}</b>
                @else
                -
                @endif
            </div>

            <div class="col-md-2">
                Product Type -
            </div>
            <div class="col-md-2">
                @if($product)
                <b>{{optional($product)->name}}</b>
                @else
                -
                @endif

            </div>

            <div class="col-md-offset-1 col-md-2">
                Interest Rate -
            </div>
            <div class="col-md-3">
                @if($saving)
                <b>{{optional($saving)->interest_rate}}%</b>
                @else
                -
                @endif
            </div>

            <div class="col-md-2">
                Branch -
            </div>
            <div class="col-md-2">
                @if($branch)
                <b>{{optional($branch)->title}}</b>
                @else
                -
                @endif
            </div>

            <div class="col-md-offset-1 col-md-2">
                Saving No -
            </div>
            <div class="col-md-3">
                @if($saving)
                <b>{{optional($saving)->saving_number}}</b>
                @else
                -
                @endif
            </div>

            <div class="col-md-2">
                Date -
            </div>
            <div class="col-md-2">
                @if(isset($_REQUEST['start_date']))
                <b>{{$_REQUEST['start_date']}}</b>
                @endif
                @if(isset($_REQUEST['end_date']))
                <b>{{$_REQUEST['end_date']}}</b>
                @endif
            </div>
        </div>
        <div class="" style="overflow-x: auto;border-radius: 8px; border:1px solid black;">

            <table class="table table-bordered table-primary table-striped" id="table-data">
                <thead>
                    <tr>
                        <th style="white-space:nowrap;">No</th>
                        <th style="white-space:nowrap;">Reference No</th>
                        <th style="white-space:nowrap;">Cash Acc</th>
                        <th style="min-width: 65px">Date</th>
                        <th style="white-space:nowrap;">Type</th>
                        <th>Debit Amount</th>
                        <th>Credit Amount</th>
                        <th>Balance</th>
                        <th>Ref Int:</th>
                        <th>Interest</th>
                    </tr>
                </thead>


                <tbody class="show-list">
                    @if($journals != null)
                    <tr>
                        <td colspan="5" style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            <b>Beginning Account Balance</b>
                        </td>
                        <td style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            -
                        </td>
                        <td style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            -
                        </td>
                        <td style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            -
                        </td>
                    </tr>
                    <?php
                    $balance = 0;
                    $cr_total = 0;
                    $dr_total = 0;
                    ?>
                    @foreach($journals as $key => $g_d)
                    <?php
                    if($g_d->tran_type == "saving-withdrawal"){
                        $saving_with = \App\Models\SavingWithdrawal::find($g_d->tran_id);
                        $acc_chart = \App\Models\AccountChart::find($saving_with->cash_out_id);
                    }else{
                        $acc_chart = \App\Models\AccountChart::find($g_d->acc_chart_id);
                    }
                    // if ($g_d->tran_type == "saving-withdrawal") {
                    //     $balance -= $g_d->dr ?? 0;
                    //     $cr_total += $g_d->dr ?? 0;
                    // } else {
                    //     $balance += $g_d->dr ?? 0;
                    //     $dr_total += $g_d->dr ?? 0;
                    // }
                    ?>
                    <tr>
                        <td style="white-space: nowrap;text-transform: capitalize">
                            {{++$key}}
                        </td>
                        <td style="text-transform: capitalize">
                            {{$g_d->reference_no}}
                        </td>
                        <td style="white-space: nowrap;">
                            @if($acc_chart && $g_d->tran_type != "compound-interest")
                            {{$acc_chart->name}}
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            {{$g_d->j_detail_date}}
                        </td>
                        <td style="white-space: nowrap;">
                            {!! $g_d->description !!}
                        </td>
                        @if($g_d->tran_type == "saving-withdrawal")
                        <td style="text-align: right">{{$g_d->cr>0?numb_format($g_d->cr,0):''}}</td>
                        <td style="text-align: right">{{$g_d->dr>0?numb_format($g_d->dr,0):''}}</td>
                        @else
                        <td style="text-align: right">{{$g_d->dr>0?numb_format($g_d->dr,0):''}}</td>
                        <td style="text-align: right">{{$g_d->cr>0?numb_format($g_d->cr,0):''}}</td>
                        @endif
                        <td style="text-align: right">{{numb_format($g_d->total_principal??0, 2)}}</td>

                        <?php
                        $transaction = SavingTransaction::whereMonth('date', Carbon::parse($g_d->j_detail_date))
                            ->whereNotIn('tran_type', ['accrue-interest'])
                            ->where('saving_id', $g_d->saving_id)
                        ->orderBy('date', 'desc')->first();
                        //dd($transaction);
                        $accrue_int = null;
                        if (optional($transaction)->tran_id == optional($g_d)->tran_id) {
                            $accrue_int = SavingTransaction::whereMonth('date', Carbon::parse($g_d->j_detail_date))
                                ->where('tran_type', 'accrue-interest')
                                ->where('saving_id', $g_d->saving_id)
                                ->first();
                        }
                        // dd($accrue_int);
                        ?>
                        @if(isset($accrue_int))
                        <td>{{$accrue_int->reference}}</td>
                        <td style="text-align: right">{{numb_format($accrue_int->amount,0)}}</td>
                        @endif
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            <b>Ending Account Balance</b>
                        </td>
                        <td style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            {{numb_format($dr_total,0)}}
                        </td>
                        <td style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            {{numb_format($cr_total,0)}}
                        </td>
                        <td style="white-space: nowrap;text-align:right;text-transform: capitalize">
                            <b>{{numb_format($g_d->total_principal??0,0)}}</b>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <?php
    $journals->appends(request()->query());
    ?>
    {{ $journals->links() }}
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

@push('crud_fields_styles')
<style>
    .table-data {
        width: 100%;
    }

    .table-data,
    .table-data th,
    .table-data td {
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

    .table-data tbody>tr:nth-child(odd) {
        background-color: #f4f4f4;
        color: #606060;
    }

    .pagination {
        display: inline-block !important;
    }
</style>

@endpush


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