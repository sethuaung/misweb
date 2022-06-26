

@extends('backpack::layout')
<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <style>
        .pg-g ul{
            display: block !important;
        }
        .header-search{
            background: #fff;
            height: 100px;
            padding: 20px;
            margin: 10px 0 10px 0;
        }
    </style>

@endsection

@section('content')
    <div class="header-search">
        <div class="row">
            <div class="col-lg-2">
                <label>Date</label>

                <input name="start_date" id="start_date" autocomplete="off" class="form-control" type="text" value="{{ isset($_REQUEST['start_date'])? $_REQUEST['start_date']:''}}">
            </div>
            {{--<div class="col-lg-2">
                <label> To </label>
                <input name="end_date" id="end_date" autocomplete="off" class="form-control" type="text">
            </div>--}}
            <div class="col-lg-2">
                <label>Journal No</label>
                <input name="reference_no" id="reference_no" class="form-control" type="text" value="{{ isset($_REQUEST['reference_no'])? $_REQUEST['reference_no']:''}}">
            </div>
            <div class="col-lg-2">
                <label>ClientID</label>
                <select class="form-control js-client-id" name="client_id" id="client_id">
                    <option value="0">-</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label>FRD Account Code</label>
                <select class="form-control js-frd_acc_code" name="frd_acc_code" id="frd_acc_code">
                    <option value="0">-</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label>Account Code</label>
                <select class="form-control js-acc_code" name="acc_code" id="acc_code">
                    <option value="0">-</option>
                </select>
            </div>

            <div class="col-lg-2">
                <label>&nbsp;</label><br>
                <button type="button" class="btn btn-danger btn-clear">Clear</button>
                <button type="submit" class="btn btn-primary btn-search">Search</button>
            </div>
        </div>
    </div>
    <div style="background: #fff; padding: 20px; overflow-x:auto;">
        <table class="table table-bordered table-default">
            <thead style="background: #eeeeee">
            <tr>
                <th>Type</th>
                <th style="min-width: 65px">Date</th>
                <th style="white-space:nowrap;">Journal No</th>
                <th style="white-space:nowrap;">Reference No</th>
                <th>ClientID</th>
                <th>Name</th>
                <th style="white-space:nowrap;">FRD Account Code</th>
                <th style="white-space:nowrap;">Account Code</th>
                <th style="white-space:nowrap;">Account Title and Description</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>


                <th style="text-align: center;"><a href="{{url('admin/general-journal/create')}}" class="btn btn-xs btn-primary" ><i class="fa fa-plus"></i> </a></th>
            </tr>
            </thead>
            <tbody id="journal-list">
            @if($g_journals != null)
                <?php

                $total_dr = 0;
                $total_cr = 0;
                ?>
                @foreach($g_journals as $g)

                    <?php
                    $g_detail = \App\Models\GeneralJournalDetail::where('journal_id',$g->id)->get();
                    $t_dr = 0;
                    $t_cr = 0;
                    ?>
                    @if($g_detail != null)
                        @foreach($g_detail as $g_d)
                            <?php

                            $ref = '';
                            $type = $g_d->tran_type;
                            $cus = optional(\App\Models\Customer::find($g_d->name));
                            if($type == 'loan-deposit'){
                                $deposit = \App\Models\LoanDeposit::find($g_d->tran_id);
                                $ref = optional($deposit)->referent_no;
                            }
                            if($type == 'loan-disbursement'){
                                $deposit = \App\Models\PaidDisbursement::find($g_d->tran_id);
                                $ref = optional($deposit)->reference;
                            }
                            if($type == 'payment'){
                                $deposit = \App\Models\LoanPayment::find($g_d->tran_id);
                                $ref = optional($deposit)->payment_number;
                            }
                            ?>
                            <tr>
                                <td style="white-space:nowrap;">
                                    @if($loop->first)
                                        {{$g_d->tran_type}}
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">
                                    @if($loop->first)
                                        {{$g->date_general->format('Y-m-d')}}
                                    @endif
                                </td>
                                <td style="text-align: center; white-space:nowrap;">
                                    @if($loop->first)
                                        {{$g->reference_no}}
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">@if($loop->first)
                                        {{$g->tran_reference}}
                                    @endif</td>
                                <td style="white-space:nowrap;">
                                    @if($loop->first)
                                        {{optional($cus)->client_number}}
                                    @endif
                                </td>
                                <td style="white-space:nowrap;">
                                    @if($loop->first)
                                        {{optional($cus)->name}}
                                    @endif
                                </td>
                                <td>{{$g_d->external_acc_chart_code}}</td>
                                <td>{{optional($g_d->acc_chart)->code}}</td>
                                <td>{{optional($g_d->acc_chart)->name}}</td>
                                <td>{!! $g_d->description !!}</td>
                                <td style="text-align: right">{{$g_d->dr>0?numb_format($g_d->dr,2):''}}</td>
                                <td style="text-align: right">{{$g_d->cr>0?numb_format($g_d->cr,2):''}}</td>
                                <td style="text-align: center; white-space: nowrap; width: 80px;">
                                    @if($loop->first)
                                        @if($g_d->tran_type == 'journal')
                                            <a href="{{url('admin/general-journal/'.$g->id.'/edit')}}" class="btn btn-xs btn-warning" ><i class="fa fa-edit"></i> </a>

                                            <a href="{{url('admin/delete-journal/'.$g->id)}}" class="btn btn-xs btn-danger" ><i class="fa fa-trash"></i> </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <?php
                            $t_dr += $g_d->dr;
                            $t_cr += $g_d->cr;
                            ?>
                        @endforeach
                        <?php
                        $total_dr += $t_dr;
                        $total_cr += $t_cr;
                        ?>
                    @endif
                @endforeach
                <tr style="background: #eeeeee">
                    <td colspan="10"><b>Total</b></td>
                    <td style="text-align: right"><b>{{$total_dr>0?numb_format($total_dr,2):''}}</b></td>
                    <td style="text-align: right"><b>{{$total_cr>0?numb_format($total_cr,2):''}}</b></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
            </tbody>

        </table>
        <div class="pg-g" style="z-index: 10000; display: block !important;">
            {{$g_journals->links()}}
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
    </style>
