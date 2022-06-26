@extends('backpack::layout')
<?php $base = asset('vendor/adminlte') ?>
@section('before_styles')
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{$base}}/bower_components/select2/dist/css/select2.min.css">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

@endsection

@section('content')
    @if(empty($_REQUEST['group_id']))
        @php
        $_REQUEST['group_id'] = 0;
        @endphp
     @endif
     @if(empty($_REQUEST['center_id']))
     @php
        $_REQUEST['center_id'] = 0;
     @endphp
  @endif
    <div class="container-fluid">
        <form method="GET">
            <div class="form-group col-md-4">
                <select class="form-control select2_field-center" data-source="{{url("api/get-center-leader-name-id")}}" multiple name="center_id"  >
                    @php
                        if(is_array($_REQUEST['center_id']))
                        {
                            $center_id = $_REQUEST['center_id'][0];
                        }
                        else {
                            $center_id = $_REQUEST['center_id'];
                        }
                    @endphp
                   @if($center_id != 0)
                        <?php

                            $c = \App\Models\CenterLeader::find($center_id);
                        ?>
                        <option {{isset($_REQUEST['center_id'])?optional($c)->id==$_REQUEST['center_id']?"selected='selected'":'':''}} value="{{optional($c)->id}}">{{optional($c)->title}}</option>
                    @endif
                </select>
            </div>
            <div class="form-group col-md-4">
                <select class="form-control select2_field-group" data-source="{{url("api/get-group-loan2")}}" multiple name="group_id" >
                    @if($_REQUEST['group_id'] != 0)
                        <?php

                        $c = \App\Models\GroupLoan::find($_REQUEST['group_id']);
                        ?>
                        <option {{isset($_REQUEST['group_id'])?optional($c)->id==$_REQUEST['group_id']?"selected='selected'":'':''}} value="{{optional($c)->id}}">{{optional($c)->group_code}}</option>
                    @endif
                </select>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{url('admin/group-dirseburse')}}" class="btn btn-danger">Clear</a>
            </div>

        </form>
    </div>
    <form method="post" action="{{url('admin/group-dirseburse')}}" id="myfrm">
        {!! csrf_field() !!}
        <div class="shadow" style="background: #fff; padding: 10px; overflow: auto;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="b-group">
                        <table class="table table-bordered" style="width: 100%;background-color: white">
                            <thead>
                            <tr>
                                <th>CenterID-Name</th>
                                <th>Group ID</th>
                                <th>Group Name</th>
                                <th>Total Loans</th>
                                <th>Total Charge</th>
                                <th>Total Compulsory</th>
                                <th>Total</th>
                                <th><input type="checkbox" id="check_all_group"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total = 0;

                            $total_loan = 0;
                            $total_charge = 0;
                            $total_compulsory = 0;
                            ?>
                            @if($g_pending != null)
                                @php
                                    $group_mem = $g_pending->groupBy('group_loan_id');
                                    //dd($m,$group_mem);
                                @endphp
                                @foreach($group_mem as $g_id => $rows)
                                    <?php
                                    $group = \App\Models\GroupLoan::find($g_id);
                                    $rand_id = rand(1, 1000) . time() . rand(1, 1000);
                                    $center = null;
                                    if ($group != null) {
                                        $center = \App\Models\CenterLeader::find($group->center_id);
                                    }
                                    //$total += $row->amount;
                                    $total_line_loan = 0;
                                    $total_line_charge = 0;
                                    $total_line_compulsory = 0;
                                    foreach ($rows as $row) {
                                        $total_line_loan += $row->loan_amount;
                                        $charges = \App\Models\LoanCharge::where('charge_type', 2)->where('status','Yes')->where('loan_id', optional($row)->id)->get();
                                        if ($charges != null) {
                                            foreach ($charges as $c) {
                                                $amt_charge = $c->amount;
                                                $total_line_charge += ($c->charge_option == 1 ? $amt_charge : (($row->loan_amount * $amt_charge) / 100));
                                            }
                                        }
                                        $compulsory = \App\Models\LoanCompulsory::where('compulsory_product_type_id', 2)->where('loan_id', optional($row)->id)->first();

                                        if ($compulsory != null) {
                                            $amt_compulsory = $compulsory->saving_amount;
                                            $total_line_compulsory += ($compulsory->charge_option == 1 ? $amt_compulsory : (($row->loan_amount * $amt_compulsory) / 100));

                                        }
                                    }

                                    $total_line = $total_line_loan - $total_line_charge - $total_line_compulsory;

                                    $total_loan += $total_line_loan;
                                    $total_charge += $total_line_charge;
                                    $total_compulsory += $total_line_compulsory;

                                    ?>

                                    <tr id="p-{{$rand_id}}">
                                        <td>{{optional($center)->code}}-{{optional($center)->title}}</td>
                                        <td>{{optional($group)->group_code}}</td>
                                        <td>{{optional($group)->group_name}}</td>
                                        <td>{{ $total_line_loan }}</td>
                                        <td>{{ $total_line_charge }}</td>
                                        <td>{{ $total_line_compulsory }}</td>
                                        <td>{{$total_line}}</td>
                                        <td>

                                            <a href="{{url("admin/list-member-dirseburse?group_loan_id={$g_id}&rand_id={$rand_id}")}}"
                                               data-remote="false" data-toggle="modal"
                                               data-target="#show-detail-modal-group" class="btn btn-xs btn-info"><i
                                                        class="fa fa-eye"></i></a>
                                            <input type="checkbox" class="form-check-input c-checked-group"
                                                   data-payment="{{$total_line}}" name="approve_check[{{$rand_id}}]"
                                                   value="{{$row->group_loan_id}}"/>
                                            <input type="hidden" name="group_loan_id[{{$rand_id}}]" value="{{$row->group_loan_id}}">
                                            <input type="hidden" name="center_id[{{$rand_id}}]" value="{{optional($center)->id}}">
                                        </td>
                                    </tr>


                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; padding-right: 50px;"><b>Total</b></td>
                                <td>{{$total_loan}}</td>
                                <td>{{$total_charge}}</td>
                                <td>{{$total_compulsory}}</td>
                                <td>{{$total_loan-$total_compulsory-$total_charge}}</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="my-p-p-p">
                            {!! $m->links() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">


                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Reference</label>
                        <input class="form-control" name="reference"
                               value="{{\App\Models\GroupLoanTranSaction::groupdisburseSeqRef('group_disbursement')}}">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Note</label>
                        <input class="form-control" name="approve_note">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Date</label>
                        <span>
                        <input class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                        <span></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Cash Payment</label>
                        <input type="number" class="form-control cash-topay" name="" value="" required>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label>Cash Out</label>
                    <select class="form-control js-cash-out" name="cash_out" required></select>
                </div>
                <div class="col-sm-3">
                    <input type="submit" class="btn btn-sm btn-primary" style="margin-top: 28px;" value="Disburse"/>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="show-detail-modal-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-x" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
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


    {{--<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="{{$base}}/bower_components/select2/dist/js/select2.min.js"></script>--}}
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>

    <script>

        $("#show-detail-modal-group").on("show.bs.modal", function (e) {
            var link = $(e.relatedTarget);
            //alert(link.attr("href"));
            // $(this).find(".modal-body").load(link.attr("href"));


            $('#iframe').prop('src', link.attr("href"));


        });
        $(function () {
            $('.approve_date').datepicker(
                {format: 'yyyy-mm-dd'}
            );

            /*$("#show-detail-modal-group").on("show.bs.modal", function(e) {
                alert('ok');
                var link = $(e.relatedTarget);
                $(this).find(".modal-body").load(link.attr("href"));
            });*/
            //alert('ok');
        });

        function sum_payment() {
            var total = 0;

            $('.c-checked-group').each(function () {

                if ($(this).is(':checked')) {
                    var payment = $(this).data('payment');
                    total += payment;
                }
            });
            $('.cash-topay').val(total);

        }
    </script>

    <script>
        function check_b(){
            $('#check_all_group').on('change', function (event) {

                if (this.checked) { // check select status
                    $('.c-checked-group').each(function () {
                        this.checked = true;  //select all
                        sum_payment();

                    });
                } else {
                    $('.c-checked-group').each(function () {
                        this.checked = false; //deselect all

                        sum_payment();
                    });
                }
            });

            $('.c-checked-group').on('change', function () {
                sum_payment();
            });
        }

        $(function () {
             check_b();
            $('.js-cash-out').select2({
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
                ;

            }
        });

        $(function () {
            $('.my-p-p-p .pagination').show();
        });
    </script>
@endsection




