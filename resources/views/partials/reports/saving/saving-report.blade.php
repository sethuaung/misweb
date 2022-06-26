@extends('backpack::layout')
<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\Saving;
use App\Models\SavingProduct;
use Illuminate\Http\Request;

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
            <input type="hidden" name="type" value="normal">
            <div class="form-group col-lg-3">
                <label>Start Date</label>
                <input name="start_date" id="start_date" autocomplete="off" class="form-control radius-all" type="text" value="{{ isset($_REQUEST['start_date'])? $_REQUEST['start_date']:''}}">
            </div>
            <div class="form-group col-lg-3">
                <label>To Date</label>
                <input name="end_date" id="end_date" autocomplete="off" class="form-control" type="text" value="{{ isset($_REQUEST['end_date'])? $_REQUEST['end_date']:''}}">
            </div>
            <div class="form-group col-lg-3">
                <label>Saving No</label>
                <input name="saving_number" id="saving_number" class="form-control" type="text" value="{{ isset($_REQUEST['saving_number'])? $_REQUEST['saving_number']:''}}">
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
</div>
<div style="padding: 15px">
    <div class="row shadow" style="background: #fff;padding-bottom: 20px!important;">
        <div class="" style="overflow-x: auto;border-radius: 8px">

            <table class="table table-bordered table-primary table-striped" id="table-data">
                <thead>
                    <tr>
                        <th rowspan="2">Sr.no.</th>
                        <th rowspan="2" style="min-width: 65px;text-align: center;">Member ID</th>
                        <th rowspan="2">Member Name</th>
                        <th rowspan="2">Center</th>
                        <th colspan="3" style="text-align: center;">Voluntory Saving</th>
                    </tr>
                    <tr>
                        <th>Principle</th>
                        <th>Interest</th>
                        <th>Total</th>
                    </tr>
                </thead>


                <tbody class="show-list">
                    @if($savings != null)
                    <?php
                    $sum_principal =0;
                    $sum_interest =0;
                    $sum_total =0;
                    $start_date = isset($_REQUEST['start_date'])? $_REQUEST['start_date']: null;
                    $end_date = isset($_REQUEST['end_date'])? $_REQUEST['end_date']: null;
                    ?>
                    @foreach($savings as $saving)
                    <?php
                    $principal = 0;
                    $interest = 0;
                    if($saving->duration_interest_calculate == 'Daily'){
                        $transactions = \App\Models\SavingTransaction::where('saving_id', $saving->id)
                                    ->where(function ($query) use ($start_date, $end_date) {
                                        if ($start_date != null && $end_date == null) {
                                            return $query->whereDate('date', '<=', $start_date);
                                        } else if ($start_date != null && $end_date != null) {
                                            return $query->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date);
                                        }
                                    })->orderBy('date','asc')->get();
                        $no = 1;
                        foreach($transactions as $tran){
                            if($tran->tran_type == "deposit" || $tran->tran_type == "withdrawal"){
                                $principal += $tran->amount;
                            }
                            if($no < count($transactions)){
                                $total_principal = $tran->tran_type == "withdrawal"?$tran->total_principal:$tran->amount; 
                                $interest_rate = $saving->interest_rate / 100;

                                $saving_interest = ($total_principal * $interest_rate) / 365;
                                $date_cal = strtotime($transactions[$no]['date']) - strtotime($tran->date);
                                $n = $date_cal/60/60/24;
                                $saving_interest_amt = $saving_interest * $n;
                                $interest += $saving_interest_amt;
                                $no = $no + 1;
                            }
                            else if($no == count($transactions)){
                                $total_principal = $tran->tran_type == "withdrawal"?$tran->total_principal:$tran->amount;
                                $interest_rate = $saving->interest_rate / 100;

                                $saving_interest = ($total_principal * $interest_rate) / 365;
                                $date_cal =strtotime($end_date) - strtotime($tran->date);
                                $n = $date_cal/60/60/24;
                                $saving_interest_amt = $saving_interest * $n;
                                $interest += $saving_interest_amt;
                            }
                        }
                    }
                    else{
                        $transactions = \App\Models\SavingTransaction::where('saving_id', $saving->id)
                                    ->where(function ($query) use ($start_date, $end_date) {
                                        if ($start_date != null && $end_date == null) {
                                            return $query->whereDate('date', '<=', $start_date);
                                        } else if ($start_date != null && $end_date != null) {
                                            return $query->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date);
                                        }
                                    })->get();
                        foreach($transactions as $tran){
                            if($tran->tran_type == "deposit" || $tran->tran_type == "compound-interest" || $tran->tran_type == "withdrawal"){
                                $principal += $tran->amount;
                            }
                            
                            if($tran->tran_type == "accrue-interest" || $tran->tran_type == "compound-interest"){
                                if($tran->tran_type == "accrue-interest"){
                                    $interest += $tran->amount;
                                }else{
                                    $interest -= $tran->amount;
                                }
                            }
                        }
                    }
                    $sum_principal += $principal;
                    $sum_interest += $interest;
                    $total = $principal + $interest;
                    $sum_total += $total;
                    $client = \App\Models\Client::find($saving->client_id);
                    $center = \App\Models\CenterLeader::find($saving->center_id);
                    ?>
                    <tr>
                        <td style="white-space: nowrap;text-transform: capitalize">
                            {{$saving->saving_number}}
                        </td>
                        <td style="text-align: center;">
                            {{$client->client_number}}
                        </td>
                        <td style="text-align: center;">
                            {{$client->name}}
                        </td>
                        <td style="white-space: nowrap;">
                            {{optional($center)->code}}
                        </td>
                        <td style="text-align: right">{{number_format($principal, 2)}}</td>
                        <td style="text-align: right">{{number_format($interest, 2)}}</td>
                        <td style="text-align: right">{{number_format($total, 2)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"></td>
                        <td style="text-align: right">{{number_format($sum_principal, 2)}}</td>
                        <td style="text-align: right">{{number_format($sum_interest, 2)}}</td>
                        <td style="text-align: right">{{number_format($sum_total, 2)}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <?php
        $savings->appends(request()->query());
        ?>
        {{ $savings->links() }}
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
            $('#saving_number').val('');
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