@extends('backpack::layout')
<?php $base=asset('vendor/adminlte') ?>
@section('before_styles')

    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

@endsection

@section('content')
    <div class="container-fluid">
        <div class="form-group col-md-4">
            <select class="form-control select2_field-center" data-source="{{url("api/get-center-leader-name-id")}}" multiple name="center_id[]"  >
            </select>
        </div>
        <div class="form-group col-md-4">
            <select class="form-control select2_field-group" data-source="{{url("api/get-group-loan2")}}" multiple name="group_id[]" >
            </select>
        </div>
        <div class="form-group col-md-4">
            <select class="form-control select2_field-loan-product" data-source="{{url("api/get-loan-product")}}" multiple name="loan_product[]" >
            </select>
        </div>
       {{-- <div class="form-group col-md-4">
            <input type="text" class="form-control pull-right" id="reservation">
        </div>--}}
            <div class="form-group col-md-4">

            </div>

        <a hidden href="javascript:void(0)" id="remove-filter"><i class="fa fa-eraser"></i> Remove Filters</a>

    </div>
    <form method="post" action="{{url('admin/group-repayment')}}" >
        {!! csrf_field() !!}
    <div style="background: #fff; padding: 10px;">
        <div class="row">
            <div class="col-sm-12">
                <div class="b-group">
                   <table class="table table-bordered" style="width: 100%;background-color: white">
                    <thead>
                    <tr>
                        <th>CenterID-Name</th>
                        <th>Group ID</th>
                        <th>Group Name</th>
                        <th>Payment Date</th>
                        <th>Term No</th>
                        <th>Loan Product</th>
                        {{--<th>Loan Repayment</th>--}}
                        <th>Compulsory</th>
                        <th>Principle</th>
                        <th>Interest</th>
                        <th>Total Payment</th>
                        <th><input type="checkbox" id="check_all_group"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $t_loan_repayment = 0;
                        $t_other_payment = 0;
                        $t_payment = 0;
                        $t_principle=0;
                        $t_interest=0;
                    ?>

                    @if($g_pending != null)
                        @php
                            $group_mem = $g_pending->groupBy('group_loan_id');

                        @endphp
                        @foreach($group_mem as $g_id => $rows)
                            <?php

                            $group = \App\Models\GroupLoan::find($g_id);
                            $rand_id = rand(1,1000).time().rand(1,1000);
                            $center = null;
                            if($group != null){
                                $center = \App\Models\CenterLeader::find($group->center_id);
                            }

                            //$total += $row->amount;

                            $t_line_loan_repayment = 0;
                            $t_line_other_payment = 0;
                            $t_line_payment = 0;
                            $t_line_compulsory = 0;
                            $t_line_charge =0;
                            $pay_date = '';
                            $pay_term = '';
                            $loan_product= '';
                            $principle2=0;
                            $interest2=0;

                            foreach ($rows as $row){

                                $line_loan_repayment = 0;
                                $line_other_payment = 0;
                                $line_payment = 0;
                                $line_compulsory = 0;
                                $line_charge =0;
                                $principle=0;
                                $interest=0;

                                //============ compulsory saving==================
                                $compulsory = \App\Models\LoanCompulsory::where('loan_id',$row->id)->first();

                                if($compulsory != null){

                                    if($compulsory->compulsory_product_type_id == 3){

                                        if($compulsory->charge_option == 1){
                                            $line_compulsory = $compulsory->saving_amount;
                                        }elseif($compulsory->charge_option == 2){
                                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                                        }
                                    }
                                    if(($compulsory->compulsory_product_type_id == 4) && ($last_no%2==0)){

                                        if($compulsory->charge_option == 1){
                                            $line_compulsory = $compulsory->saving_amount;
                                        }elseif($compulsory->charge_option == 2){
                                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                                        }
                                    }
                                    if($compulsory->compulsory_product_type_id == 5 && ($last_no%3==0)){
                                        if($compulsory->charge_option == 1){
                                            $line_compulsory = $compulsory->saving_amount;
                                        }elseif($compulsory->charge_option == 2){
                                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                                        }
                                    }
                                    if($compulsory->compulsory_product_type_id == 6 && ($last_no%6==0)){
                                        if($compulsory->charge_option == 1){
                                            $line_compulsory = $compulsory->saving_amount;
                                        }elseif($compulsory->charge_option == 2){
                                            $line_compulsory = ($compulsory->saving_amount*$row->loan_amount)/100;
                                        }
                                    }

                                }
                                //==============Total Repayment=========================

                                $loan_d = \App\Models\LoanCalculate::where('disbursement_id',$row->id)
                                    ->whereRaw('DATE(date_s) = DATE(NOW())')->where('payment_status','pending')
                                    ->whereNull('date_p')->orderBy('date_s','asc')->where('total_p','=',0)->first();
                                $last_no = 0;
                                $pay_date = \Carbon\Carbon::parse(optional($loan_d)->date_s)->format('d-m-Y');
                                $pay_term = optional($loan_d)->no;

                                if($loan_d != null){
                                    $principal_s = $loan_d->principal_s??0;
                                    $interest_s = $loan_d->interest_s??0;
                                    $penalty_s = $loan_d->penalty_s??0;

                                    $line_loan_repayment = $principal_s + $interest_s + $penalty_s;

                                    if($line_loan_repayment >0){
                                        $t_line_loan_repayment += $line_loan_repayment;
                                    }
                                    $last_no = $loan_d->no;
                                }

                                //============Service Charge==================
                                $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('status','Yes')->where('loan_id',$row->id)->get();
                                if($charges != null){
                                    foreach ($charges as $c){
                                        $amt_charge = $c->amount;
                                        $line_charge += ($c->charge_option == 1?$amt_charge:(($row->loan_amount*$amt_charge)/100));
                                    }
                                }

                                if($line_compulsory>0){
                                    $t_line_compulsory += $line_compulsory;
                                }

                                if($line_charge >0){
                                    $t_line_charge += $line_charge;
                                }
                                $t_line_other_payment = $t_line_compulsory + $t_line_charge;
                                $t_line_payment = $t_line_loan_repayment + $t_line_other_payment;

                                $l_product=\App\Models\LoanProduct::find($row->loan_production_id);
                                $loan_product=optional($l_product)->name;

                                //find principle and interest
                                $principle+=\App\Models\LoanCalculate::where('disbursement_id',$row->id)
                                    ->whereDate('date_s','=',date('Y-m-d'))
                                    ->where('payment_status','!=','paid')
                                    ->sum('principal_s');

                                $interest+=\App\Models\LoanCalculate::where('disbursement_id',$row->id)
                                    ->whereDate('date_s','=',date('Y-m-d'))
                                    ->where('payment_status','!=','paid')
                                        ->sum('interest_s');

                                $principle2+=$principle;
                                $interest2+=$interest;

                            }

                            $t_loan_repayment += $t_line_loan_repayment;
                            $t_other_payment += $t_line_other_payment;
                            $t_payment += $t_line_payment;

                            $t_principle+=$principle2;
                            $t_interest+=$interest2;

                            ?>

                            <tr id="p-{{$rand_id}}">
                                <td>{{optional($center)->code}}-{{optional($center)->title}}</td>
                                <td>{{optional($group)->group_code}}</td>
                                <td>{{optional($group)->group_name}}</td>
                                <td>{{$pay_date}}</td>
                                <td>{{$pay_term}}</td>
                                <td>{{$loan_product}}</td>
{{--                            <td>{{ $t_line_loan_repayment }}</td>--}}
                                <td>{{ $t_line_other_payment }}</td>
                                <td>{{$principle2}}</td>
                                <td>{{$interest2}}</td>
                                <td>{{$t_line_payment}}</td>
                                <td>
                                    <a href="{{url("admin/list-member-repayment?group_loan_id={$g_id}&rand_id={$rand_id}")}}"
                                       data-remote="false" data-toggle="modal" data-target="#show-detail-modal-group" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                    <input type="checkbox" data-payment="{{$t_line_payment}}" class="form-check-input c-checked-group" name="approve_check[{{$rand_id}}]" value="{{$g_id}}"/>
                                    <input type="hidden" name="group_loan_id[{{$rand_id}}]" value="{{$g_id}}">
                                </td>
                            </tr>

                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right; padding-right: 50px;"><b>Total</b></td>
                        {{--<td>{{$t_loan_repayment}}</td>--}}
                        <td>{{$t_other_payment}}</td>
                        <td>{{$t_principle}}</td>
                        <td>{{$t_interest}}</td>
                        <td>{{$t_payment}}</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
