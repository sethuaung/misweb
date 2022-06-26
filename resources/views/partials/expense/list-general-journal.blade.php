
@extends('backpack::layout')
<?php $base=asset('vendor/adminlte');?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    {{--<link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">--}}
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <style>
        .pg-g ul{
            display: block !important;
        }
        .header-search{
            background: #fff;
            height: 100%;
            padding: 20px;
            margin: 10px 0 10px 0;
            border-radius: 8px;
        }
        .pagination{
            margin: 22px 9px!important;
        }
    </style>
@endsection

@section('content')
    <div class="header-search ">
    {{-- @php dd($_REQUEST) @endphp --}}
        <form method="GET" id="form-search">
            <div class="row">
                <div class="form-group col-lg-3">
                    <label>Start Date</label>
                    <input name="start_date" id="start_date" autocomplete="off" class="form-control" type="text" value="{{ isset($_REQUEST['start_date'])? $_REQUEST['start_date']:''}}">
                </div>
                <div class="form-group col-lg-3">
                    <label>To Date</label>
                    <input name="end_date" id="end_date" autocomplete="off" class="form-control" type="text" value="{{ isset($_REQUEST['end_date'])? $_REQUEST['end_date']:''}}">
                </div>
                <div class="form-group col-lg-3">
                    <label>Journal No</label>
                    <input name="reference_no" id="reference_no" class="form-control" type="text" value="{{ isset($_REQUEST['reference_no'])? $_REQUEST['reference_no']:''}}">
                </div>
                @if(companyReportPart() == 'company.mkt')
                <div class="form-group col-lg-3">
                    <label>Branch</label>
                    <select class="form-control js-branch-id" name="branch_id" id="branch_id">
                        <option value="0">-</option>
                        @if(isset($_REQUEST['branch_id']))
                            <?php

                           $branch_id = \App\Models\Branch::where('id',$_REQUEST['branch_id'])->first();
                            ?>
                            @if($branch_id != null)
                                <option selected="selected" value="{{$branch_id->id}}">{{$branch_id->title}}</option>
                            @endif
                        @endif
                    </select>
                </div>
                @endif
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
                    <label>FRD Account Code</label>
                    <select class="form-control js-frd_acc_code" name="frd_acc_code" id="frd_acc_code">
                        <option value="0">-</option>
                        @if(isset($_REQUEST['frd_acc_code']))
                            <?php
                            $frd_acc = \App\Models\AccountChartExternal::find($_REQUEST['frd_acc_code']);
                            ?>
                            @if($frd_acc != null)
                                <option selected="selected" value="{{$frd_acc->id}}">{{$frd_acc->external_acc_code}}</option>
                            @endif
                        @endif
                    </select>
                </div>
                <div class="col-lg-3">
                    <label>Account Code</label>
                    <select class="form-control js-acc_code" name="acc_code" id="acc_code">

                        <option value="0">-</option>
                        @if(isset($_REQUEST['acc_code']))
                            <?php
                            $acc = \App\Models\AccountChart::find($_REQUEST['acc_code']);
                            ?>
                            @if($acc != null)
                                <option selected="selected" value="{{$acc->id}}">{{$acc->code}}</option>
                            @endif
                        @endif
                    </select>
                </div>


            </div>

            <div class="row">
                <div class="col-lg-6">
                    <button type="button" class="btn btn-danger btn-clear"><span class="fa fa-eraser"></span> Clear</button>
                    <button type="submit" class="btn btn-primary btn-search"><span class="fa fa-search"></span> Search</button>
                    <button type="button" class="btn btn-success"
                            onclick="exportTableToExcel('table-data', 'Expense-{{date('d-m-Y')}}')"  >
                        <i class="fa fa-file-excel-o"></i> Excel
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div style="padding: 15px">
        <div class="row shadow" style="background: #fff;    padding-bottom: 20px!important;">
            <div class="" style="overflow-x: auto;border-radius: 8px">
                <div id="DivIdToPrint">

                    <table class="table table-bordered table-primary table-striped table-data"  id="table-data">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th style="min-width: 65px">Date</th>
                        <th style="white-space:nowrap;">Journal No</th>
                        <th style="white-space:nowrap;">Reference No</th>
                        @if(companyReportPart() == 'company.mkt')
                        <th style="white-space:nowrap;">Branch</th>
                        <th style="white-space:nowrap;">Login ID</th>
                        @endif
                        <th>ClientID</th>
                        <th>Name</th>
                        @if (CompanyReportPart() == "company.moeyan")
                        <th style="white-space:nowrap;">Branch</th>
                        @else
                        <th style="white-space:nowrap;">FRD Account Code</th>
                        @endif
                        <th style="white-space:nowrap;">Account Code</th>
                        <th style="white-space:nowrap;">Account Title and Description</th>
                        <th>Description</th>
                        <th>Debit</th>
                        <th>Credit</th>

                        @if(_can('create-journal-expense'))
                        <th style="text-align: center;"><a href="{{url('admin/expense/create')}}" class="btn btn-xs btn-primary" ><i class="fa fa-plus"></i> </a></th>
                        @endif
                    </tr>

                    <tbody class="show-list " >
                    @include('partials.expense.expense-list-search', ['g_journals' => $rows])
                    </tbody>
                </table>

                </div>
            </div>
            <div class="pg-g" style="z-index: 10000;">
                @if ($pag > 0)
                    {{$rows->links()}}
                @endif
            </div>



        </div>
    </div>

    <div class="row">
      <div class="col-md-12">
          <div class="dropdown dropup">
              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  @if ($pag == 0)
                      Show All
                  @else
                      Show {{session('expense_page')??10}}
                  @endif

                  <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                  <li><a href="{{backpack_url('update-expense-pag/10')}}" >10 </a></li>
                  <li><a href="{{backpack_url('update-expense-pag/100')}}">100</a></li>
                  <li><a href="{{backpack_url('update-expense-pag/250')}}">250</a></li>
                  <li><a href="{{backpack_url('update-expense-pag/500')}}">500</a></li>
                  {{--<li><a href="{{backpack_url('update-expense-pag/0')}}">All</a></li>--}}

              </ul>
              records per page
          </div>
      </div>
    </div>

@endsection

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
        .pg-g .pagination{
            display: block !important;
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

        $(function () {
            $('.approve_date').datepicker(
                { format: 'yyyy-mm-dd'}
            );
        });
    </script>

    <script>
        $(function () {
            // $('.select-province').select2();
            $('#check_all_group').on('change',function(event) {

                if(this.checked) { // check select status
                    $('.c-checked-group').each(function() {
                        this.checked = true;  //select all

                    });
                }else{
                    $('.c-checked-group').each(function() {
                        this.checked = false; //deselect all
                    });
                }
            });
            $('.c-checked-group').on('change',function () {

            });

        });
        $(function () {
            $('#start_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );

            $('#end_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );

            $('.date-pick').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
            $('.date-pick2').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
            $('.js-client-id').select2({
                theme: 'bootstrap',
                multiple: false,
                ajax: {
                    url: '{{url("api/get-client")}}',
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
            $('.js-frd_acc_code').select2({
                theme: 'bootstrap',
                multiple: false,
                ajax: {
                    url: '{{url("api/get-frd-acc-code")}}',
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
                                    text: item["external_acc_code"],
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
            $('.js-acc_code').select2({
                theme: 'bootstrap',
                multiple: false,
                ajax: {
                    url: '{{url("api/acc-chart")}}',
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
                                    text: item["code"],
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

            $('.btn-clear').on('click',function () {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#reference_no').val('');
                $('#client_id').val('');
                $('#branch_id').val('');
                $('#frd_acc_code').val('');
                $('#acc_code').val('');
                $('#form-search').submit();
            });
        });


        function exportTableToExcel(tableID, filename = ' '){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename?filename+'.xls':'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }



    </script>

@endsection