@endpush

@section('after_scripts')


    {{--<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="{{$base}}/bower_components/select2/dist/js/select2.min.js"></script>--}}
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $("#show-detail-modal-group").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            //alert(link.attr("href"));
            // $(this).find(".modal-body").load(link.attr("href"));
            $('#iframe').prop('src',link.attr("href"));
        });
        $(function () {
            $('.approve_date').datepicker(
                { format: 'yyyy-mm-dd'}
            );

            /*$("#show-detail-modal-group").on("show.bs.modal", function(e) {
                alert('ok');
                var link = $(e.relatedTarget);
                $(this).find(".modal-body").load(link.attr("href"));
            });*/
            //alert('ok');
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
            $('.approve_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
            $('#start_date').datepicker({
                    format: 'yyyy-mm-dd',
                    changeMonth: true,
                    changeMonth: true,
                    changeYear: true,
                }
            );
            $('#end_date').datepicker({
                    format: 'yyyy-mm-dd',
                    changeMonth: true,
                    changeMonth: true,
                    changeYear: true,

                }
            );
            $('.btn-search').on('click',function () {
                var start_date = $('#start_date').val();
                //var end_date = $('#start_date').val();
                var reference_no = $('#reference_no').val();
                var client_id = $('#client_id').val();
                var frd_acc_code = $('#frd_acc_code').val();
                var acc_code = $('#acc_code').val();
                if(start_date != '' || reference_no != '' || client_id != '' || frd_acc_code != '' || acc_code != '') {
                    $.ajax({
                        type: 'GET',
                        url: '{{url('api/search-general-journal')}}',
                        data: {
                            start_date: start_date,
                            reference_no: reference_no,
                            client_id: client_id,
                            frd_acc_code: frd_acc_code,
                            acc_code: acc_code
                        },
                        success: function (res) {
                            $('#journal-list').html(res);
                        }
                    });
                }
            });

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
        });

    </script>



@endsection