<div class="my-p-p-p">
                        @if($g_pending_group != null)
                            {!! $g_pending_group->links() !!}
                        @endif
</div>

                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Reference</label>
                    <input class="form-control" name="reference" value="{{\App\Models\GroupLoanTranSaction::grouppaymentSeqRef('group_repayment')}}">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Note</label>
                    <input class="form-control" name="approve_note">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Date</label>
                    <span>
                        <input  class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                    <span></span>
                </div>
            </div>
            <div class="col-sm-4">
                <label>Cash Out</label>
                <select class="form-control sel-cash-out" name="cash_out" required>
                </select>
            </div>
            <div class="col-sm-4">
                <label>Cash Payment</label>
                <input type="number" class="form-control cash-topay" name="" value="" required>
            </div>
            <div class="col-sm-3">
                <input type="submit" class="btn btn-sm btn-primary" style="margin-top: 28px;" value="Payment" />
            </div>
        </div>

    </div>




    </form>

    <div class="modal fade" id="show-detail-modal-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel2"></h4>
                </div>
                <div class="modal-body">
                    <iframe id="iframe" style="width: 100%;height:500px"></iframe>
                </div>
                <div class="modal-footer">
                   {{-- <button type="button" onclick="printDiv()" class="btn btn-default glyphicon glyphicon-print"></button>--}}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('after_scripts')
    <!-- date-range-picker -->
    <script src="{{$base}}/bower_components/moment/min/moment.min.js"></script>
    <script src="{{$base}}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $("#show-detail-modal-group").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $('#iframe').prop('src',link.attr("href"));
        });
        $(function () {
            $('.approve_date').datepicker(
                { format: 'yyyy-mm-dd'}
            );
        });
    </script>

    <script>
        function check_b(){
            $('#check_all_group').on('change',function(event) {

                if(this.checked) { // check select status
                    $('.c-checked-group').each(function() {
                        this.checked = true;  //select all
                        sum_payment();
                    });
                }else{
                    $('.c-checked-group').each(function() {
                        this.checked = false; //deselect all
                        sum_payment();
                    });
                }
            });
            $('.c-checked-group').on('change',function () {
                sum_payment();
            });
        }
        $(function () {
            // $('.select-province').select2();
            check_b();

            $('.sel-cash-out').select2({
                theme: 'bootstrap',
                multiple: false,
                ajax: {
                    url: '{{url("api/account-cash")}}',
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

        });
        $(function () {
            $('.approve_date').datepicker({
                    format: 'yyyy-mm-dd',
                }
            );
        });
        function sum_payment() {
            var total = 0;

            $('.c-checked-group').each(function () {

                if($(this).is(':checked')){
                    var payment = $(this).data('payment');
                    total += payment;
                }
            });

            $('.cash-topay').val(round(total,2));
        }
    </script>
    <script>
        $(".select2_field-group").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select Group",
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
                                    textField = "group_code";
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
                ;

            }
        });
        $(".select2_field-center").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select Center",

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
                                    textField = "code";
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
                ;

            }
        });
        $(".select2_field-loan-product").each(function (i, obj) {
            var form = $(obj).closest('form');
            var url = $(this).data('source');
            if (!$(obj).hasClass("select2-hidden-accessible"))
            {

                $(obj).select2({
                    theme: 'bootstrap',
                    multiple: false,
                    placeholder: "Select Loan Product",
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
                ;

            }
        });

        $(function () {
            $('body').on('change','.select2_field-group,.select2_field-center,.select2_field-loan-product',function () {
                repay_filter();
            });

            $('#remove-filter').on("click", function () {
                $('[name="center_id[]"]').val('').trigger("change");
                $('[name="group_id[]"]').val('').trigger("change");
                $('#remove-filter').hide();
                window.location.reload();
            });

        });

    </script>
    <script>
        function repay_filter() {
            $('#remove-filter').show();

            var center_id = $('.select2_field-center').val();
            var group_id = $('.select2_field-group').val();
            var loan_product_id = $('.select2_field-loan-product').val();

            //alert("hello Ork");
            $.ajax({
                url: "{{url('admin/search-group-repayment')}}",
                method: "get",
                data: {
                    group_id:group_id,
                    center_id:center_id,
                    loan_product_id:loan_product_id
                },
                success: function (res) {

                    // console.log(res);
                    $('.b-group').html();
                    $('.b-group').html(res);
                    check_b();
                }
            });
        }
        $(function () {
            $('.my-p-p-p .pagination').show();
        });
    </script>


@endsection




